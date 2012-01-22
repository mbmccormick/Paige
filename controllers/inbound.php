<?php

	function inbound_voice()
	{
		Security_Refresh(params('accountid'));

		echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
		echo "<Response>\n";
		echo "<Gather timeout='20' action='http://paigeapp.com/inbound/" . $_SESSION['CurrentAccount_ID'] . "/voice/menu' method='POST' numDigits='4'>\n";
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
			echo "<Gather timeout='20' action='http://paigeapp.com/inbound/" . $_SESSION['CurrentAccount_ID'] . "/voice/confirm' method='POST' numDigits='1'>\n";
			echo "<Say voice='woman'>To confirm your page, please press one.</Say>\n";
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
			echo "<Say voice='woman'>Thank you, your page has been confirmed.</Say>\n";
			echo "</Response>\n";

			mysql_query("DELETE FROM queue WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 1");
		}
	}

	function inbound_sms()
	{
		Security_Refresh(params('accountid'));

		if ($_POST[Body] == "confirm")
		{
			mysql_query("DELETE FROM queue WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT 1");

			$twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
					
			$message = $twilio->account->sms_messages->create(
				$_SESSION['CurrentAccount_PhoneNumber'],
				$_POST[From],
				"Thank you, your page has been confirmed."
			);
		}
	}

?>