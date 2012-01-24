<?php
    $i = 1;
    
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

            $oncall = $member[name];
            
            $i = 2;
            $input .= "<input type=\"hidden\" name=\"team1\" value=\"" . $member[id] . "\">";
            
            // get the other team members
            $teamQuery = mysql_query("SELECT * FROM member WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' and id!='" . $member[id] . "'");
            while($team = mysql_fetch_array($teamQuery))
            {
                $options .= "<option value=\"" . $i . "\">" . $team[name] . "</option>";
                $inc = "team" . $i;
                $input .= "<input type=\"hidden\" name=\"" . $inc . "\" value=\"" . $team[id] . "\">";  
                //$options .= "<option value=\"" . $i . "\">" . $ids[$i] . "</option>";
                $i++;
            }

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
                    $history .= "<b><a href='" . option('base_uri') . "members/$member[id]'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via unknown</b><br />\n";
                elseif ($row[medium] == 1)
                    $history .= "<b><a href='" . option('base_uri') . "members/$member[id]'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via web</b><br />\n";
                elseif ($row[medium] == 2)
                    $history .= "<b><a href='" . option('base_uri') . "members/$member[id]'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via telephone</b><br />\n";
                elseif ($row[medium] == 3)
                    $history .= "<b><a href='" . option('base_uri') . "members/$member[id]'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via hook</b><br />\n";
                elseif ($row[medium] == 4)
                    $history .= "<b><a href='" . option('base_uri') . "members/$member[id]'>" . $member[name] . "</a> was paged " . FriendlyDate(1, strtotime($row[createddate])) . " via scheduler</b><br />\n";
                $history .= $row[message] . "<br />\n";
                $history .= "</div></div><br />\n";
            }

            set("title", "Dashboard");
            set("oncall", $oncall);
            set("history", $history);
            set("i", $i);
            set("options", $options);
            set("input", $input);
            return html("common/dashboard.php");
        }
    }

    function common_dashboard_post()
    {
        Security_Authorize();
        
        if ($_POST[recipient] != $_POST[i])
        {
            $now = AccountTime();
        
            // lookup the on-call member
            /*$result = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
            $shift = mysql_fetch_array($result);

            $result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
            $member = mysql_fetch_array($result);*/
            
            //$result = mysql_query("SELECT * FROM member WHERE id='" . $ids[$i] . "'");
            //$member = mysql_fetch_array($result);

            $idVal = "team" . $_POST[recipient]; 
            LogHistory($_POST[$idVal], $_POST[message], 1);
            
            RequestUrl("https://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&message=" . urlencode($_POST[message]) . "&memberid=" . urlencode($_POST[$idVal]));
        }
        else
        {
            RequestUrl("https://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&team=true&message=" . urlencode($_POST[message]));
        }
        
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
    }

?>