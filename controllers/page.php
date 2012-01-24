<?php

    function page_step1()
    {
        Security_Refresh(params('accountid'));
        
        if (strlen($_GET[message]) > 120)
        {
            die("Message must be 120 characters or less.");
            exit;
        }

        if (isset($_GET[team]) == true)
        {
        	$result = mysql_query("SELECT * FROM member WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "'");
        	while($row = mysql_fetch_array($result))
        	{
        		$twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');

        		$message = $twilio->account->sms_messages->create(
					$_SESSION['CurrentAccount_PhoneNumber'],
					$row[phonenumber],
					$_GET[message]
				);
        	}

        	exit;
        }

        $now = AccountTime();

        // lookup the on-call member
        /*$result = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
        $shift = mysql_fetch_array($result);

        $result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
        $member = mysql_fetch_array($result);*/
		
		$result = mysql_query("SELECT * FROM member WHERE id='" . $_GET[memberid] . "'");
		$member = mysql_fetch_array($result);
        
        if (isset($_GET[queue]) == true)
        {
        	LogHistory($member[id], $_GET[message], 4);
        }

        // initialize twilio client
        $twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
        
		if (isset($_GET[attempt]) == true)
			$attempt = $_GET[attempt] + 1;
		else
			$attempt = 1;
			
        $call = $twilio->account->calls->create(
            $_SESSION['CurrentAccount_PhoneNumber'],
            $member[phonenumber],
            "https://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step2&attempt=" . $attempt . "&message=" . urlencode($_GET[message]) . "&memberid=" . urlencode($member[id]),
			array('IfMachine' => 'Continue')
        );
    }

    function page_step2()
    {
        Security_Refresh(params('accountid'));

        $now = AccountTime();

		if ($_GET[attempt] != 3)
		{
			if ($_POST[AnsweredBy] == "machine")
			{
				// lookup the on-call member
		        /*$result = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
		        $shift = mysql_fetch_array($result);

		        $result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
		        $member = mysql_fetch_array($result);*/
				
				$result = mysql_query("SELECT * FROM member WHERE id='" . $_GET[memberid] . "'");
				$member = mysql_fetch_array($result);

		        if ($member[isoptedin] == "1")
				{
					echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
					echo "<Response>\n";
					//echo "<Hangup />\n";
					echo "</Response>\n";

					// send text
					$twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
					
					$message = $twilio->account->sms_messages->create(
						$_SESSION['CurrentAccount_PhoneNumber'],
						$member[phonenumber], // Text this number
						$_GET[message] . " Reply \"confirm\" to confirm this page."
					);

					// add message to queue
					$now = AccountTime();
					
					$url = "https://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&attempt=" . $_GET[attempt] . "&message=" . urlencode($_GET[message]) . "&memberid=" . urlencode($member[id]);
					$duedatetime = date("Y-m-d H:i:s", strtotime('+10 minutes'));
					$createdtime = $now;
					
					$sql = "INSERT INTO queue (accountid, duedatetime, url, createddate) VALUES ('" . $_SESSION['CurrentAccount_ID'] . "', '" . $duedatetime . "', '" . $url . "', '" . $createdtime . "')";
					mysql_query($sql);

					mysql_query("UPDATE history SET status='2' WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 1");
				}
				else
				{
					echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
					echo "<Response>\n";
					echo "<Say voice='woman'>Hello, this is an automated page from " . $_SESSION['CurrentAccount_Name'] . ".</Say>\n";
					echo "<Say voice='woman'>" . $_GET[message] . "</Say>\n";
					echo "<Say voice='woman'>Please confirm this page as soon as possible. We will attempt to page you again in 10 minutes.</Say>\n";
					echo "</Response>\n";
					
					// add message to queue
					$now = AccountTime();
					
					$url = "https://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&attempt=" . $_GET[attempt] . "&message=" . urlencode($_GET[message]) . "&memberid=" . urlencode($_GET[memberid]);
					$duedatetime = date("Y-m-d H:i:s", strtotime('+15 minutes'));
					$createdtime = $now;
					
					$sql = "INSERT INTO queue (accountid, duedatetime, url, createddate) VALUES ('" . $_SESSION['CurrentAccount_ID'] . "', '" . $duedatetime . "', '" . $url . "', '" . $createdtime . "')";
					mysql_query($sql);

					mysql_query("UPDATE history SET status='2' WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 1");
				}
			}
			else 
			{
				echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
				echo "<Response>\n";
				echo "<Gather timeout='20' action='https://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step3&amp;attempt=" . urlencode($_GET[attempt]) . "&amp;message=" . urlencode($_GET[message]) . "&amp;memberid=" . urlencode($_GET[memberid]) . "' method='POST' numDigits='1'>\n";
				echo "<Say voice='woman'>Hello, this is an automated page from " . $_SESSION['CurrentAccount_Name'] . ".</Say>\n";
				echo "<Say voice='woman'>" . $_GET[message] . "</Say>\n";
				echo "<Say voice='woman'>Press one now to confirm that you have received this page.</Say>\n";
				echo "</Gather>\n";
				echo "</Response>\n";
			}
		}
		else
		{
			$result = mysql_query("SELECT * FROM member WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "'");
        	while($row = mysql_fetch_array($result))
        	{
        		$twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');

        		$message = $twilio->account->sms_messages->create(
					$_SESSION['CurrentAccount_PhoneNumber'],
					$row[phonenumber],
					$_GET[message] . " Reply \"confirm\" to confirm this page."
				);
        	}
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

			mysql_query("UPDATE history SET status='1' WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 1");
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
				$now = AccountTime();
				
				$url = "https://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&attempt=" . $_GET[attempt] . "&message=" . urlencode($_GET[message]) . "&memberid=" . urlencode($member[id]);
				$duedatetime = date("Y-m-d H:i:s", strtotime('+15 minutes'));
				$createdtime = $now;
				
				$sql = "INSERT INTO queue (duedatetime, url, createddate) VALUES ('" . $duedatetime . "', '" . $url . "', '" . $createdtime . "')";
				mysql_query($sql);

				mysql_query("UPDATE history SET status='2' WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 1");
				
				// lookup the on-call member
				/*$onCall = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
				$shift = mysql_fetch_array($onCall);
				
				$result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
				$member = mysql_fetch_array($result);*/
				
				$result = mysql_query("SELECT * FROM member WHERE id='" . $_GET[memberid] . "'");
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
	
	/**
	 * Haven't updated this yet!
	 **/
    function page_hook()
    {
        $result = mysql_query("SELECT * FROM account WHERE hash='" . mysql_real_escape_string(params('hash')) . "'");
        $account = mysql_fetch_array($result);

        Security_Refresh($account[id]);

        $now = AccountTime();
		
		// lookup the on-call member
        $result = mysql_query("SELECT * FROM schedule WHERE startdate <= '" . $now . "' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate DESC");
        $shift = mysql_fetch_array($result);

        $result = mysql_query("SELECT * FROM member WHERE id='" . $shift[memberid] . "'");
        $member = mysql_fetch_array($result);

        LogHistory($member[id], $_GET[message], 3);

        RequestUrl("https://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&message=" . urlencode($_GET[message]));
    }

?>