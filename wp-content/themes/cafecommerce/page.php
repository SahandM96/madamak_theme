<?php
	get_header();
	get_template_part('template-part/headers/head'); 
?>

<?php 


	if ( is_product_category() ) {
		get_template_part('template-part/content/archive-product/archive-product');
	}
 
	if ( is_page() ) {
		get_template_part('template-part/page-template');
	}
?>


<?php 
	get_template_part('template-part/footers/footer');
	get_footer();