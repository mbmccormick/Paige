<?php

    function members_list()
    {
        Security_Authorize();
        
        $index = 1;
        
        $result = mysql_query("SELECT * FROM member ORDER BY name ASC");
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
            $body .= "<a href='" . option('base_uri') . "members/$row[id]'>Edit</a>\n";
            $body .= "</td>\n";
            
            $body .= "</tr>\n";
            
            $index++;
        }
        
        if (mysql_num_rows($result) == 0)
        {
            $body .= "<tr>\n";
            $body .= "<td colspan='3'>There are currently no members setup.</td>\n";
            $body .= "</tr>\n";
        }
        
        set("title", "Members");
        set("body", $body);
        return html("members/list.php");
    }
    
    function members_add()
    {
        Security_Authorize();
        
        set("title", "New Member");
        return html("members/add.php");
    }
    
    function members_add_post()
    {
        Security_Authorize();
        
        $now = date("Y-m-d H:i:s");
        
        $sql = "INSERT INTO member (name, email, phonenumber, isoptedin, createddate) VALUES
                    ('" . mysql_real_escape_string($_POST[name]) . "', '" . mysql_real_escape_string($_POST[email]) . "', '" . mysql_real_escape_string($_POST[phonenumber]) . "', '" . mysql_real_escape_string($_POST[isoptedin]) . "', '" . $now . "')";
        mysql_query($sql);
        
		header("Location: " . option('base_uri') . "members&success=Your member was added successfully!");
        exit;
    }
    
    function members_edit()
    {
        Security_Authorize();
        
        if ($_SESSION['CurrentAccount_IsAdministrator'] == 0 &&
            $_SESSION['CurrentAccount_ID'] != params('id'))
        {
            header("Location: " . option('base_uri') . "members&error=You are not authorized to edit that member!");
            exit;
        }
        
        $result = mysql_query("SELECT * FROM member WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $member = mysql_fetch_array($result);
        
        if ($member != null)
        {
            set("title", "Edit Member");
            set("member", $member);
            return html("members/edit.php");
        }
        else
        {
            set("title", "Member Not Found");
            set("type", "member");
            return html("error/notfound.php");
        }
    }
    
    function members_edit_post()
    {
        Security_Authorize();
        
        if ($_SESSION['CurrentAccount_IsAdministrator'] == 0 &&
            $_SESSION['CurrentAccount_ID'] != params('id'))
        {
            header("Location: " . option('base_uri') . "members&error=You are not authorized to edit that member!");
            exit;
        }
        
        $result = mysql_query("SELECT * FROM member WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $member = mysql_fetch_array($result);
        
        $now = date("Y-m-d H:i:s");
        
        if (md5($_POST[currentpassword]) == $member[password])
        {
            if ($member[newpassword] == $member[newpasswordconfirm])
            {
                $sql = "UPDATE member SET membername='" . mysql_real_escape_string($_POST[membername]) . "', name='" . mysql_real_escape_string($_POST[name]) . "', email='" . mysql_real_escape_string($_POST[email]) . "', password='" . md5(mysql_real_escape_string($_POST[newpassword])) . "', isadministrator='" . mysql_real_escape_string($_POST[isadministrator]) . "' WHERE id='" . mysql_real_escape_string($member[id]) . "'";
                mysql_query($sql);
            }
            else
            {
                header("Location: " . option('base_uri') . "members/$member[id]&error=Your new password does not match!");
                exit;
            }
        }
        else
        {
            $sql = "UPDATE member SET membername='" . mysql_real_escape_string($_POST[membername]) . "', name='" . mysql_real_escape_string($_POST[name]) . "', email='" . mysql_real_escape_string($_POST[email]) . "', isadministrator='" . mysql_real_escape_string($_POST[isadministrator]) . "' WHERE id='" . mysql_real_escape_string($member[id]) . "'";
            mysql_query($sql);
        }
        
        if ($_SESSION['CurrentAccount_ID'] == params('id'))
        {
            Security_Refresh(params('id'));
        }
        
        header("Location: " . option('base_uri') . "members/$member[id]&success=Your member was updated successfully!");
        exit;
    }
    
    function members_delete()
    {
        Security_Authorize();
        
        if ($_SESSION['CurrentAccount_IsAdministrator'] == 0 &&
            $_SESSION['CurrentAccount_ID'] != params('id'))
        {
            header("Location: " . option('base_uri') . "members/" . params('id') . "&error=You are not authorized to delete this member!");
            exit;
        }
    
        $sql = "DELETE FROM member WHERE id='" . mysql_real_escape_string(params('id')) . "'";    
        mysql_query($sql);

        header("Location: " . option('base_uri') . "members&success=Your member was deleted successfully!");
        exit;
    }

?>