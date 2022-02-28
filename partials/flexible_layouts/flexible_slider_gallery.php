<!--LOGO SLIDER
===========================================-->
<?php
	$section_title = get_sub_field('section_title');
	$repeater_gallery = get_sub_field('repeater_gallery');

	if ($repeater_gallery) {
?>
	<section id="logo-slider">
		<div class="container-fluid">
			<h1><?php echo $section_title; ?></h1>
		</div><!--container-fluid-->
		<div class="responsive container-slider">
			<?php foreach($repeater_gallery as $slide_image) {?>
				<div class="logo-slide">
					<div class="wrapper">
						<img src="<?php echo $slide_image['slide_image']['url'];?>" alt="">
						<!-- <a href=""> -->
						<!-- </a> -->
					</div>
				</div><!--logo-slide-->
			<?php  }?>
		</div><!--responsive-->
	</section><!--#logo-slider-->
<?php
	} //endif
?>
