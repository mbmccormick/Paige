<?php

    function Security_Login($email, $password, $rememberme = false)
    {
        $sql = mysql_query("SELECT * FROM account WHERE email='" . mysql_real_escape_string($email) . "' AND password='" . mysql_real_escape_string(md5($password)) . "'");
        
        if (mysql_num_rows($sql) > 0)
        {
            $row = mysql_fetch_array($sql);
            
            Security_Refresh($row[id]);

            if ($rememberme == true)
            {
                setcookie("email", $row[email], time() + (60 * 60 * 24 * 7), "/", "paigeapp.com", true);
                setcookie("password", $row[password], time() + (60 * 60 * 24 * 7), "/", "paigeapp.com", true);
            }

            return true;
        }
        else
        {
            return false;
        }
    }

    function Security_CookieLogin($email, $password)
    {
        $sql = mysql_query("SELECT * FROM account WHERE email='" . mysql_real_escape_string($email) . "' AND password='" . mysql_real_escape_string($password) . "'");
        
        if (mysql_num_rows($sql) > 0)
        {
            $row = mysql_fetch_array($sql);
            
            Security_Refresh($row[id]);

            setcookie("email", $row[email], time() + (60 * 60 * 24 * 7), "/", "paigeapp.com", true);
            setcookie("password", $row[password], time() + (60 * 60 * 24 * 7), "/", "paigeapp.com", true);

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

        setcookie("email", "", time() - (60 * 60), "/", "paigeapp.com");
        setcookie("password", "", time() - (60 * 60), "/", "paigeapp.com");
        
        return true;
    }
    
    function Security_Authorize()
    {
        if ($_SESSION['CurrentAccount_ID'] == null)
        {
            header("Location: " . option('base_uri') . "login");
            exit;
        }
    }
    
    function Security_Refresh($id)
    {
        $sql = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string($id) . "'");
        
        if (mysql_num_rows($sql) > 0)
        {
            $row = mysql_fetch_array($sql);
            
            $_SESSION['CurrentAccount_ID'] = $row[id];
            $_SESSION['CurrentAccount_Name'] = $row[name];
			$_SESSION['CurrentAccount_PhoneNumber'] = $row[phonenumber];
            $_SESSION['CurrentAccount_Timezone'] = $row[timezone];
        }
    }
    
?>