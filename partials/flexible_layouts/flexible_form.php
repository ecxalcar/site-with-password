<!--FORM
===========================================-->
<?php
	$form_section_title = get_sub_field('form_section_title');

	if ($repeater_gallery) {

?>
	<section id="form">
		<div class="container-fluid">
			<div class="wrapper">
				<h1><?php echo $form_section_title; ?></h1>
				<?php 
					if (have_posts()) {
						while (have_posts()) {
							the_post();
							the_content();
						}
					}
				?>	
			</div><!--wrapper-->
		</div><!--container-fluid-->
	</section><!--form-->
<?php
	} //endif
?>
