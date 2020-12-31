<?php get_template_part('ajax', 'auth'); ?>   
<header class="py-5 mb-5">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background:<?php global $cafe; echo ''  . $cafe['header_bg']['background-color']; ?>;">
        <div class="container mt-md-4 mt-0">
			<a class="navbar-brand ml-0" href="<?php bloginfo('url');?>">
				<?php global $cafe; if($cafe['logo']['url']!='') { ?>
					<img class="img-fluid" src="<?php echo $cafe['logo']['url']; ?>" width="90px">
				<?php } else { ?>
					<img class="img-fluid" src="<?php bloginfo('template_directory'); ?>/assets/img/logo.png" width="90px">
				<?php } ?>
			</a>
            <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
				<span class="navbar-toggler-icon"></span>
            </button>
			<div class="navbar-collapse offcanvas-collapse" id="navbarCafe">
			<?php if ( has_nav_menu( 'header-menu' ) ): ?>
					<?php  
						wp_nav_menu(array(  
						'menu' => 'Header Menu', 
						'container' => false, 
						'theme_location' => 'header-menu',
						'menu_class'  => 'navbar-nav mr-md-2',
						'walker' => new CSS_Menu_Maker_Walker()
						)); 
					?>
			<?php else: ?>
			<div class="no-menu">
				<a class="nav-link" href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" target="_blank">هیچ منویی انتخاب نشده است!</a>
			</div>
			<?php endif; ?>
			<form class="form-inline mr-auto" style="display:<?php global $cafe; echo $cafe['search_box']; ?>" action="<?php echo home_url( '/' ); ?>">
                <div class="form-group" style="background:<?php global $cafe; echo ''  . $cafe['input_serch_back']['background-color']; ?>;">
                    <button class="btn my-2 my-sm-0" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
					<input class="form-control search-field" type="search" name="s" placeholder="<?php global $cafe; echo $cafe['serch_text']; ?>">
                </div>
            </form>
            <ul class="nav navbar-nav ml-auto">
				<?php if (is_user_logged_in()) { ?>
				<li class="nav-item dropdown">
					<a href="javascript:void(0);" class="dropdown-toggle nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user-o"></i>
						<?php  
							$current_user = wp_get_current_user();
							echo '' . $current_user->display_name . ''; 
						?>
					</a>
					<?php 
						wp_nav_menu(array(  
							'menu' => 'Users Menu', 
							'container' => false, 
							'theme_location' => 'users-menu',
							'menu_class'  => 'dropdown-menu',
							'menu_id' => 'user-menu'
						)); 
					?>
				</li>
				<?php } ?>
				<?php if (!is_user_logged_in()) { ?>
				<li class="nav-item">
					<a id="" class="nav-link" href="" data-toggle="modal" data-target="#ajax_login"><?php global $cafe; echo $cafe['login_text_homepage']; ?></a>
				</li>
                <li class="nav-item">
                    <a class="nav-link" href="" data-toggle="modal" data-target="#ajax_register"><?php global $cafe; echo $cafe['register_text_homepage']; ?></a>
                </li>
				<?php } ?>
				<li class="nav-item" style="display:<?php global $cafe; echo $cafe['cart_active']; ?>">
					<a class="nav-link" href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>" target="_blank">
						<i class="fa fa-shopping-cart"></i>
						<?php echo WC()->cart->get_cart_contents_count(); ?>
					</a>
				</li>
            </ul>
            </div>
        </div>
    </nav>
</header>
