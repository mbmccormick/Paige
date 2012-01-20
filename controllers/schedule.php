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
            if ($i > 4 && date("m", $working_date) != $month) break;
            
            $calendar .= "<tr valign='top'>\n";
            
            for ($j = 0; $j < 7; $j++) // day of week
            {
                if (date("d", $working_date) == date("d") &&
                    date("m", $working_date) == date("m") &&
                    date("Y", $working_date) == date("Y"))
                {
                    $calendar .= "<td class='calendar-day today'>\n";
                }
                else
                {
                    $calendar .= "<td class='calendar-day'>\n";
                }
                
                date("m", $working_date) == $month ? $calendar .= "<div class='calendar-day-title'>\n" : $calendar .= "<div class='calendar-day-title calendar-day-title-off'>\n";
                $calendar .= date("j", $working_date) . "\n";
                $calendar .= "</div>\n";

                $result = mysql_query("SELECT * FROM schedule WHERE startdate >= '" . date("Y-m-d", $working_date) . " 00:00:00' AND startdate < '" . date("Y-m-d", $working_date) . " 23:59:59' AND accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY startdate ASC, type ASC");                
                while($row = mysql_fetch_array($result))
                {
                    $sql = mysql_query("SELECT * FROM member WHERE id='" . $row[memberid] . "'");
                    $member = mysql_fetch_array($sql);
                        
                    $startdate = strtotime($row[startdate]);
                        
                    $calendar .= "<div class='calendar-day-event'>\n";
                    $calendar .= "<a href='/schedule/" . $row[id] . "/edit'><b>" . substr((date("i", $startdate) == "00" ? date("ga", $startdate) : date("g:ia", $startdate)), 0, -1) . "</b> " . $member[name] . "</a>";
                    $calendar .= "</div>\n";
                }
                
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
    
    function schedule_add()
    {
        Security_Authorize();
    
        $members = "<option selected='true' value=''></option>\n";
    
        $result = mysql_query("SELECT * FROM member WHERE accountid='" . $_SESSION['CurrentAccount_ID'] . "' ORDER BY name ASC");
        while($row = mysql_fetch_array($result))
        {
            $members .= "<option value='" . $row[id] . "'>" . $row[name] . "</option>\n";
        }
    
        set("title", "New Shift");
        set("members", $members);
        return html("schedule/add.php");
    }
    
    function schedule_add_post()
    {
        Security_Authorize();
        
        $now = date("Y-m-d H:i:s");
        
        $sql = "INSERT INTO schedule (accountid, memberid, startdate, type, createddate) VALUES ('" . mysql_real_escape_string($_SESSION['CurrentAccount_ID']) . "', '" . mysql_real_escape_string($_POST[memberid]) . "', '" . mysql_real_escape_string(date("Y-m-d", strtotime($_POST[startdate])) . " " . date("H:i:s", strtotime($_POST[starttime]))) . "', '0', '" . $now . "')";
        mysql_query($sql);
        
        header("Location: /schedule&month=" . date("m", strtotime($_POST[startdate])) . "&year=" . date("Y", strtotime($_POST[startdate])) . "&success=Your shift was created successfully!");
        exit;
    }
    
    function schedule_edit()
    {
        Security_Authorize(5);
        
        $now = date("Y-m-d H:i:s");
        
        $result = mysql_query("SELECT * FROM schedule WHERE id='" . params('id') . "'");
        $schedule = mysql_fetch_array($result);
        
        $doctors_list = "<option selected='true' value=''></option>\n";
        
        $result = mysql_query("SELECT * FROM environment_user, user WHERE environmentid='" . $_SESSION[CurrentEnvironment_ID] . "' AND userid=user.id AND user.roletype=2 ORDER BY user.name ASC");
        while($row = mysql_fetch_array($result))
        {
            if ($row[id] == $schedule[userid])
                $doctors_list .= "<option value='" . $row[id] . "' selected='true'>" . $row[name] . "</option>\n";
            else
                $doctors_list .= "<option value='" . $row[id] . "'>" . $row[name] . "</option>\n";
        }
        
        if ($schedule[shifttype] == 0)
            $types_list = "<option value='0' selected='true'>Primary Doctor</option>\n";
        else
            $types_list = "<option value='0'>Primary Doctor</option>\n";
        if ($schedule[shifttype] == 1)
            $types_list .= "<option value='1' selected='true'>Backup Doctor</option>\n";
        else
            $types_list .= "<option value='1'>Backup Doctor</option>\n";
        
        if ($schedule != null)
        {
            set("title", "Edit Schedule");
            set("schedule", $schedule);
            set("doctors", $doctors_list);
            set("types", $types_list);
            return html("schedule/edit.php");
        }
        else
        {
            set("title", "Schedule Not Found");
            set("type", "schedule");
            return html("common/notfound.php");
        }
    }
    
    function schedule_edit_post()
    {
        Security_Authorize(5);
        
        $now = date("Y-m-d H:i:s");
        
        if (date("Y-m-d", strtotime($_POST[shiftstartdate])) . " " . date("H:i:s", strtotime($_POST[shiftstarttime])) < $now)
        {
            header("Location: " . option('base_uri') . "schedule/" . params('id') . "/edit&error=Shift start date must be in the future!");
            exit;
        }
        
        $sql = "UPDATE schedule SET userid='" . mysql_real_escape_string($_POST[userid]) . "', shiftstart='" . date("Y-m-d", strtotime($_POST[shiftstartdate])) . " " . date("H:i:s", strtotime($_POST[shiftstarttime])) . "', shifttype='" . $_POST[shifttype] . "' WHERE id='" . params('id') . "'";
        mysql_query($sql);
        
        header("Location: " . option('base_uri') . "schedule&success=Your shift was updated successfully!");
        exit;
    }
    
    function schedule_delete()
    {
        Security_Authorize();
        
        $sql = "DELETE FROM schedule WHERE id='" . params('id') . "'";    
        mysql_query($sql);

        header("Location: " . option('base_uri') . "schedule");
        exit;
    }

?>