<?php

	function schedule_view()
	{
		Security_Authorize();
		
		$month = isset($_GET[month]) ? $_GET[month] : date("m");
        $year = isset($_GET[year]) ? $_GET[year] : date("Y");
        
        $first_of_month = strtotime($year . "-" . $month . "-" . 1 . " 00:00:00");
        $working_date = strtotime("-" . date("w", $first_of_month) . " day", $first_of_month);
        
        $result = mysql_query("SELECT * FROM schedule WHERE shiftstart < '" . date("Y-m-d", $working_date) . " 00:00:00' AND environmentid='" . $_SESSION[CurrentEnvironment_ID] . "' AND shifttype='0' ORDER BY shiftstart DESC LIMIT 1");
        $last_primary = mysql_fetch_array($result);
        
        $result = mysql_query("SELECT * FROM schedule WHERE shiftstart < '" . date("Y-m-d", $working_date) . " 00:00:00' AND environmentid='" . $_SESSION[CurrentEnvironment_ID] . "' AND shifttype='1' ORDER BY shiftstart DESC LIMIT 1");
        $last_backup = mysql_fetch_array($result);
        
        $calendar = "";        
        for ($i = 0; $i < 6; $i++) // weeks
        {
            $calendar .= "<tr valign='top'>\n";
            
            for ($j = 0; $j < 7; $j++) // day of week
            {
                $calendar .= "<td class='calendar-day'>\n";
                date("m", $working_date) == $month ? $calendar .= "<div class='calendar-day-title'>\n" : $calendar .= "<div class='calendar-day-title calendar-day-title-off'>\n";
                
				if (date("d", $working_date) == date("d") &&
				    date("m", $working_date) == date("m") &&
					date("Y", $working_date) == date("Y"))
				{
					$calendar .= "<span style='color: red; font-weight: bold;'>" . date("j", $working_date) . "</span>\n";
                }
				else
				{
					$calendar .= date("j", $working_date) . "\n";
				}
				
				$calendar .= "</div>\n";
                
                $calendar .= "</td>\n";
                
                $working_date = strtotime("+1 day", $working_date);
            }
            
            $calendar .= "</tr>\n";
        }
        
        set("title", "Schedule");
        set("calendar", $calendar);
        set("calendar_title", date("F", $first_of_month) . " " . date("Y", $first_of_month));
        set("prev", "<a href='/schedule&month=" . (($month - 1) == 0 ? 12 : ($month - 1)) . "&year=" . (($month - 1) == 0 ? ($year - 1) : $year) . "'>&lt;&lt;&lt;</a>");
        set("next", "<a href='/schedule&month=" . (($month + 1) == 13 ? 1 : ($month + 1)) . "&year=" . (($month + 1) == 13 ? ($year + 1) : $year) . "'>&gt;&gt;&gt;</a>");
		
		return html("schedule/view.php");
	}

?>