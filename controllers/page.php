<?php

    function page_step1()
    {
        Security_Refresh(params('accountid'));
        
        $now = date("Y-m-d H:i:s");

        // lookup the on-call member
        $result = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurentAccount_ID'] . "' ORDER BY startdate DESC");
        $shift = mysql_fetch_array($result);

        $result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
        $member = mysql_fetch_array($result);
        
        // initialize twilio client
        $twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
        
        $call = $client->account->calls->create(
            $_SESSION['CurrentAccount_PhoneNumber'],
            $member[phonenumber],
            "http://paigeapp.com/page/" . $_SESSION['CurentAccount_ID'] . "/step2&message=" . $_GET[message]
        );
    }

    function page_step2()
    {
        Security_Refresh(params('accountid'));

        echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
        echo "<Response>\n";
        echo "<Gather timeout='5' action='http://paigeapp.com/page/" . $_SESSION['CurentAccount_ID'] . "/step3' method='POST' numDigits='1'>\n";
        echo "<Say voice='woman'>Hello, this is an automated page from " . $_SESSION['CurentAccount_Name'] . ".</Say>\n";
        echo "<Say voice='woman'>" . $_GET[message] . "</Say>\n";
        echo "<Say voice='woman'>Press one now to confirm that you have received this message.</Say>\n";
        echo "</Gather>\n";
        echo "</Response>\n";
    }

?>