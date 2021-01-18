<?php get_header(); ?>


<main>

    <div class="background-container">
        <img class="slide-background" src="<?php echo get_template_directory_uri().'/images/watch-dogs-2.jpg'; ?>">
        <div class="overly"></div>
    </div>

    <div class="contact-page">
    
        <div class="page-heading ">
        
            <h1>راه های ارتباطی</h1>
            <h6>CONTACT WAYS</h6>

        </div>

        <div class="items">
        

            <div class="way">

                <span class="line-x-l"></span>
                <span class="line-y-t"></span>

                <img src="<?php echo get_template_directory_uri().'/images/svg/phone.svg'; ?>" alt="">
                <p><?php the_field('contact-first-number','option'); ?></p>

                <span class="line-x-r"></span>
                <span class="line-y-b"></span>

            </div>

            <div class="way">

                <span class="line-x-l"></span>
                <span class="line-y-t"></span>

                <img src="<?php echo get_template_directory_uri().'/images/svg/fix-phone.svg'; ?>" alt="">
                <p><?php the_field('contact-second-number','option'); ?></p>

                <span class="line-x-r"></span>
                <span class="line-y-b"></span>

            </div>

            <div class="way">

                <span class="line-x-l"></span>
                <span class="line-y-t"></span>

                <img src="<?php echo get_template_directory_uri().'/images/svg/mail.svg'; ?>" alt="">
                <p><?php the_field('contact-email','option'); ?></p>

                <span class="line-x-r"></span>
                <span class="line-y-b"></span>

            </div>

            <a href="<?php the_field('submit-ticket-page','option'); ?>" class="way">

                <span class="line-x-l"></span>
                <span class="line-y-t"></span>

                <img src="<?php echo get_template_directory_uri().'/images/svg/send.svg'; ?>" alt="">
                <p class="per">ارسال تیکت</p>

                <span class="line-x-r"></span>
                <span class="line-y-b"></span>

            </a>

            <?php if (!empty(get_field('contact-address','option'))) : ?>
            <div class="address">
                <p><i class="ti-map-alt"></i><span>آدرس:</span> <?php the_field('contact-address','option'); ?></p>
            </div>
            <?php endif; ?>

        </div>



        <?php if (!empty(get_field('contact-form','option'))) : ?>
        <div class="subtitle">
            <h6>ارسال پیام</h6>
            <p>ارسال پیشهادات و انتقادات</p>
        </div>

        <div class="contact-form">
            <?php echo do_shortcode(get_field('contact-form','option')); ?>
        </div>
        <?php endif; ?>

    </div>
  
</main>

<?php get_footer(); ?>