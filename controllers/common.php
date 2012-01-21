<?php

    function common_dashboard()
    {
        Security_Authorize();
        
        set("title", "Dashboard");
        return html("common/dashboard.php");
    }

    function common_dashboard_post()
    {
    	Security_Authorize();

    	RequestUrl("http://paigeapp.com/page/" . $_SESSION['CurrentAccount_ID'] . "/step1&message=" . urlencode($_POST[message]));
        
        header("Location: /&success=Your page was sent successfully!");
        exit;
    }

?>