<?php

    require_once "library/limonade.php";
    require_once "library/lightopenid/openid.php";
	require_once "library/stripe/Stripe.php";
	require_once "library/twilio/Twilio.php";
    
    require("config/config.php");
    require("library/utils.php");
    require("library/security.php");
    
    /* Establish database connection */
    $con = mysql_connect(Server, Username, Password);
    mysql_select_db(Database, $con);
	
	/* Connect to Stripe */
	Stripe::setApiKey("xyNE8eAHEtrXJEbjIAt7hVzVRsaCyxfn");
	
    /* Modify configuration settings */
    function configure()
    {
        option('base_uri', '/');
        option('public_dir', 'public/');
        option('views_dir', 'views/');
        option('controllers_dir', 'controllers/');
    }
    
    /* Declare default layout page */
    function before()
    {
        layout('layout.php');
    }
    
    /* Declare default error page */
    function server_error($errno, $errstr, $errfile=null, $errline=null)
    {
        $args = compact('errno', 'errstr', 'errfile', 'errline');   
        return html("error/error.php", "layout.php", $args);
    }
    
    /* Declare Common routes */
    dispatch('/', 'common_dashboard');
    
    /* Declare Security routes */
    dispatch('/login', 'login');
    dispatch_post('/login', 'login_post');
    dispatch('/login/reset', 'login_reset');
    dispatch_post('/login/reset', 'login_reset_post');
    dispatch('/logout', 'logout');
    
	/* Declare Accounts routes */
    dispatch('/accounts', 'accounts_add');
    dispatch('/accounts/add', 'accounts_add');
    dispatch_post('/accounts/add', 'accounts_add_post');
    dispatch('/accounts/:id', 'accounts_edit');
    dispatch_post('/accounts/:id/edit', 'accounts_edit_post');
    dispatch('/accounts/:id/delete', 'accounts_delete');
	
	/* Declare Members routes */
    dispatch('/members', 'members_list');
    dispatch('/members/add', 'members_add');
    dispatch_post('/members/add', 'members_add_post');
    dispatch('/members/:id', 'members_edit');
    dispatch_post('/members/:id/edit', 'members_edit_post');
    dispatch('/members/:id/delete', 'members_delete');
	
	/* Declare Schedule routes */
    dispatch('/schedule', 'schedule_view');
    dispatch('/schedule/add', 'schedule_add');
    dispatch_post('/schedule/add', 'schedule_add_post');
    dispatch('/schedule/:id', 'schedule_edit');
    dispatch_post('/schedule/:id/edit', 'schedule_edit_post');
    dispatch('/schedule/:id/delete', 'schedule_delete');
	
	/* Declare History routes */
    dispatch('/history', 'history_list');
    dispatch('/history/add', 'history_add');
    dispatch_post('/history/add', 'history_add_post');
    dispatch('/history/:id', 'history_edit');
    dispatch_post('/history/:id/edit', 'history_edit_post');
    dispatch('/history/:id/delete', 'history_delete');

    /* Declare Page routes */
    dispatch('/page/:accountid', 'page_step1');
    dispatch('/page/:accountid/step1', 'page_step1');
    dispatch('/page/:accountid/step2', 'page_step2');
	
	/* Declare Stripe routes */
    dispatch('/stripe/hook', 'stripe_hook');
    
    run();
    
?>