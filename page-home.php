<?php /* Template Name: Home Page */?>
<?php get_header(); ?>

<main>
	<?php if (have_posts()): 
		while (have_posts()) : the_post(); ?>
			<div class="container">
				<?php the_content(); ?>
			</div>
		<?php endwhile; ?>
	<?php endif ?>
    
    <?php $map = get_field('map'); ?>
    <?php echo do_shortcode($map); ?>
</main>

<script>
//DARK MODE
const btnSwitch = document.querySelector('#checkbox');
// const currentTheme = localStorage.getItem('theme');

btnSwitch.addEventListener('click', () => {
    //Set the atributue data-theme
    if ( btnSwitch.checked ) {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.documentElement.removeAttribute('data-theme', 'dark');
    }

    //Save the theme mode in localStorage
    if( document.documentElement.getAttribute('data-theme') == 'dark') {
        localStorage.setItem('dark-theme', 'true');
    } else {
        localStorage.setItem('dark-theme', 'false');
    }

});

if ( localStorage.getItem('dark-theme') === 'true') {
    document.documentElement.setAttribute('data-theme', 'dark');
} else {
    document.documentElement.removeAttribute('data-theme', 'dark');
}

</script>
<?php get_footer(); ?>
