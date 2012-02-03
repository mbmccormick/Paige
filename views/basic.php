<!DOCTYPE html> 
<html lang="en">
<head> 
    <meta charset="utf-8" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?=ApplicationName?> - <?=$title?></title> 
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/bootstrap.css" />
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/basic.css" />
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/bootstrap.responsive.css" />
    <link rel="shortcut icon" type="image/x-icon" href="<?=option('base_uri')?>public/img/logo.ico">
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/jquery.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/common.js"></script>
</head> 
<body>
    <div class="container">
        <h1><?=ApplicationName?></h1>
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
        <footer>
            <p><a href="<?=option('base_uri')?>"><?=ApplicationName?></a> is powered by <a href="http://github.com/mbmccormick/limoncello" target="_blank">Limoncello</a>. Version <?=Version?>.</p>
        </footer>
    </div>
    </body>
</html>
