<!DOCTYPE html> 
<html lang="en">
<head> 
    <meta charset="utf-8" /> 
    <title><?=ApplicationName?> - <?=$title?></title> 
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/bootstrap.css" />
    <link rel="stylesheet" href="<?=option('base_uri')?>public/css/basic.css" />
    <link rel="shortcut icon" type="image/x-icon" href="<?=option('base_uri')?>public/img/logo.ico">
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-modal.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-alerts.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-twipsy.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-popover.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-dropdown.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-scrollspy.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-tabs.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/bootstrap-buttons.js"></script>
    <script type="text/javascript" src="<?=option('base_uri')?>public/js/common.js"></script>
</head> 
<body>
    <div class="container">
        <h1><?=ApplicationName?></h1>
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
        <footer>
            <p><a href="<?=option('base_uri')?>"><?=ApplicationName?></a> is powered by <a href="http://github.com/mccormicktechnologies/limoncello" target="_blank">Limoncello</a>. Version <?=Version?>.</p>
        </footer>
    </div>
    </body>
</html>
