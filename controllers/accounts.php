<?php

    function accounts_add_post()
    {
        $sql = mysql_query("SELECT COUNT(*) AS rowcount FROM account WHERE email='" . mysql_real_escape_string($_POST[email]) . "'");
        $return = mysql_fetch_array($sql);
        
        if ($return[rowcount] > 0)
        {
            header("Location: " . option('base_uri') . "register&error=An account with that email address already exists!");
            exit;
        }

        if ($_POST[password] == "" ||
            $_POST[password] != $_POST[passwordconfirm])
        {
            header("Location: " . option('base_uri') . "register&error=Your passwords do not match!");
            exit;
        }
        
        // create customer on Stripe
        try
        {
            $customer = Stripe_Customer::create(array(
                "description" => mysql_real_escape_string($_POST[name]),
                "email" => mysql_real_escape_string($_POST[email]),
                "card" => array(
                    "number" => mysql_real_escape_string($_POST[cardnumber]),
                    "exp_month" => mysql_real_escape_string($_POST[cardexpmonth]),
                    "exp_year" => mysql_real_escape_string($_POST[cardexpyear]),
                    "cvc" => mysql_real_escape_string($_POST[cardcvc])
                ),
                "plan" => mysql_real_escape_string($_POST[plan])
            ));
        }
        catch (Exception $e)
        {
            header("Location: " . option('base_uri') . "register&error=Your billing information could not be verified!");
            exit;
        }
        
        // purchase number on Twilio
        try
        {
            $twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
            
            $numbers = $twilio->account->available_phone_numbers->getList('US', 'Local');
            $firstNumber = $numbers->available_phone_numbers[0]->phone_number;
            $purchasedNumber = $twilio->account->incoming_phone_numbers->create(array('PhoneNumber' => $firstNumber));
            
            $number = str_replace("+1", "", $purchasedNumber->phone_number);
        }
        catch (Exception $e)
        {
            $customer->delete();

            header("Location: " . option('base_uri') . "register&error=There was a problem provisioning your account!");
            exit;
        }
        
        // generate hash
        $hash = "";
        for ($i = 0; $i < 15; $i++)
        { 
            $d .= rand(1, 30) % 2; 
            $hash .= $d ? chr(rand(97, 122)) : chr(rand(48, 57)); 
        }

        // insert account to database
        $now = date("Y-m-d H:i:s");
        
        $sql = "INSERT INTO account (name, email, password, phonenumber, stripeid, stripeplan, hash, createddate) VALUES
                    ('" . mysql_real_escape_string($_POST[name]) . "', '" . mysql_real_escape_string($_POST[email]) . "', '" . md5(mysql_real_escape_string($_POST[password])) . "', '" . mysql_real_escape_string($number) . "', '" . mysql_real_escape_string($customer->id) . "', '" . mysql_real_escape_string($_POST[plan]) . "', '" . mysql_real_escape_string($hash) . "', '" . $now . "')";
        mysql_query($sql);

        $purchasedNumber->update(array('VoiceUrl' => 'https://paigeapp.com/inbound/' . mysql_insert_id() . '/voice', 'SmsUrl' => 'https://paigeapp.com/inbound/' . mysql_insert_id() . '/sms'));
        
        header("Location: " . option('base_uri') . "login&success=Your account was added successfully!");
        exit;
    }
    
    function accounts_edit()
    {
        Security_Authorize();
        
        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $account = mysql_fetch_array($result);

        if ($_SESSION['CurrentAccount_ID'] != $account[id])
        {
            header("Location: " . option('base_uri') . "&error=You are not authorized to edit that account!");
            exit;
        }
        
        $customer = Stripe_Customer::retrieve($account[stripeid]);
        $creditcard = "************" . $customer->active_card->last4;
        
        $nextcharge = date("F j, Y", strtotime($customer->next_recurring_charge->date));
        
        if ($account != null)
        {
            set("title", "Edit Account");
            set("account", $account);
            set("creditcard", $creditcard);
            set("nextcharge", $nextcharge);
            return html("accounts/edit.php");
        }
        else
        {
            set("title", "Account Not Found");
            set("type", "account");
            return html("error/notfound.php");
        }
    }
    
    function accounts_edit_post()
    {
        Security_Authorize();
        
        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $account = mysql_fetch_array($result);

        if ($_SESSION['CurrentAccount_ID'] != $account[id])
        {
            header("Location: " . option('base_uri') . "&error=You are not authorized to edit that account!");
            exit;
        }
        
        $now = date("Y-m-d H:i:s");
        
        $sql = "UPDATE account SET name='" . mysql_real_escape_string($_POST[name]) . "', email='" . mysql_real_escape_string($_POST[email]) . "' WHERE id='" . mysql_real_escape_string($account[id]) . "'";
        mysql_query($sql);
        
        if ($account[stripeplan] != $_POST[stripeplan])
        {
            $customer = Stripe_Customer::retrieve("cus_ZeIL4hOQAT6inx");
            $customer->updateSubscription(array("prorate" => true, "plan" => $_POST[stripeplan]));

            $sql = "UPDATE account SET stripeplan='" . mysql_real_escape_string($_POST[stripeplan]) . "' WHERE id='" . mysql_real_escape_string($account[id]) . "'";
            mysql_query($sql);
        }

        if ($_POST[newpassword] == "" ||
            $_POST[newpassword] != $_POST[newpasswordconfirm])
        {
            $sql = "UPDATE account SET password='" . md5(mysql_real_escape_string($_POST[newpassword])) . "' WHERE id='" . mysql_real_escape_string($account[id]) . "'";
            mysql_query($sql);
        }

        if ($_SESSION['CurrentAccount_ID'] == params('id'))
        {
            Security_Refresh(params('id'));
        }
        
        header("Location: " . option('base_uri') . "accounts/$account[id]&success=Your account was updated successfully!");
        exit;
    }
    
    function accounts_delete()
    {
        Security_Authorize();
        
        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $account = mysql_fetch_array($result);

        if ($_SESSION['CurrentAccount_ID'] != $account[id])
        {
            header("Location: " . option('base_uri') . "&error=You are not authorized to edit that account!");
            exit;
        }
        
        // delete customer on Stripe
        $customer = Stripe_Customer::retrieve($account[stripeid]);
        $customer->delete();
        
        // release number on Twilio
        $twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
        foreach ($twilio->account->incoming_phone_numbers as $number)
        {
            if (str_replace("+1", "", $number->phone_number) == $account[phonenumber])
            {
                $twilio->account->incoming_phone_numbers->delete($number->sid);
                break;
            }
        }
        
        $sql = "DELETE FROM account WHERE id='" . mysql_real_escape_string(params('id')) . "'";    
        mysql_query($sql);

        $sql = "DELETE FROM history WHERE accountid='" . mysql_real_escape_string(params('id')) . "'";    
        mysql_query($sql);

        $sql = "DELETE FROM member WHERE accountid='" . mysql_real_escape_string(params('id')) . "'";    
        mysql_query($sql);

        $sql = "DELETE FROM queue WHERE accountid='" . mysql_real_escape_string(params('id')) . "'";    
        mysql_query($sql);

        $sql = "DELETE FROM schedule WHERE accountid='" . mysql_real_escape_string(params('id')) . "'";    
        mysql_query($sql);

        header("Location: " . option('base_uri') . "logout&success=Your account was deleted successfully!");
        exit;
    }

?>