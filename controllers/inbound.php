<?php

    function inbound_voice()
    {
        Security_Refresh(params('accountid'));

        echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
        echo "<Response>\n";
        echo "<Gather timeout='20' action='https://" . $_SERVER['HTTP_HOST'] . "/inbound/" . $_SESSION['CurrentAccount_ID'] . "/voice/menu' method='POST' numDigits='4'>\n";
        echo "<Say voice='woman'>You have reached the " . $_SESSION['CurrentAccount_Name'] . " paging service.</Say>\n";
        echo "<Say voice='woman'>Please enter your account PIN to continue.</Say>\n";
        echo "</Gather>\n";
        echo "</Response>\n";
    }

    function inbound_voice_menu()
    {
        Security_Refresh(params('accountid'));

        $result = mysql_query("SELECT * FROM account WHERE id='" . mysql_real_escape_string($_SESSION['CurrentAccount_ID']) . "'");
        $account = mysql_fetch_array($result);

        $customer = Stripe_Customer::retrieve($account[stripeid]);
        
        if ($_POST[Digits] == $customer->active_card->last4)
        {
            echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
            echo "<Response>\n";
            echo "<Gather timeout='20' action='https://" . $_SERVER['HTTP_HOST'] . "/inbound/" . $_SESSION['CurrentAccount_ID'] . "/voice/confirm' method='POST' numDigits='1'>\n";
            echo "<Say voice='woman'>To page your on-call team member now, please press one.</Say>\n";
            echo "<Say voice='woman'>To confirm the most recent page, please press two.</Say>\n";
            echo "</Gather>\n";
            echo "</Response>\n";
        }
        else
        {
            echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
            echo "<Response>\n";
            echo "<Say voice='woman'>The PIN that you entered did not match our records. Please try your call again later.</Say>\n";
            echo "</Response>\n";
        }
    }

    function inbound_voice_confirm()
    {
        Security_Refresh(params('accountid'));
        
        if ($_POST[Digits] == "1")
        {
            echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
            echo "<Response>\n";
            echo "<Say voice='woman'>Thank you, your page has been sent.</Say>\n";
            echo "</Response>\n";

            $now = AccountTime();
        
            // lookup the on-call member
            $result = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
            $shift = mysql_fetch_array($result);

            $result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
            $member = mysql_fetch_array($result);

            LogHistory($member[id], $_GET[message], 2);

            RequestUrl("https://" . $_SERVER['HTTP_HOST'] . "/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&memberid=" . $member[id]);
        }
        elseif ($_POST[Digits] == "2")
        {
            echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
            echo "<Response>\n";
            echo "<Say voice='woman'>Thank you, the most recent page has been confirmed.</Say>\n";
            echo "</Response>\n";

            mysql_query("DELETE FROM queue WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 1");

            mysql_query("UPDATE history SET status='1' WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 1");
        }
    }

    function inbound_sms()
    {
        Security_Refresh(params('accountid'));

        if (trim(strtolower($_POST[Body])) == "confirm")
        {
            $twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
                    
            $message = $twilio->account->sms_messages->create(
                $_SESSION['CurrentAccount_PhoneNumber'],
                $_POST[From],
                "Thank you, your page has been confirmed."
            );

            mysql_query("DELETE FROM queue WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 1");

            mysql_query("UPDATE history SET status='1' WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 1");
        }
    }

?>