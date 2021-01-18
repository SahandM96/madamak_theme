<?php get_header(); ?>


<main>

    <div class="background-container">
        <img class="slide-background" src="<?php echo get_template_directory_uri().'/images/call-duty-mobile.jpg'; ?>">
        <div class="overly"></div>
    </div>

    <div class="about-page">
    
        <div class="page-heading ">
        
            <h1>درباره ما</h1>

        </div>

        <?php if (get_field('services-show','option')) : ?>
        <div class="subtitle">
            <h6>خدمات</h6>
            <p>برخی از خدماتی که ما به شما ارائه میکنیم</p>
        </div>

        <div class="services">

            <div class="service">
                <img src="<?php the_field('service-icon-1','option'); ?>" alt="">
                <h6><?php the_field('service-title-1','option'); ?></h6>
                <p><?php the_field('service-text-1','option'); ?></p>
            </div>

            <div class="service">
                <img src="<?php the_field('service-icon-2','option'); ?>" alt="">
                <h6><?php the_field('service-title-2','option'); ?></h6>
                <p><?php the_field('service-text-2','option'); ?></p>
            </div>

            <div class="service">
                <img src="<?php the_field('service-icon-3','option'); ?>" alt="">
                <h6><?php the_field('service-title-3','option'); ?></h6>
                <p><?php the_field('service-text-3','option'); ?></p>
            </div>

        </div>
        <?php endif; ?>


        <div class="subtitle">
            <h6>درباره ما</h6>
            <p>کمی بیشتر در مورد ما بدانید</p>
        </div>

        <div class="about-content">

            <?php while(have_posts()) { the_post(); the_content(); } ?>

        </div>




        <?php if (get_field('customer-show','option')) : ?>
        <div class="subtitle">
            <h6>مشتریان ما چه میگویند ؟</h6>
            <p>نظر برخی از مشتریان درباره ما</p>
        </div>

        <div class="customer-items">

            <button class="controllerBtn controller-per ti-angle-left" @click="customerNav('next')"></button>
        
            <div class="customer animated pulse" :class="customerStatus[1]" v-show="customerStatus[1] === 'show'">
                <div class="customer-wrapper">
                    <div class="photo">
                        <img src="<?php the_field('customer-photo-1','option'); ?>" alt="">
                    </div>
                    <div class="customer-name">
                        <p><?php the_field('customer-name-1','option'); ?></p>
                        <p><?php the_field('customer-semat-1','option'); ?></p>
                    </div>
                    <div class="customer-comment">
                        <p><?php the_field('customer-comment-1','option'); ?></p>
                    </div>
                </div>
            </div>

            <div class="customer animated pulse" :class="customerStatus[2]" v-show="customerStatus[2] === 'show'">
                <div class="customer-wrapper">
                    <div class="photo">
                        <img src="<?php the_field('customer-photo-2','option'); ?>" alt="">
                    </div>
                    <div class="customer-name">
                        <p><?php the_field('customer-name-2','option'); ?></p>
                        <p><?php the_field('customer-semat-2','option'); ?></p>
                    </div>
                    <div class="customer-comment">
                        <p><?php the_field('customer-comment-2','option'); ?></p>
                    </div>
                </div>
            </div>

            <div class="customer animated pulse" :class="customerStatus[3]" v-show="customerStatus[3] === 'show'">
                <div class="customer-wrapper">
                    <div class="photo">
                        <img src="<?php the_field('customer-photo-3','option'); ?>" alt="">
                    </div>
                    <div class="customer-name">
                        <p><?php the_field('customer-name-3','option'); ?></p>
                        <p><?php the_field('customer-semat-3','option'); ?></p>
                    </div>
                    <div class="customer-comment">
                        <p><?php the_field('customer-comment-3','option'); ?></p>
                    </div>
                </div>
            </div>


            <div class="controller">
                <button @click="changeCustomer(1)" :class="customerStatus[1]"></button>
                <button @click="changeCustomer(2)" :class="customerStatus[2]"></button>
                <button @click="changeCustomer(3)" :class="customerStatus[3]"></button>
            </div>

            <button class="controllerBtn controller-next ti-angle-right" @click="customerNav('per')"></button>
        </div>
        <?php endif; ?>





        <?php if (get_field('story-show','option')) : ?>
        <div class="subtitle">
            <h6>داستان ما</h6>
            <p>داستان شکل گیری ما</p>
        </div>

        <div class="timeline">

            <div class="container">

                <span class="line"></span>

                <?php while(have_rows('story-level','option')) : the_row(); ?>
                <div class="item">

                    <div class="time">
                        <p><?php the_sub_field('story-year','option'); ?></p>
                    </div>

                    <div class="shapes">
                        <span class="point"></span>
                    </div>

                    <div class="event">

                        <h6><?php the_sub_field('story-title','option'); ?></h6>
                        <p><?php the_sub_field('story-text','option'); ?></p>

                    </div>

                </div>
                <?php endwhile; ?>
                

            </div>

        </div>
        <?php endif; ?>

    </div>
  
</main>

<?php get_footer(); ?>