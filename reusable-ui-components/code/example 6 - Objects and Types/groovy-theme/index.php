<?php
/**
 * Basic template
 */

get_header();
?>

	<div class="container">

		<?php if ( have_posts() ): ?>
			<?php while ( have_posts() ): ?>
				<?php the_post(); ?>

				<?php
				// Set up the ViewModel
				$viewmodel = new \PKGS\Post\View_Model();
				$viewmodel->title = get_the_title();
				$viewmodel->author = get_the_author();
				$viewmodel->excerpt = get_the_excerpt();
				$viewmodel->link = get_permalink();

				// Set up and render the View
				$view = new \PKGS\Post\View($viewmodel);
				echo $view->render();
				?>

			<?php endwhile; ?>
		<?php endif; ?>

	</div>

<?php
get_footer();