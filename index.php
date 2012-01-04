<?php

    require_once "library/limonade.php";
    require_once "library/lightopenid/openid.php";
    
    require("config/config.php");
    require("library/utils.php");
    require("library/security.php");
    
    /* Establish database connection */
    $con = mysql_connect(Server, Username, Password);
    mysql_select_db(Database, $con);
    
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
    dispatch('/login/openid/google', 'login_openid_google');
    dispatch_post('/login/openid/google', 'login_openid_google_post');
    dispatch('/login/openid/remove', 'login_openid_remove');
    dispatch('/logout', 'logout');
    
    /* Declare Users routes */
    dispatch('/users', 'users_list');
    dispatch('/users/add', 'users_add');
    dispatch_post('/users/add', 'users_add_post');
    dispatch('/users/:id', 'users_edit');
    dispatch_post('/users/:id/edit', 'users_edit_post');
    dispatch('/users/:id/delete', 'users_delete');
    
    run();
    
?>