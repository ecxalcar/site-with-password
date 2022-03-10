<!DOCTYPE html>
<html <?php language_attributes();?>>

    <head>
        <meta charset="<?php bloginfo( 'charset' );?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="<?php bloginfo('description'); ?>">
		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
		<!-- CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

		<!-- jQuery and JS bundle w/ Popper.js -->
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <?php wp_head();?>
    </head>

	<body <?php body_class();?>>
		<header>
			<div id="header">
				<div class="takeOff-container">
					<div class="header-center">
						<div class="header-logo container-logo">
							<a href="/">
								<h1>&lt;/CODE&gt;</h1>
							</a>
						</div>
						<div id="nav-icon">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
						<div class="headerNav">
							<nav class="header-nav">
								<?php wp_nav_menu(
									array(
										'theme_location' => 'header-menu',
										'menu_class' => 'nav-menu',
										'container' => 'ul'
									));
								?>
							</nav>
						</div>
					</div>
				</div>
				
				<div class="header-mobile">
					<div class="takeOff-container">
						<nav class="header-nav">
							<?php wp_nav_menu(
								array(
									'theme_location' => 'header-menu',
									'menu_class' => 'nav-menu',
									'container' => 'ul'
								));
							?>
						</nav>
					</div>
				</div>
			</div>
		</header>

		<?php form_password_page(); ?>