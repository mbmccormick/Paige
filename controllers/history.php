<?php

	function history_list()
    {
        Security_Authorize();

		// gets all tuples from history table
        $result = mysql_query("SELECT * FROM history WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC");

		// iterates through result
		while($row = mysql_fetch_array($result)) 
		{
			$body .= "<tr>\n";
       
            $body .= "<td>\n";		
			$name = mysql_query("SELECT name FROM member WHERE id=" . $row[memberid]);
			$name = mysql_fetch_array($name);
            $body .= $name[name];
            $body .= "</td>\n";
            $body .= "<td>\n";
			$body .= $row[message];
            $body .= "</td>\n";
			$body .= "<td>\n";
			$body .= date('F d, Y \a\t g:ia', strtotime($row[createddate]));
            $body .= "</td>\n";
            $body .= "<td>\n";
					
			if ($row[medium] == 0) 
				$body .= "Web";
			else if ($row[medium] == 1)
				$body .= "Telephone";
			else if ($row[medium] == 2)
				$body .= "Hook";

            $body .= "</td>\n";
			
            $body .= "</tr>\n";
		}
		
		if (mysql_num_rows($result) == 0)
        {
            $body .= "<tr>\n";
            $body .= "<td colspan='4'>There has not been any pages sent from this account.</td>\n";
            $body .= "</tr>\n";
        }
        
        set("title", "History");
        set("body", $body);
        return html("history/list.php");
    }

?>