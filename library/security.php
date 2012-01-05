<?php

    function Security_Login($username, $password)
    {
        $sql = mysql_query("SELECT * FROM user WHERE username='" . mysql_real_escape_string($username) . "' AND password='" . mysql_real_escape_string(md5($password)) . "'");
        
        if (mysql_num_rows($sql) > 0)
        {
            $row = mysql_fetch_array($sql);
            
            Security_Refresh($row[id]);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function Security_Login_OpenID($identity)
    {
        $sql = mysql_query("SELECT * FROM user WHERE identity='" . mysql_real_escape_string($identity) . "'");
        
        if (mysql_num_rows($sql) > 0)
        {
            $row = mysql_fetch_array($sql);
            
            Security_Refresh($row[id]);        
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function Security_Logout()
    {
        session_destroy();
        
        return true;
    }
    
    function Security_Authorize()
    {
        if ($_SESSION["CurrentUser_ID"] == null)
        {
            header("Location: " . option('base_uri') . "login");
            exit;
        }
    }
    
    function Security_Refresh($id)
    {
        $sql = mysql_query("SELECT * FROM user WHERE id='" . mysql_real_escape_string($id) . "'");
        
        if (mysql_num_rows($sql) > 0)
        {
            $row = mysql_fetch_array($sql);
            
            $_SESSION["CurrentUser_ID"] = $row[id];
			$_SESSION["CurrentUser_AccountID"] = $row[accountid];
            $_SESSION["CurrentUser_Name"] = $row[name];
            $_SESSION["CurrentUser_Username"] = $row[username];
            $_SESSION["CurrentUser_IsAdministrator"] = $row[isadministrator];
			
			$sql = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string($row[accountid]) . "'");
			$row = mysql_fetch_array($sql);
			
			$_SESSION["CurrentAccount_Name"] = $row[name];
			$_SESSION["CurrentAccount_PhoneNumber"] = $row[phonenumber];
        }
    }
    
?>