<?php

	function history_list()
    {
        Security_Authorize();

        if (isset($_GET[page]) == true)
            $page = $_GET[page] - 1;
        else
            $page = 0;
        
		$index = ($page * 25) + 1;

        $result = mysql_query("SELECT * FROM history WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC LIMIT " . ($page * 25) . ", 25");

		while($row = mysql_fetch_array($result)) 
		{
			$body .= "<tr>\n";
       
            $body .= "<th style='vertical-align: top;'>\n";
            $body .= $index;
            $body .= "</th>\n";
            $body .= "<td style='text-align: center;'>\n";
            if ($row[status] == 0)
                $body .= "<img src='/public/img/0.png' title='This page is in progress.' />";
            elseif ($row[status] == 1)
                $body .= "<img src='/public/img/1.png' title='This page was confirmed.' />";
            elseif ($row[status] == 2)
                $body .= "<img src='/public/img/2.png' title='This page was not confirmed.' />";
            $body .= "</td>\n";
            $body .= "<td>\n";		
			$name = mysql_query("SELECT name FROM member WHERE id=" . $row[memberid]);
			$name = mysql_fetch_array($name);
            $body .= $name[name];
            $body .= "</td>\n";
            $body .= "<td>\n";
			$body .= $row[message];
            $body .= "</td>\n";
            $body .= "<td>\n";
            if ($row[medium] == 0)
                $body .= "Unknown";
            elseif ($row[medium] == 1)
                $body .= "Web";
            elseif ($row[medium] == 2)
                $body .= "Telephone";
            elseif ($row[medium] == 3)
                $body .= "URL Hook";
            elseif ($row[medium] == 4)
                $body .= "Scheduler";
            $body .= "</td>\n";
			$body .= "<td>\n";
			$body .= date('F d \a\t g:ia', strtotime($row[createddate]));
            $body .= "</td>\n";			
            $body .= "</tr>\n";

            $index++;
		}
		
		if (mysql_num_rows($result) == 0)
        {
            $body .= "<tr>\n";
            $body .= "<td colspan='6'>There has not been any pages sent from this account.</td>\n";
            $body .= "</tr>\n";
        }

        $result = mysql_query("SELECT * FROM history WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY createddate DESC");
        $count = mysql_num_rows($result);

        if ($page > 0)
            $pagination .= "<li class='prev'><a href='" . option('base_uri') . "history?page=" . $page . "'>&larr; Previous</a></li>";
        else
            $pagination .= "<li class='prev disabled'><a href='#'>&larr; Previous</a></li>";

        for ($i = 1; $i < ($count / 25) + 1; $i++)
        {
            if (($page + 1) == $i)
                $pagination .= "<li class='active'><a href='" . option('base_uri') . "history?page=" . $i . "'>" . $i . "</a></li>";
            else
                $pagination .= "<li><a href='" . option('base_uri') . "history?page=" . $i . "'>" . $i . "</a></li>";
        }

        if ((($page + 1) * 25) < $count)
            $pagination .= "<li class='next'><a href='" . option('base_uri') . "history?page=" . ($page + 2) . "'>Next &rarr;</a></li>";
        else
            $pagination .= "<li class='next disabled'><a href='#'>Next &rarr;</a></li>";
        
        set("title", "History");
        set("body", $body);
        set("pagination", $pagination);
        return html("history/list.php");
    }

?>