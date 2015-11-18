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
				\GroovyTheme\get_template_part( \GroovyTheme\get_package_dir('post/template'), '', array(
					'title'   => get_the_title(),
					'author'  => get_the_author(),
					'excerpt' => get_the_excerpt(),
					'link'    => get_permalink()
				) );
				?>

			<?php endwhile; ?>
		<?php endif; ?>

	</div>

<?php
get_footer();