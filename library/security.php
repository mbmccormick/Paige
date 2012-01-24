<?php

    function Security_Login($email, $password)
    {
        $sql = mysql_query("SELECT * FROM account WHERE email='" . mysql_real_escape_string($email) . "' AND password='" . mysql_real_escape_string(md5($password)) . "'");
        
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