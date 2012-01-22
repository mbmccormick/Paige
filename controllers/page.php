<?php

    function page_step1()
    {
        Security_Refresh(params('accountid'));
        
        if (strlen($_GET[message]) > 120)
        {
            die("Message must be 120 characters or less.");
            exit;
        }

        $now = date("Y-m-d H:i:s");

        // lookup the on-call member
        $result = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
        $shift = mysql_fetch_array($result);

        $result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
        $member = mysql_fetch_array($result);

        LogHistory($member[id], $_GET[message], 0);
        
        // initialize twilio client
        $twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
        
		if (isset($_GET[attempt]) == true)
			$attempt = $_GET[attempt] + 1;
		else
			$attempt = 1;
			
        $call = $twilio->account->calls->create(
            $_SESSION['CurrentAccount_PhoneNumber'],
            $member[phonenumber],
            "http://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step2&attempt=" . $attempt . "&message=" . urlencode($_GET[message]),
			array('IfMachine' => 'Continue')
        );
    }

    function page_step2()
    {
        Security_Refresh(params('accountid'));

        $now = date("Y-m-d H:i:s");

		if ($_GET[attempt] != 3)
		{
			if ($_POST[AnsweredBy] == "machine")
			{
				// lookup the on-call member
		        $result = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
		        $shift = mysql_fetch_array($result);

		        $result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
		        $member = mysql_fetch_array($result);

		        if ($member[isoptedin] == "1")
				{
					echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
					echo "<Response>\n";
					echo "<Hangup />\n";
					echo "</Response>\n";

					// send text
					$twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
					
					$message = $twilio->account->sms_messages->create(
						$_SESSION['CurrentAccount_PhoneNumber'],
						$member[phonenumber], // Text this number
						$_GET[message] . " Reply \"confirm\" to confirm this page."
					);

					// add message to queue
					$now = date("Y-m-d H:i:s");
					
					$url = "http://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&attempt=" . $_GET[attempt] . "&message=" . urlencode($_GET[message]);
					$duedatetime = date("Y-m-d H:i:s", strtotime('+15 minutes'));
					$createdtime = $now;
					
					$sql = "INSERT INTO queue (accountid, duedatetime, url, createddate) VALUES ('" . $_SESSION['CurrentAccount_ID'] . "', '" . $duedatetime . "', '" . $url . "', '" . $createdtime . "')";
					mysql_query($sql);
				}
				else
				{
					echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
					echo "<Response>\n";
					echo "<Say voice='woman'>Hello, this is an automated page from " . $_SESSION['CurrentAccount_Name'] . ".</Say>\n";
					echo "<Say voice='woman'>" . $_GET[message] . "</Say>\n";
					echo "<Say voice='woman'>Since your page was not confirmed, we will try again in fifteen minutes.</Say>\n";
					echo "</Response>\n";
					
					// add message to queue
					$now = date("Y-m-d H:i:s");
					
					$url = "http://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&attempt=" . $_GET[attempt] . "&message=" . urlencode($_GET[message]);
					$duedatetime = date("Y-m-d H:i:s", strtotime('+15 minutes'));
					$createdtime = $now;
					
					$sql = "INSERT INTO queue (accountid, duedatetime, url, createddate) VALUES ('" . $_SESSION['CurrentAccount_ID'] . "', '" . $duedatetime . "', '" . $url . "', '" . $createdtime . "')";
					mysql_query($sql);
				}
			}
			else 
			{
				echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
				echo "<Response>\n";
				echo "<Gather timeout='20' action='http://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step3&amp;attempt=" . urlencode($_GET[attempt]) . "&amp;message=" . urlencode($_GET[message]) . "' method='POST' numDigits='1'>\n";
				echo "<Say voice='woman'>Hello, this is an automated page from " . $_SESSION['CurrentAccount_Name'] . ".</Say>\n";
				echo "<Say voice='woman'>" . $_GET[message] . "</Say>\n";
				echo "<Say voice='woman'>Press one now to confirm that you have received this message.</Say>\n";
				echo "</Gather>\n";
				echo "</Response>\n";
			}
		}
		else
		{
			// text the team
		}
    }

	function page_step3()
	{
		Security_Refresh(params('accountid'));
		
		if ($_POST[Digits] == '1') 
		{
			echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
			echo "<Response>\n";
			echo "<Say voice='woman'>Your page has been confirmed. Thank you.</Say>\n";
			echo "</Response>\n";
		}
		else 
		{
			if ($_GET[attempt] == 1 || $_GET[attempt] == 2) 
			{
				echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
				echo "<Response>\n";
				echo "<Say voice='woman'>Since your page was not confirmed, we will try again in fifteen minutes.</Say>\n";
				echo "</Response>\n";
				
				// add message to queue
				$now = date("Y-m-d H:i:s");
				
				$url = "http://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&attempt=" . $_GET[attempt] . "&message=" . urlencode($_GET[message]);
				$duedatetime = date("Y-m-d H:i:s", strtotime('+15 minutes'));
				$createdtime = $now;
				
				$sql = "INSERT INTO queue (duedatetime, url, createddate) VALUES ('" . $duedatetime . "', '" . $url . "', '" . $createdtime . "')";
				mysql_query($sql);
				
				// if opted in, send a text
				
				// lookup the on-call member
				$onCall = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
				$shift = mysql_fetch_array($onCall);
				
				$result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
				$member = mysql_fetch_array($result);
				
				if ($member[isoptedin] == 1) 
				{
					// send text
					$twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
					
					$message = $twilio->account->sms_messages->create(
					  $_SESSION['CurrentAccount_PhoneNumber'],
					  $member[phonenumber], // Text this number
					  $_GET[message] . " Reply \"confirm\" to confirm this page."
					);
				}
				else
				{
					// exit
				}
			}
			else 
			{
				echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
				echo "<Response>\n";
				echo "<Say voice='woman'>You should never hear this.</Say>\n";
				echo "</Response>\n";
			}
			
			// leave a voicemail
		}
	}
	
    function page_hook()
    {
        $result = mysql_query("SELECT * FROM account WHERE hash='" . mysql_real_escape_string(params('hash')) . "'");
        $account = mysql_fetch_array($result);

        Security_Refresh($account[id]);

        RequestUrl("http://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&message=" . urlencode($_GET[message]));
    }

?>