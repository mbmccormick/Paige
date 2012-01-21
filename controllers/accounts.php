<?php

    function accounts_add()
    {
        set("title", "New Account");
        return html("accounts/add.php");
    }
    
    function accounts_add_post()
    {
        $sql = mysql_query("SELECT COUNT(*) AS rowcount FROM account WHERE email='" . mysql_real_escape_string($_POST[email]) . "'");
        $return = mysql_fetch_array($sql);
        
        if ($return[rowcount] > 0)
        {
            header("Location: " . option('base_uri') . "accounts/add&error=An account with that email address already exists!");
            exit;
        }
        
        // create customer on Stripe
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
        
        // purchase number on Twilio
        $twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
        $purchasedNumber = $twilio->account->incoming_phone_numbers->create(array('AreaCode' => $_POST[areacode]));
        
        $number = str_replace("+1", "", $purchasedNumber->phone_number);
        
        // generate hash
        $hash = "";
        for ($i = 0; $i < 15; $i++)
        { 
            $d .= rand(1, 30) % 2; 
            $hash .= $d ? chr(rand(97, 122)) : chr(rand(48, 57)); 
        }

        // insert account to database
        $now = date("Y-m-d H:i:s");
        
        $sql = "INSERT INTO account (name, email, phonenumber, stripeid, stripeplan, hash, createddate) VALUES
                    ('" . mysql_real_escape_string($_POST[name]) . "', '" . mysql_real_escape_string($_POST[email]) . "', '" . mysql_real_escape_string($number) . "', '" . mysql_real_escape_string($customer->id) . "', '" . mysql_real_escape_string($_POST[plan]) . "', '" . mysql_real_escape_string($hash) . "', '" . $now . "')";
        mysql_query($sql);
        
        header("Location: " . option('base_uri') . "accounts&success=Your account was added successfully!");
        exit;
    }
    
    function accounts_edit()
    {
        Security_Authorize();
        
        if ($_SESSION['CurrentAccount_IsAdministrator'] == 0 &&
            $_SESSION['CurrentAccount_ID'] != params('id'))
        {
            header("Location: " . option('base_uri') . "accounts&error=You are not authorized to edit that account!");
            exit;
        }
        
        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $account = mysql_fetch_array($result);
        
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
        
        if ($_SESSION['CurrentAccount_IsAdministrator'] == 0 &&
            $_SESSION['CurrentAccount_ID'] != params('id'))
        {
            header("Location: " . option('base_uri') . "accounts&error=You are not authorized to edit that account!");
            exit;
        }
        
        $now = date("Y-m-d H:i:s");
        
        $sql = "UPDATE account SET name='" . mysql_real_escape_string($_POST[name]) . "', email='" . mysql_real_escape_string($_POST[email]) . "' WHERE id='" . mysql_real_escape_string($account[id]) . "'";
        mysql_query($sql);
        
        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $account = mysql_fetch_array($result);
        
        if ($account[stripeplan] != $_POST[stripeplan])
        {
            $customer = Stripe_Customer::retrieve("cus_ZeIL4hOQAT6inx");
            $customer->updateSubscription(array("prorate" => true, "plan" => $_POST[stripeplan]));

            $sql = "UPDATE account SET stripeplan='" . mysql_real_escape_string($_POST[stripeplan]) . "' WHERE id='" . mysql_real_escape_string($account[id]) . "'";
            mysql_query($sql);
        }

        if ($_POST[newpassword] != "" &&
            $_POST[newpassword] == $_POST[newpasswordconfirm])
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
        
        if ($_SESSION['CurrentAccount_IsAdministrator'] == 0 &&
            $_SESSION['CurrentAccount_ID'] != params('id'))
        {
            header("Location: " . option('base_uri') . "accounts/" . params('id') . "&error=You are not authorized to delete this account!");
            exit;
        }
        
        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $account = mysql_fetch_array($result);
        
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

        header("Location: " . option('base_uri') . "accounts&success=Your account was deleted successfully!");
        exit;
    }

?>