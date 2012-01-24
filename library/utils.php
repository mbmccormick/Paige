<?php

    function LogHistory($memberid, $message, $medium)
    {
        $now = date("Y-m-d H:i:s");
        
        $sql = "INSERT INTO history (accountid, memberid, message, medium, status, createddate) VALUES
                    ('" . mysql_real_escape_string($_SESSION['CurrentAccount_ID']) . "', '" . mysql_real_escape_string($memberid) . "', '" . mysql_real_escape_string($message) . "', '" . mysql_real_escape_string($medium) . "', '0', '" . $now . "')";
        mysql_query($sql);
    }

    function FriendlyDate($levels = 2, $date1)
    { 
        $blocks = array( 
            array('name'=>'year','amount' => 60*60*24*365), 
            array('name'=>'month','amount' => 60*60*24*31), 
            array('name'=>'week','amount' => 60*60*24*7), 
            array('name'=>'day','amount' => 60*60*24), 
            array('name'=>'hour','amount' => 60*60), 
            array('name'=>'minute','amount' => 60), 
            array('name'=>'second','amount' => 1) 
        ); 
        
        $date2 = time();
        $diff = abs($date1-$date2); 
        
        $current_level = 1; 
        $result = array(); 
        foreach($blocks as $block) 
        { 
            if ($current_level > $levels) { break; } 
            if ($diff/$block['amount'] >= 1) 
            { 
                $amount = floor($diff/$block['amount']); 
                if ($amount>1) { $plural='s'; } else { $plural=''; } 
                $result[] = $amount.' '.$block['name'].$plural; 
                $diff -= $amount*$block['amount']; 
                $current_level++; 
            } 
        } 
         
        if (strpos(implode(' ', $result), "second") !== false ||
            strpos(implode(' ', $result), "seconds") !== false ||
            implode(' ', $result) == "1 minute" ||
            implode(' ', $result) == "2 minutes")
        {
            return "just now"; 
        }
        else
        {
            return implode(' ',$result) . " ago"; 
        }
    }
    
    function FriendlyString($string, $length = 100)
    {
        if (strlen($string) > $length)
            return substr($string, 0, ($length - 3)) . "...";
        else
            return $string;
    }
    
    function StartsWith($haystack, $needle, $case=true)
    {
        if ($case) 
        {
            return (strcmp(substr($haystack, 0, strlen($needle)), $needle)===0);
        }
        return (strcasecmp(substr($haystack, 0, strlen($needle)), $needle)===0);
    }
    
    function RequestUrl($url)
    {
        $ch = curl_init();        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        $data = curl_exec($ch);
        curl_close($ch);
        
        return $data;
    }
    
?>