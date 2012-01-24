<?php

    function members_list()
    {
        Security_Authorize();
        
        $index = 1;
        
        $result = mysql_query("SELECT * FROM member WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY name ASC");
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

        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string($_SESSION['CurrentAccount_ID']) . "'");
        $account = mysql_fetch_array($result);

        $result = mysql_query("SELECT * FROM member WHERE accountid='" . mysql_real_escape_string($account[id]) . "'");
        $count = mysql_num_rows($result);

        if (($count >= 10 && $account[stripeplan] == 1) ||
            ($count >= 20 && $account[stripeplan] == 2) ||
            ($count >= 50 && $account[stripeplan] == 3))
        {
            header("Location: " . option('base_uri') . "members&error=You have reached the maxium number of team members for your account plan!");
            exit;
        }
        
        set("title", "New Member");
        return html("members/add.php");
    }
    
    function members_add_post()
    {
        Security_Authorize();
        
        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string($_SESSION['CurrentAccount_ID']) . "'");
        $account = mysql_fetch_array($result);

        $result = mysql_query("SELECT * FROM member WHERE accountid='" . mysql_real_escape_string($account[id]) . "'");
        $count = mysql_num_rows($result);

        if (($count >= 10 && $account[stripeplan] == 1) ||
            ($count >= 15 && $account[stripeplan] == 2) ||
            ($count >= 20 && $account[stripeplan] == 3))
        {
            header("Location: " . option('base_uri') . "members&error=You have reached the maxium number of team members for your account plan!");
            exit;
        }
        
        $now = AccountTime();
        
        $sql = "INSERT INTO member (accountid, name, email, phonenumber, isoptedin, createddate) VALUES
                    ('" . mysql_real_escape_string($_SESSION['CurrentAccount_ID']) . "', '" . mysql_real_escape_string($_POST[name]) . "', '" . mysql_real_escape_string($_POST[email]) . "', '" . mysql_real_escape_string(str_replace("-", "", $_POST[phonenumber])) . "', '" . mysql_real_escape_string($_POST[isoptedin]) . "', '" . $now . "')";
        mysql_query($sql);
        
		header("Location: " . option('base_uri') . "members&success=Your member was added successfully!");
        exit;
    }
    
    function members_edit()
    {
        Security_Authorize();
        
        $result = mysql_query("SELECT * FROM member WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $member = mysql_fetch_array($result);
        
        if ($_SESSION['CurrentAccount_ID'] != $member[accountid])
        {
            header("Location: " . option('base_uri') . "members&error=You are not authorized to edit that member!");
            exit;
        }
        
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
        
        $result = mysql_query("SELECT * FROM member WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $member = mysql_fetch_array($result);
        
        if ($_SESSION['CurrentAccount_ID'] != $member[accountid])
        {
            header("Location: " . option('base_uri') . "members&error=You are not authorized to edit that member!");
            exit;
        }
        
        $now = AccountTime();
        
        $sql = "UPDATE member SET name='" . mysql_real_escape_string($_POST[name]) . "', email='" . mysql_real_escape_string($_POST[email]) . "', phonenumber='" . mysql_real_escape_string(str_replace("-", "", $_POST[phonenumber])) . "' , isoptedin='" . mysql_real_escape_string($_POST[isoptedin]) . "' WHERE id='" . mysql_real_escape_string($member[id]) . "'";
        mysql_query($sql);
        
        header("Location: " . option('base_uri') . "members/$member[id]&success=Your member was updated successfully!");
        exit;
    }
    
    function members_delete()
    {
        Security_Authorize();
        
        $result = mysql_query("SELECT * FROM member WHERE id='" . mysql_real_escape_string(params('id')) . "'");
        $member = mysql_fetch_array($result);
        
        if ($_SESSION['CurrentAccount_ID'] != $member[accountid])
        {
            header("Location: " . option('base_uri') . "members/" . params('id') . "&error=You are not authorized to delete this member!");
            exit;
        }
		
		$sql = "DELETE FROM member WHERE id='" . mysql_real_escape_string($member[id]) . "'";
        mysql_query($sql);
		
		$sqlDelete = "DELETE FROM schedule WHERE memberid='" . mysql_real_escape_string($member[id]) . "'";
		mysql_query($sqlDelete);
		
        header("Location: " . option('base_uri') . "members&success=Your member was deleted successfully!");
        exit;
    }

?>