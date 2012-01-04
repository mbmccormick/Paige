<?php

    function common_dashboard()
    {
        Security_Authorize();
        
        set("title", "Dashboard");
        return html("common/dashboard.php");
    }

?>