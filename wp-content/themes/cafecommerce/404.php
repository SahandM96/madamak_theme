<?php
	get_header();
	get_template_part('template-part/headers/head'); 
?>
<main>
	<section>
		<div class="container text-center">
			<div class="error">
				
				<?php global $cafe; if($cafe['404_image']['url']!='') { ?>
					<img class="img_error" src="<?php echo $cafe['404_image']['url']; ?>" style="width:<?php global $cafe; echo $cafe['404_image_width']; ?>;height:<?php global $cafe; echo $cafe['404_image_height']; ?>">
				<?php } else { ?>
					<img class="img_error" src="<?php bloginfo('template_url')?>/assets/img/icon-dissatisfied.svg" style="width:<?php global $cafe; echo $cafe['404_image_width']; ?>;height:<?php global $cafe; echo $cafe['404_image_height']; ?>"/>
				<?php } ?>
				<h1 class="title_error"><?php global $cafe; echo '' . $cafe['404_title']; ?></h1>
				<p class="text_error"><a href="<?php global $cafe; echo '' . $cafe['404_btn_link']; ?>"><?php global $cafe; echo '' . $cafe['404_btn_title']; ?></a></p>
			</div>
		</div>
	</section>
</main>

<?php 
	get_template_part('template-part/footers/footer');
	get_footer();
?>