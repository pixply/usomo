<?php

/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) {
	get_template_part('template-parts/footer');
}
?>

<div class="magic-menu" style="display: none;">
	<div class="magic-menu__underlay">
		<div class="magic-menu__background">
			<div class="wrapper">
				<div class="menu-wrapper">
					<?php
					wp_nav_menu(array(
						'menu' => 'main-1',
						'container_class' => 'main-menu'
					));
					?>
				</div>
				<div class="info" style="color: #fff">

					<div class="portrait">
						<img width="1658" height="1144" src="/wp-content/uploads/2021/12/bild1.jpg" class="attachment-full size-full" alt="" loading="lazy" srcset="/wp-content/uploads/2021/12/bild1.jpg 1658w, /wp-content/uploads/2021/12/bild1-300x207.jpg 300w, /wp-content/uploads/2021/12/bild1-1024x707.jpg 1024w, /wp-content/uploads/2021/12/bild1-768x530.jpg 768w, /wp-content/uploads/2021/12/bild1-1536x1060.jpg 1536w" sizes="(max-width: 1658px) 100vw, 1658px">
					</div>
					<div class="text">
						<h4>usomo | Experience unique sonic moments</h4>
						<p>
							FRAMED immersive projects GmbH & Co. KG<br>
							Kopernikusstraße 5<br>
							D-10243 Berlin
						</p>
					</div>

				</div>
			</div>

		</div>
	</div>
</div>
<?php wp_footer(); ?>

</body>

</html>