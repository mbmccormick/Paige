<?php

    function users_list()
    {
        Security_Authorize();
        
        if ($_SESSION["CurrentUser_IsAdministrator"] == "0")
        {
            header("Location: " . option('base_uri') . "&error=You are not authorized to view the list of users!");
            exit;
        }
        
        $index = 1;
        
        $result = mysql_query("SELECT * FROM user ORDER BY name ASC");
        while($row = mysql_fetch_array($result))
        {
            $body .= "<tr>\n";
            
            $body .= "<th>\n";
            $body .= $index;
            $body .= "</th>\n";
            $body .= "<td>\n";
            $body .= $row[name];
            $body .= "</td>\n";
            $body .= "<td>\n";
            $body .= "<a href='" . option('base_uri') . "users/$row[id]'>Edit</a>\n";
            $body .= "</td>\n";
            
            $body .= "</tr>\n";
            
            $index++;
        }
        
        if (mysql_num_rows($result) == 0)
        {
            $body .= "<tr>\n";
            $body .= "<td colspan='2'>There are currently no users setup.</td>\n";
            $body .= "</tr>\n";
        }
        
        set("title", "Users");
        set("body", $body);
        return html("users/list.php");
    }
    
    function users_add()
    {
        Security_Authorize();
        
        if ($_SESSION["CurrentUser_IsAdministrator"] == "0")
        {
            header("Location: " . option('base_uri') . "users&error=You are not authorized to add a new user!");
            exit;
        }
        
        set("title", "New User");
        return html("users/add.php");
    }
    
    function users_add_post()
    {
        Security_Authorize();
        
        if ($_SESSION["CurrentUser_IsAdministrator"] == "0")
        {
            header("Location: " . option('base_uri') . "users&error=You are not authorized to add a new user!");
            exit;
        }
        
        $sql = mysql_query("SELECT COUNT(*) AS rowcount FROM user WHERE email='" . mysql_real_escape_string($_POST[email]) . "'");
        $return = mysql_fetch_array($sql);
        
        if ($return[rowcount] > 0)
        {
            header("Location: " . option('base_uri') . "users/add&error=A user with that email address already exists!");
            exit;
        }
        
        $now = date("Y-m-d H:i:s");
        
        if ($user[password] == $user[passwordconfirm])
        {
            $sql = "INSERT INTO user (username, name, email, password, isadministrator, createddate) VALUES
                    ('" . mysql_real_escape_string($_POST[username]) . "', '" . mysql_real_escape_string($_POST[name]) . "', '" . mysql_real_escape_string($_POST[email]) . "', '" . mysql_real_escape_string(md5($_POST[password])) . "', '" . mysql_real_escape_string($_POST[isadministrator]) . "', '" . $now . "')";
            mysql_query($sql);
        }
        else
        {
            header("Location: " . option('base_uri') . "users/add&error=Your passwords do not match!");
            exit;
        }
        
        header("Location: " . option('base_uri') . "users&success=Your user was added successfully!");
        exit;
    }
    
    function users_edit()
    {
        Security_Authorize();
        
        if ($_SESSION["CurrentUser_IsAdministrator"] == "0" &&
            $_SESSION["CurrentUser_ID"] != params('id'))
        {
            header("Location: " . option('base_uri') . "users&error=You are not authorized to edit that user!");
            exit;
        }
        
        $result = mysql_query("SELECT * FROM user WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $user = mysql_fetch_array($result);
        
        if ($user != null)
        {
            set("title", "Edit User");
            set("user", $user);
            return html("users/edit.php");
        }
        else
        {
            set("title", "User Not Found");
            set("type", "user");
            return html("error/notfound.php");
        }
    }
    
    function users_edit_post()
    {
        Security_Authorize();
        
        if ($_SESSION["CurrentUser_IsAdministrator"] == "0" &&
            $_SESSION["CurrentUser_ID"] != params('id'))
        {
            header("Location: " . option('base_uri') . "users&error=You are not authorized to edit that user!");
            exit;
        }
        
        $result = mysql_query("SELECT * FROM user WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $user = mysql_fetch_array($result);
        
        $now = date("Y-m-d H:i:s");
        
        if (md5($_POST[currentpassword]) == $user[password])
        {
            if ($user[newpassword] == $user[newpasswordconfirm])
            {
                $sql = "UPDATE user SET username='" . mysql_real_escape_string($_POST[username]) . "', name='" . mysql_real_escape_string($_POST[name]) . "', email='" . mysql_real_escape_string($_POST[email]) . "', password='" . md5(mysql_real_escape_string($_POST[newpassword])) . "', isadministrator='" . mysql_real_escape_string($_POST[isadministrator]) . "' WHERE id='" . mysql_real_escape_string($user[id]) . "'";
                mysql_query($sql);
            }
            else
            {
                header("Location: " . option('base_uri') . "users/$user[id]&error=Your new password does not match!");
                exit;
            }
        }
        else
        {
            $sql = "UPDATE user SET username='" . mysql_real_escape_string($_POST[username]) . "', name='" . mysql_real_escape_string($_POST[name]) . "', email='" . mysql_real_escape_string($_POST[email]) . "', isadministrator='" . mysql_real_escape_string($_POST[isadministrator]) . "' WHERE id='" . mysql_real_escape_string($user[id]) . "'";
            mysql_query($sql);
        }
        
        if ($_SESSION['CurrentUser_ID'] == params('id'))
        {
            Security_Refresh(params('id'));
        }
        
        header("Location: " . option('base_uri') . "users/$user[id]&success=Your user was updated successfully!");
        exit;
    }
    
    function users_delete()
    {
        Security_Authorize();
        
        if ($_SESSION["CurrentUser_IsAdministrator"] == "0" &&
            $_SESSION["CurrentUser_ID"] != params('id'))
        {
            header("Location: " . option('base_uri') . "users/" . params('id') . "&error=You are not authorized to delete this user!");
            exit;
        }
    
        $sql = "DELETE FROM user WHERE id='" . mysql_real_escape_string(params('id')) . "'";    
        mysql_query($sql);

        header("Location: " . option('base_uri') . "users&success=Your user was deleted successfully!");
        exit;
    }

?>