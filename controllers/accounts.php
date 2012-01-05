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
		$purchasedNumber = $client->account->incoming_phone_numbers->create(array('AreaCode' => $_POST[areacode]));
		
		// insert account to database
        $now = date("Y-m-d H:i:s");
        
        $sql = "INSERT INTO account (name, email, phonenumber, stripeid, stripeplan, createddate) VALUES
                    ('" . mysql_real_escape_string($_POST[name]) . "', '" . mysql_real_escape_string($_POST[email]) . "', '" . mysql_real_escape_string($purchasedNumber->phone_number) . "', '" . mysql_real_escape_string($customer->id) . "', '" . mysql_real_escape_string($customer->plan) . "', '" . $now . "')";
		mysql_query($sql);
        
		// header("Location: " . option('base_uri') . "accounts&success=Your account was added successfully!");
        // exit;
    }
    
    function accounts_edit()
    {
        Security_Authorize();
        
        if ($_SESSION["CurrentUser_IsAdministrator"] == "0" &&
            $_SESSION["CurrentUser_AccountID"] != params('id'))
        {
            header("Location: " . option('base_uri') . "accounts&error=You are not authorized to edit that account!");
            exit;
        }
        
        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $account = mysql_fetch_array($result);
        
        if ($account != null)
        {
            set("title", "Edit Account");
            set("account", $account);
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
        
        if ($_SESSION["CurrentUser_IsAdministrator"] == "0" &&
            $_SESSION["CurrentUser_AccountID"] != params('id'))
        {
            header("Location: " . option('base_uri') . "accounts&error=You are not authorized to edit that account!");
            exit;
        }
        
        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $account = mysql_fetch_array($result);
        
        $now = date("Y-m-d H:i:s");
        
        $sql = "UPDATE account SET accountname='" . mysql_real_escape_string($_POST[accountname]) . "', name='" . mysql_real_escape_string($_POST[name]) . "', email='" . mysql_real_escape_string($_POST[email]) . "', isadministrator='" . mysql_real_escape_string($_POST[isadministrator]) . "' WHERE id='" . mysql_real_escape_string($account[id]) . "'";
		mysql_query($sql);
        
        if ($_SESSION["CurrentUser_ID"] == params('id'))
        {
            Security_Refresh(params('id'));
        }
        
        header("Location: " . option('base_uri') . "accounts/$account[id]&success=Your account was updated successfully!");
        exit;
    }
    
    function accounts_delete()
    {
        Security_Authorize();
        
        if ($_SESSION["CurrentUser_IsAdministrator"] == "0" &&
            $_SESSION["CurrentUser_AccountID"] != params('id'))
        {
            header("Location: " . option('base_uri') . "accounts/" . params('id') . "&error=You are not authorized to delete this account!");
            exit;
        }
    
        $sql = "DELETE FROM account WHERE id='" . mysql_real_escape_string(params('id')) . "'";    
        mysql_query($sql);

        header("Location: " . option('base_uri') . "accounts&success=Your account was deleted successfully!");
        exit;
    }

?>