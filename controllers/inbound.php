<?php

	function inbound_voice()
	{
		Security_Refresh(params('accountid'));
	}

	function inbound_sms()
	{
		Security_Refresh(params('accountid'));

		if ($_POST[Body] == "confirm")
		{
			mysql_query("DELETE FROM queue WHERE accountid='" . $account[id] . "' LIMIT 1 ORDER BY createddate DESC");

			$twilio = new Services_Twilio('AC5057e5ab36685604eecc9b1fdd8528e2', '309e6930d27b624bbfaa45dac382c6ae');
					
			$message = $twilio->account->sms_messages->create(
				$_SESSION['CurrentAccount_PhoneNumber'],
				$_POST[From],
				"Thank you, your page has been confirmed."
			);
		}
	}

?>