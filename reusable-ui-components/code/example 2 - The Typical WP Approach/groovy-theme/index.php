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

				<div class="post">
					<div class="post__title"><?php the_title() ?></div>
					<div class="post__author"><?php the_author() ?></div>
					<div class="post__excerpt"><?php the_excerpt() ?></div>
					<a href="" class="post__permalink"><?php the_permalink() ?></a>
				</div>

			<?php endwhile; ?>
		<?php endif; ?>

	</div>

<?php
get_footer();