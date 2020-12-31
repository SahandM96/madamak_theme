	<footer class="page-footer">
        <div class="container">
            <div class="row">
                <div class="col-10 col-md-10 text-left">
					<?php if ( has_nav_menu( 'footer-menu' ) ): ?>
					<?php  
						wp_nav_menu(array(  
						'menu' => 'Footer Menu', 
						'container' => false, 
						'theme_location' => 'footer-menu',
						'menu_class'  => 'list-inline',
						'walker' => new CSS_Menu_Maker_Walker_Footer()
						)); 
					?>

			<?php else: ?>
			<div class="no-menu">
				<a class="nav-link" href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" target="_blank">هیچ منویی انتخاب نشده است!</a>
			</div>
			<?php endif; ?>
                </div>
                <div class="col-2 col-md-2 social_icon text-right">
                    <ul class="list-inline">
                        <li class="list-inline-item instagram" style="display:<?php global $cafe; echo $cafe['footer_instagram_box']; ?>">
                            <a class="p-0 d-inline-block" href="<?php global $cafe; echo $cafe['footer_instagram_link']; ?>" target="_blank" title="Instagram">
                                <i class="<?php global $cafe; echo $cafe['footer_instagram_icon']; ?>"></i>
                            </a>
                        </li>
                        <li class="list-inline-item telegram" style="display:<?php global $cafe; echo $cafe['footer_telegram_box']; ?>">
                            <a class="p-0 d-inline-block" href="<?php global $cafe; echo $cafe['footer_telegram_link']; ?>" target="_blank" title="Telegram">
                                <i class="<?php global $cafe; echo $cafe['footer_telegram_icon']; ?>"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>