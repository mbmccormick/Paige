<?php

    function login()
    {
        set("title", "Login");
        return html("security/login.php", "basic.php");
    }
    
    function login_post()
    {
        if (Security_Login($_POST[email], $_POST[password]) == true)
        {
            header("Location: " . option('base_uri'));
            exit;
        }
        else
        {
            header("Location: " . option('base_uri') . "login&error=Please check your login credentials and try again.");
            exit;
        }
    }
    
    function login_reset()
    {
        set("title", "Reset Password");
        return html("security/reset.php", "basic.php");
    }
    
    function login_reset_post()
    {
        $result = mysql_query("SELECT * FROM account WHERE email='" . mysql_real_escape_string($_POST[email]) . "'");
        $account = mysql_fetch_array($result);
        
        $password = "";
        for ($i = 0; $i < 12; $i++)
        { 
            $d .= rand(1, 30) % 2; 
            $password .= $d ? chr(rand(65, 90)) : chr(rand(48, 57)); 
        }
        
        if (mail($account[email], "Your New " . ApplicationName . " Password", "You recently requested a new password for " . ApplicationName . ". Your new password is " . $password . ".\n\n--\n" . ApplicationName . "", "From: " . ApplicationName . " <" . EmailAddress . ">") == true)
        {        
            $sql = "UPDATE account SET password='" . mysql_real_escape_string(md5($password)) . "' WHERE id='" . mysql_real_escape_string($account[id]) . "'";
            mysql_query($sql);
            
            header("Location: " . option('base_uri') . "login&success=Your password has been reset and a new one was just emailed to you!");
            exit;
        }
        else
        {
            header("Location: " . option('base_uri') . "login&error=Something went wrong, please contact our support team!");
            exit;
        }
    }
    
    function logout()
    {
        if (Security_Logout() == true)
        {
            header("Location: " . option('base_uri') . "login");
            exit;
        }
    }

?>