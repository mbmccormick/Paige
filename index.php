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
    dispatch('/accounts', 'accounts_add');
    dispatch('/accounts/add', 'accounts_add');
    dispatch_post('/accounts/add', 'accounts_add_post');
    dispatch('/accounts/:id', 'accounts_edit');
    dispatch_post('/accounts/:id/edit', 'accounts_edit_post');
    dispatch('/accounts/:id/delete', 'accounts_delete');
	
	/* Declare Schedule routes */
    dispatch('/schedules', 'schedules_add');
    dispatch('/schedules/add', 'schedules_add');
    dispatch_post('/schedules/add', 'schedules_add_post');
    dispatch('/schedules/:id', 'schedules_edit');
    dispatch_post('/schedules/:id/edit', 'schedules_edit_post');
    dispatch('/schedules/:id/delete', 'schedules_delete');
	
	/* Declare History routes */
    dispatch('/history', 'history_add');
    dispatch('/history/add', 'history_add');
    dispatch_post('/history/add', 'history_add_post');
    dispatch('/history/:id', 'history_edit');
    dispatch_post('/history/:id/edit', 'history_edit_post');
    dispatch('/history/:id/delete', 'history_delete');
	
	/* Declare Stripe routes */
    dispatch('/stripe/hook', 'stripe_hook');
    
    run();
    
?>