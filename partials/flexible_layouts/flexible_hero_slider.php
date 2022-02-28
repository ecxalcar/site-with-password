<?php 
    $hero_slides = get_sub_field('hero_slide');

    if ($hero_slides) {
?>

    <section class="hero-page">
        <div class="single-item">

        <?php foreach($hero_slides as $hero_slide){ ?>
            <div class="hero-slide">
                <div class="container-fluid">
                    <div class="wrapper">
                        <div class="left-block">
                            <h1><?php echo $hero_slide['slide_title'] ?></h1>
                            <p><?php echo $hero_slide['slide_description'] ?></p>
                        </div>
                        <div class="right-block">
                        <?php if($hero_slide['slide_image']): ?>
                            <img src="<?php echo $hero_slide['slide_image']['url'];?>" alt="">
                        <?php else: ?>
                            <img src="<?php echo get_template_directory_uri()?>/assets/img/hero-house.svg" alt="">
                        <?php endif; ?>
                        </div>
                    </div>
                </div><!--container-fluid-->
            </div><!--hero-slide-->
        <?php } ?>

        </div><!--single-item-->
    </section>

<?php 
    }//endif
?>