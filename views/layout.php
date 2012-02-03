<!DOCTYPE html> 
<html lang="en">
<head> 
    <meta charset="utf-8" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?=ApplicationName?> - <?=$title?></title> 
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/bootstrap.css" />
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/layout.css" />
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/bootstrap.responsive.css" />
    <link rel="shortcut icon" type="image/x-icon" href="<?=option('base_uri')?>public/img/logo.ico">
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/jquery.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/calendrical/jquery.calendrical.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/maskedinput/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/common.js"></script>    
</script>
</head> 
<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="<?=option('base_uri')?>"><?=ApplicationName?></a>
                <div class="nav-collapse">
                    <?php if ($_SESSION['CurrentAccount_ID'] == null) { ?>
                    <ul class="nav">
                        <?php if ($_SERVER['REQUEST_URI'] == option('base_uri') || $_SERVER['REQUEST_URI'] == "") { ?>
                        <li class="active"><a href="<?=option('base_uri')?>">Home</a></li>
                        <?php } else { ?>
                        <li><a href="<?=option('base_uri')?>">Home</a></li>
                        <?php } ?>
                        <?php if (strpos($_SERVER['REQUEST_URI'], option('base_uri') . "about") === 0) { ?>
                        <li class="active"><a href="<?=option('base_uri')?>about">About</a></li>
                        <?php } else { ?>
                        <li><a href="<?=option('base_uri')?>about">About</a></li>
                        <?php } ?>
                        <?php if (strpos($_SERVER['REQUEST_URI'], option('base_uri') . "register") === 0) { ?>
                        <li class="active"><a href="<?=option('base_uri')?>register">Register</a></li>
                        <?php } else { ?>
                        <li><a href="<?=option('base_uri')?>register">Register</a></li>
                        <?php } ?>
                    </ul>
                    <ul class="nav pull-right">
                        <?php if (strpos($_SERVER['REQUEST_URI'], option('base_uri') . "login") === 0) { ?>
                        <li class="active"><a href="<?=option('base_uri')?>login">Login</a></li>
                        <?php } else { ?>
                        <li><a href="<?=option('base_uri')?>login">Login</a></li>
                        <?php } ?>
                    </ul>
                    <?php } else { ?>
                    <ul class="nav">
                        <?php if ($_SERVER['REQUEST_URI'] == option('base_uri')) { ?>
                        <li class="active"><a href="<?=option('base_uri')?>">Dashboard</a></li>
                        <?php } else { ?>
                        <li><a href="<?=option('base_uri')?>">Dashboard</a></li>
                        <?php } ?>
                        <?php if (strpos($_SERVER['REQUEST_URI'], option('base_uri') . "schedule") === 0) { ?>
                        <li class="active"><a href="<?=option('base_uri')?>schedule">Schedule</a></li>
                        <?php } else { ?>
                        <li><a href="<?=option('base_uri')?>schedule">Schedule</a></li>
                        <?php } ?>
                        <?php if (strpos($_SERVER['REQUEST_URI'], option('base_uri') . "members") === 0) { ?>
                        <li class="active"><a href="<?=option('base_uri')?>members">Team</a></li>
                        <?php } else { ?>
                        <li><a href="<?=option('base_uri')?>members">Team</a></li>
                        <?php } ?>
                        <?php if (strpos($_SERVER['REQUEST_URI'], option('base_uri') . "history") === 0) { ?>
                        <li class="active"><a href="<?=option('base_uri')?>history">History</a></li>
                        <?php } else { ?>
                        <li><a href="<?=option('base_uri')?>history">History</a></li>
                        <?php } ?>
                    </ul>
                    <ul class="nav pull-right">
                        <?php if (strpos($_SERVER['REQUEST_URI'], option('base_uri') . "accounts") === 0) { ?>
                        <li class="dropdown active">
                        <?php } else { ?>
                        <li class="dropdown">
                        <?php } ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$_SESSION['CurrentAccount_Name']?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=option('base_uri')?>accounts/<?=$_SESSION['CurrentAccount_ID']?>">Account Settings</a></li>
                                <li><a href="<?=option('base_uri')?>logout">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="content">
            <div class="page-header">
                <h1><?=$title?></h1>
            </div>
            <?php if ($_GET['error'] != null) { ?>
            <div class="alert alert-error">
                <strong>Error:</strong> <?=$_GET['error']?>
            </div>
            <?php } ?>
            <?php if ($_GET['warning'] != null) { ?>
            <div class="alert alert-warning">
                <strong>Warning:</strong> <?=$_GET['warning']?>
            </div>
            <?php } ?>
            <?php if ($_GET['success'] != null) { ?>
            <div class="alert alert-success">
                <strong>Success:</strong> <?=$_GET['success']?>
            </div>
            <?php } ?>
            <?php if ($_GET['info'] != null) { ?>
            <div class="alert alert-info">
                <strong>Information:</strong> <?=$_GET['info']?>
            </div>
            <?php } ?>
            <?=$content?>
        </div>
        <footer>
            <p><a href="<?=option('base_uri')?>"><?=ApplicationName?></a> is powered by <a href="http://github.com/mbmccormick/limoncello" target="_blank">Limoncello</a>. Version <?=Version?>.</p>
        </footer>
    </div>
    </body>
</html>
