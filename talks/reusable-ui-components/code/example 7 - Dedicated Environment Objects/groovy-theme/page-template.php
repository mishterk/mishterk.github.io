<?php
/**
 * Template Name: Page Template
 */

get_header();
?>

	<div class="other-container">

		<?php if ( have_posts() ): ?>
			<?php while ( have_posts() ): ?>
				<?php the_post(); ?>

				<?php
				// Set up the ViewModel
				$viewmodel = new \PKGS\Post\WordPress_View_Model(get_post());

				// Set up and render the View
				$view = new \PKGS\Post\View($viewmodel);
				echo $view->render();
				?>

			<?php endwhile; ?>
		<?php endif; ?>

	</div>

<?php
get_footer();