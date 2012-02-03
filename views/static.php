<!DOCTYPE html> 
<html lang="en">
<head> 
    <meta charset="utf-8" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?=ApplicationName?> - <?=$title?></title> 
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/bootstrap.css" />
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/static.css" />
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
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="content">
            <?php if ($_SERVER['REQUEST_URI'] != option('base_uri') && $_SERVER['REQUEST_URI'] != "") { ?>
            <div class="page-header">
                <h1><?=$title?></h1>
            </div>
            <?php } ?>
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
            <p><a href="<?=option('base_uri')?>"><?=ApplicationName?></a> is a <a href="http://mccormicktechnologies.com" target="_blank">McCormick Technologies</a> product, powered by <a href="http://github.com/mbmccormick/limoncello" target="_blank">Limoncello</a>.</p>
            <p>&copy; 2012 Paige. All Rights Reserved. Version <?=Version?>.</p>
      </footer>
    </div>
    </body>
</html>
