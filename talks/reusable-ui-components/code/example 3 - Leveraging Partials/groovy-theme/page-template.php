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

				<?php get_template_part( 'partials/post' ) ?>

			<?php endwhile; ?>
		<?php endif; ?>

	</div>

<?php
get_footer();