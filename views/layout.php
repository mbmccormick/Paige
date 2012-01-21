<!DOCTYPE html> 
<html lang="en">
<head> 
    <meta charset="utf-8" /> 
    <title><?=ApplicationName?> - <?=$title?></title> 
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/bootstrap.css" />
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/layout.css" />
    <link rel="shortcut icon" type="image/x-icon" href="<?=option('base_uri')?>public/img/logo.ico">
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/calendrical/jquery.calendrical.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-modal.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-alerts.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-twipsy.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-popover.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-dropdown.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-scrollspy.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-tabs.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-buttons.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/common.js"></script>
</script>
</head> 
<body>
    <div class="topbar">
        <div class="fill">
            <div class="container">
                <a class="brand" href="<?=option('base_uri')?>"><?=ApplicationName?></a>
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
                <ul class="nav secondary-nav">
                    <?php if (strpos($_SERVER['REQUEST_URI'], option('base_uri') . "accounts") === 0) { ?>
                    <li class="dropdown active" data-dropdown="dropdown">
                    <?php } else { ?>
                    <li class="dropdown" data-dropdown="dropdown">
                    <?php } ?>
                        <a href="#" class="dropdown-toggle"><?=$_SESSION['CurrentAccount_Name']?></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?=option('base_uri')?>accounts/<?=$_SESSION['CurrentAccount_ID']?>">Account Settings</a></li>
                            <li><a href="<?=option('base_uri')?>logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <a href="<?=option('base_uri')?>accounts/<?=$_SESSION['CurrentUser_ID']?>"></a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="content">
            <div class="page-header">
                <h1><?=$title?></h1>
            </div>
            <?php if ($_GET['error'] != null) { ?>
            <div class="alert-message error">
                <a class="close" href="#">&times;</a>
                <p><strong><?=$_GET['error']?></strong></p>
            </div>
            <?php } ?>
            <?php if ($_GET['warning'] != null) { ?>
            <div class="alert-message warning">
                <a class="close" href="#">&times;</a>
                <p><strong><?=$_GET['warning']?></strong></p>
            </div>
            <?php } ?>
            <?php if ($_GET['success'] != null) { ?>
            <div class="alert-message success">
                <a class="close" href="#">&times;</a>
                <p><strong><?=$_GET['success']?></strong></p>
            </div>
            <?php } ?>
            <?php if ($_GET['info'] != null) { ?>
            <div class="alert-message info">
                <a class="close" href="#">&times;</a>
                <p><strong><?=$_GET['info']?></strong></p>
            </div>
            <?php } ?>
            <?=$content?>
        </div>
        <footer>
            <p><a href="<?=option('base_uri')?>"><?=ApplicationName?></a> is powered by <a href="http://github.com/mccormicktechnologies/limoncello" target="_blank">Limoncello</a>. Version <?=Version?>.</p>
        </footer>
    </div>
    </body>
</html>
