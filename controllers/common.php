<?php

    function common_dashboard()
    {
        if ($_SESSION['CurrentAccount_ID'] == null)
        {
            set("title", "Home");
            return html("common/home.php");
        }
        else
        {
            $now = date("Y-m-d H:i:s");

            // lookup the on-call member
            $result = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
            $shift = mysql_fetch_array($result);

            $result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
            $member = mysql_fetch_array($result);

            $oncall = $member[name];

            $result = mysql_query("SELECT * FROM history WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 3");
            while($row = mysql_fetch_array($result))
            {
                $result2 = mysql_query("SELECT * FROM member WHERE id='" . $row[memberid] . "'");
                $member = mysql_fetch_array($result2);

                $history .= "<div class='history-item'>\n";
                $history .= "<b><a href='" . option('base_uri') . "members/$member[id]'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via web</b><br />\n";
                $history .= $row[message] . "<br />\n";
                $history .= "</div><br />\n";
            }

            set("title", "Dashboard");
            set("oncall", $oncall);
            set("history", $history);
            return html("common/dashboard.php");
        }
    }

    function common_dashboard_post()
    {
    	Security_Authorize();

        if ($_POST[recipient] == 1)
    	   RequestUrl("http://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&message=" . urlencode($_POST[message]));
        else
            RequestUrl("http://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&team=true&message=" . urlencode($_POST[message]));
        
        header("Location: /&success=Your page was sent successfully!");
        exit;
    }

    function common_register()
    {
        set("title", "Register");
        return html("accounts/add.php");
    }

    function common_about()
    {
        set("title", "About");
        return html("common/about.php");
    }

    function common_execute()
    {
        $now = date("Y-m-d H:i:s");
        
        $result = mysql_query("SELECT * FROM queue WHERE duedatetime <= '" . $now . "'");
        while($row = mysql_fetch_array($result))
        {
            RequestUrl($row[url]);
            mysql_query("DELETE FROM queue WHERE id='" . $row[id] . "'");
        }
    }

?>