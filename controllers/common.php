<?php
        
    function common_dashboard()
    {
        if ($_SESSION['CurrentAccount_ID'] == null)
        {
            if (isset($_COOKIE[email]) == true &&
                isset($_COOKIE[password]) == true)
            {
                if (Security_CookieLogin($_COOKIE[email], $_COOKIE[password]) == true)
                {
                    header("Location: " . option('base_uri'));
                    exit;
                }
            }

            set("title", "Home");
            return html("common/home.php");
        }
        else
        {
            $now = AccountTime();

            // lookup the on-call member
            $result = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
            $shift = mysql_fetch_array($result);

            $result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
            $member = mysql_fetch_array($result);

            $members .= "<option value='" . $member[id] . "'>" . $member[name] . " (on-call team member)</option>\n";
            
            $result = mysql_query("SELECT * FROM member WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "'");
            while($row = mysql_fetch_array($result))
            {
                if ($row[id] != $member[id])
                {
                    $members .= "<option value='" . $row[id] . "'>" . $row[name] . "</option>\n";
                }
            }

            $members .= "<option value='-1'>All team members</option>\n";

            $result = mysql_query("SELECT * FROM history WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 3");
            while($row = mysql_fetch_array($result))
            {
                $result2 = mysql_query("SELECT * FROM member WHERE id='" . $row[memberid] . "'");
                $member = mysql_fetch_array($result2);

                $history .= "<div class='history-item'>\n";
                if ($row[status] == 0)
                    $history .= "<div class='history-item-image'><img src='/public/img/0.png' title='This page is in progress.' /></div>";
                elseif ($row[status] == 1)
                    $history .= "<div class='history-item-image'><img src='/public/img/1.png' title='This page was confirmed.' /></div>";
                elseif ($row[status] == 2)
                    $history .= "<div class='history-item-image'><img src='/public/img/2.png' title='This page was not confirmed.' /></div>";
                $history .= "<div class='history-item-body'>\n";
                if ($row[medium] == 0)
                    $history .= "<b><a href='" . option('base_uri') . "members/" . $member[id] . "'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via unknown</b><br />\n";
                elseif ($row[medium] == 1)
                    $history .= "<b><a href='" . option('base_uri') . "members/" . $member[id] . "'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via web</b><br />\n";
                elseif ($row[medium] == 2)
                    $history .= "<b><a href='" . option('base_uri') . "members/" . $member[id] . "'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via telephone</b><br />\n";
                elseif ($row[medium] == 3)
                    $history .= "<b><a href='" . option('base_uri') . "members/" . $member[id] . "'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via hook</b><br />\n";
                elseif ($row[medium] == 4)
                    $history .= "<b><a href='" . option('base_uri') . "members/" . $member[id] . "'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via scheduler</b><br />\n";
                $history .= $row[message] . "<br />\n";
                $history .= "</div></div><br />\n";
            }

            set("title", "Dashboard");
            set("members", $members);
            set("history", $history);
            return html("common/dashboard.php");
        }
    }

    function common_dashboard_post()
    {
        Security_Authorize();

        if ($_POST[recipient] > 0)
        {
            $now = AccountTime();
        
            LogHistory($_POST[recipient], $_POST[message], 1);
            
            RequestUrl("https://" . $_SERVER['HTTP_HOST'] . "/page/" . $_SESSION['CurrentAccount_ID'] . "/step1?message=" . urlencode($_POST[message]) . "&memberid=" . $_POST[recipient]);
        }
        else
        {
            RequestUrl("https://" . $_SERVER['HTTP_HOST'] . "/page/" . $_SESSION['CurrentAccount_ID'] . "/step1?message=" . urlencode($_POST[message]) . "&team=true");
        }
        
        header("Location: /&success=Your page was sent successfully!");
        exit;
    }

    function common_register()
    {
        if (isset("warning") == false)
        {
            header("Location: " . option('base_uri') . "register&warning=Paige is currenty only open for testing. For commercial use, please contact our support team.");
            exit;
        }
        
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
        $result1 = mysql_query("SELECT * FROM account ORDER BY id");
        while($row1 = mysql_fetch_array($result1))
        {
            Security_Refresh($row1[id]);
            
            $now = AccountTime();
            
            $result2 = mysql_query("SELECT * FROM queue WHERE duedatetime <= '" . $now . "' AND accountid='" . $row1[id] . "'");
            while($row2 = mysql_fetch_array($result2))
            {
                RequestUrl($row2[url] . "&queue=true");
                mysql_query("DELETE FROM queue WHERE id='" . $row2[id] . "'");
            }
        }

        header("Location: /logout");
        exit;
    }

?>