<footer>

    <div class="background">
        <img src="<?php echo get_template_directory_uri().'/images/assassins-creed-syndicate.jpg' ?>" alt="">
        <div class="overly"></div>
    </div>

    <div class="container">

        <div class="about">
            <h1 class="footer-title"><?php echo bloginfo('name'); ?></h1>
            <p class="subtitle"><?php the_field('shoar','option'); ?></p>

            <p class="text"><?php the_field('about-shop','option'); ?></p>
        </div>

        <div class="sitemap">
            <h6 class="footer-title">لینک های مفید</h6>
            <p class="subtitle">دسترسی آسان به تمام بخش های فروشگاه</p>

            <?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' =>'', 'menu_class' =>'nav-items' ) ); ?>
        </div>

        <div class="contact">
        
            <h6 class="footer-title">ارتباط با ما</h6>
            <p class="subtitle">سوالات و پیشنهادات خود را مطرح کنید</p>

            <ul>
                <li><?php the_field('contact-email','option'); ?><i class="ti-email"></i></li>
                <li><?php the_field('contact-first-number','option'); ?><i class="ti-mobile"></i></li>
                <li><?php the_field('contact-second-number','option'); ?><i class="ti-mobile"></i></li>
                <li><a href="<?php the_field('submit-ticket-page','option'); ?>"><i class="ti-location-arrow"></i>ارسال تیکت</a></li>
            </ul>

        </div>

    </div>


    <div class="social">

        <div>
            <h6 class="footer-title">شبکه های اجتماعی :</h6>
        </div>

        <div>
            <a href="<?php the_field('social-instagram','option'); ?>">Instagram<i class="fab fa-instagram"></i></a>
            <a href="<?php the_field('social-telegram','option'); ?>">Telegram<i class="fab fa-telegram"></i></a>
            <a href="<?php the_field('social-linkedin','option'); ?>">Linkedin<i class="fab fa-linkedin"></i></a>
            <a href="<?php the_field('social-facebook','option'); ?>">Facebook<i class="fab fa-facebook"></i></a>
            <a href="<?php the_field('social-twitter','option'); ?>">Twitter<i class="fab fa-twitter"></i></a>
        </div>

	</div>

	<?php $en1 = get_field('tlogo-1','option'); $en2 = get_field('tlogo-2','option'); if (!empty($en1) || !empty($en2)) { ?>
	
	<div class="trust-logos">

        <div>
            <h6 class="footer-title">نماد ها :</h6>
        </div>

        <div>
			<?php echo $en1; echo $en2; ?>		
        </div>

    </div>

	<?php } ?>


    <div class="creator">

				<p><?php the_field('copyright','option'); ?></p>
				
    </div>



</footer>

<?php wp_footer(); ?>

</div> </body>

<style>
/* Overrides for right-to-left sliders. */

.ui-slider-horizontal.ui-slider-rtl .ui-slider-range-min { left: auto; right: 0; }
.ui-slider-horizontal.ui-slider-rtl .ui-slider-range-max { left: 0; right: auto; }

.ui-slider-vertical.ui-slider-rtl .ui-slider-range-min { top: 0; bottom: auto; }
.ui-slider-vertical.ui-slider-rtl .ui-slider-range-max { top: auto; bottom: 0; }
</style>

<script
  src="<?php echo get_template_directory_uri().'/jsmini/jquery.min.js'; ?>"></script>
<script src="<?php echo get_template_directory_uri().'/jsmini/jquery-ui.min.js'; ?>"></script>
<script src="<?php echo get_template_directory_uri().'/jsmini/jquery.ui.slider-rtl.js'; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.12/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="<?php echo get_template_directory_uri().'/jsmini/owls.js'; ?>"></script>
<script src="<?php echo get_template_directory_uri().'/jsmini/main.js'; ?>"></script>
</body>

</html>