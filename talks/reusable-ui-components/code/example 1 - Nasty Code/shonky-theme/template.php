<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package ShonkyTheme 2014-05
 */


require( TEMPLATEPATH . '/inc/home_helper.php' );

function new_excerpt_more( $more )
{
	global $post;

	return '...';
}

add_filter( 'excerpt_more', 'new_excerpt_more' );

get_header();

$news_category_slug           = 'news-daily';
$news_title                   = 'News Daily';
$news_slogan                  = "All the biggest shonky news and more from around the globe";
$top_feature_section_post_ids = array();

// get the first feature article for the top of the page
$feature_query = new WP_Query( "category_name=feature&posts_per_page=1" );

if ( $feature_query->have_posts() ) {
	$feature_query->the_post();
	$top_feature_post_id            = $post->ID;
	$top_feature_section_post_ids[] = $top_feature_post_id;

	if ( has_post_thumbnail( $top_feature_post_id ) ) {
		$feature_image = wp_get_attachment_image_src( get_post_thumbnail_id( $top_feature_post_id ), 'single-post-thumbnail' );
	} else {
		$feature_image = get_template_directory_uri() . "/images/default_large_feature_image.png";
	}
	?>
	<div class='body-wrapper'>
	<div class="feature-image" style="background-image: url('<?php echo $feature_image[0]; ?>');">
		<div class="gradient"></div>
		<div id='feature-wrapper'>

			<div class='feature-details'>
				<?php
				/**
				 * Action hook for associate logos. Hook is found in the following files;
				 *    home.php
				 *    single_title_section.php
				 *    archive-news.php
				 *    archive.php
				 */
				do_action( 'shonkytheme/hook/associate_logo' );
				$feature_title_override_font_size = get_post_custom_values( 'feature_title_override_font_size', $top_feature_post_id );
				if ( isset( $feature_title_override_font_size[0] ) ) {
					$feature_major_heading_style_override = "style='font-size: " . $feature_title_override_font_size[0] . "px;'";
				} else {
					$feature_major_heading_style_override = "";
				}
				?>
				<h2 class='feature-major-heading' <?php echo $feature_major_heading_style_override; ?>>
					<a href='<?php the_permalink(); ?>'>
						<?php
						$feature_title_override_text = get_post_custom_values( 'feature_title_override_text', $top_feature_post_id );
						if ( isset( $feature_title_override_text[0] ) ) {
							echo $feature_title_override_text[0];
						} else {
							echo the_title();
						}
						?>
					</a>
				</h2>

				<p class="post-date"><?= get_the_date() ?></p>

				<div class='feature-excerpt'>
					<?php the_excerpt(); ?>
				</div>
				<div class='feature-details-gutter'>

					<?php if ( get_comments_number( $top_feature_post_id ) > 0 ): ?>
						<div class='article-comment-count'>
							<i class="shonkythemeicon-speech-bbl"></i>
							<?= get_comments_number( $top_feature_post_id ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php
}
wp_reset_query();
?>

	<div class='home-section-container home-section-container--features'>
		<div class='home-right-col home-news-right-col'>

			<div class='home-upper-ad-section'>
				<?php require( TEMPLATEPATH . '/inc/ad_home_upper_right.php' ); ?>
			</div>

			<?php
			/**
			 * Dropping in race results widget
			 */
			$results_model = new Shonkytheme\Block\RaceResultsSmall\Model( Shonkytheme\Block\RaceResultsSmall\Model::getNonWomensEventData( 2 ) );
			$results_view  = new Shonkytheme\Block\RaceResultsSmall\View( $results_model );
			echo $results_view;
			?>

		</div>
		<div class='home-left-col'>
			<div class='home-articles-section'>

				<div class="home-articles-section__video">
					<?= \Shonkytheme\Bluescreen::get_random_video_ad_unit() ?>
				</div>

				<?php
				$query_args            = array_merge( $wp_query->query_vars, array(
					'cat'            => STHomeHelper::getCategoryIdForCategorySlug( 'feature' ),
					'post__not_in'   => array( $top_feature_post_id ),
					'posts_per_page' => 2
				) );
				$recent_articles_query = new WP_Query( $query_args );

				while ( $recent_articles_query->have_posts() ) {
					$recent_articles_query->next_post();

					$top_feature_section_post_ids[] = $recent_articles_query->post->ID;

					STHomeHelper::getArticleHtml( $recent_articles_query->post->ID, 'horizontal', 'medium', true, true );
				}
				?>
			</div>
			<div class='home-section-container-bottom-border'></div>

			<?php
			/**
			 * LATEST NEWS Block
			 * =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			 */
			$latest_news_posts = get_posts( array(
				'cat'              => implode( ',', array(
					\Shonkytheme\Meta::$news_cat_id,
					\Shonkytheme\Meta::$news_daily_cat_id
				) ),
				'category__not_in' => \Shonkytheme\Meta::$feature_cat_id,
				'posts_per_page'   => 7
			) );
			$news_cat_link     = get_category_link( \Shonkytheme\Meta::$news_cat_id );
			?>
			<div class='home-articles-section home-news-section'>
				<a class='home-category-more-link' href="<?= $news_cat_link ?>">MORE <span>&plus;</span></a>

				<h2 class='home-section-heading'><a href="<?= $news_cat_link ?>">Latest News</a></h2>

				<div style='clear: left;'></div>
				<?php
				/** @var WP_Post $post */
				foreach ( $latest_news_posts as $post ) {
					STHomeHelper::getArticleHtml( $post->ID, 'horizontal', 'small', true, false );
				}
				?>
			</div>
		</div>
		<div style='clear:both;'></div>
	</div>

	<div class='home-section-container-bottom-border'></div>

	<div class='home-section-container'>
		<div class='home-articles-section'>
			<a class='home-category-more-link' href='/category/feature/'>MORE <span>&plus;</span></a>

			<h2 class='home-section-heading'><a href='/category/feature/'>Latest Features</a></h2>

			<div style='clear: left;'></div>

			<?php
			$category_list = STHomeHelper::getCategoryIdForCategorySlug( 'feature' );

			$query_args            = array_merge( $wp_query->query_vars, array(
				'cat'            => $category_list,
				'posts_per_page' => 4,
				'post__not_in'   => $top_feature_section_post_ids
			) );
			$latest_features_query = new WP_Query( $query_args );

			while ( $latest_features_query->have_posts() ) {
				$latest_features_query->next_post();

				echo STHomeHelper::getArticleHtml( $latest_features_query->post->ID, 'vertical', 'medium', true, true );
			}
			?>

		</div>
	</div>

	<div class='home-section-container-bottom-border'></div>

	<div class='home-section-container'>
		<div class='home-articles-section'>
			<a class='home-category-more-link' href='/category/tech-features/'>MORE <span>&plus;</span></a>

			<h2 class='home-section-heading'><a
					href='/category/tech-features/'>Tech</a><?= apply_filters( 'shonkytheme/filter/home_tech_section_title_appendage', '' ); ?>
			</h2>

			<div style='clear: left;'></div>

			<?php
			$query_args    = array_merge( $wp_query->query_vars, array(
				'cat'            => STHomeHelper::getCategoryIdForCategorySlug( 'tech-features' ),
				'posts_per_page' => 3
			) );
			$reviews_query = new WP_Query( $query_args );

			while ( $reviews_query->have_posts() ) {
				$reviews_query->next_post();

				echo STHomeHelper::getArticleHtml( $reviews_query->post->ID, 'vertical', 'large', true, true );
			}
			?>

		</div>
	</div>
	<div class='home-section-container-bottom-border'></div>

	<div class='home-section-container'>
		<div class='home-articles-section'>
			<a class='home-category-more-link' href='/category/roadtripping/'>MORE <span>&plus;</span></a>

			<h2 class='home-section-heading'><a href='/category/roadtripping/'>Roadtripping</a></h2>

			<div style='clear: left;'></div>

			<?php
			$query_args         = array_merge( $wp_query->query_vars, array(
				'cat'            => STHomeHelper::getCategoryIdForCategorySlug( 'roadtripping' ),
				'posts_per_page' => 3
			) );
			$roadtripping_query = new WP_Query( $query_args );

			while ( $roadtripping_query->have_posts() ) {
				$roadtripping_query->next_post();

				echo STHomeHelper::getArticleHtml( $roadtripping_query->post->ID, 'vertical', 'large', true, true, true );
			}
			?>

		</div>
	</div>

	<div class='home-section-container-bottom-border'></div>

<?php
if ( ST_SITE == 'ENGLISH' ) {
	?>
	<div class='home-section-container'>
		<div class='home-articles-section'>
			<a class='home-category-more-link'
			   href='<?= $womens_url = get_permalink( \Shonkytheme\Meta::$ella_home_page_id ) ?>'>MORE
				<span>&plus;</span></a>

			<h2 class='home-section-heading'><a href='<?= $womens_url ?>'>Women's
					Cycling</a><?= apply_filters( 'shonkytheme/filter/home_womens_section_title_appendage', '' ); ?>
			</h2>

			<div style='clear: left;'></div>
			<?php
			/*
									<a class='home-section-heading-sponsored' href='/category/womens-shonky/' style="background-image: url('<?php echo get_template_directory_uri(); ?>/images/home-womens-shonky-sponsor-logo.png');">
										  <h2>Women's Cycling</h2>
									</a>
			*/
			?>

			<?php
			$query_args   = array_merge( $wp_query->query_vars, array(
				'cat'            => STHomeHelper::getCategoryIdForCategorySlug( 'ella' ),
				'posts_per_page' => 3
			) );
			$womens_query = new WP_Query( $query_args );

			while ( $womens_query->have_posts() ) {
				$womens_query->next_post();

				echo STHomeHelper::getArticleHtml( $womens_query->post->ID, 'vertical', 'large', true, true );
			}
			?>

		</div>
	</div>

	<?php
}
?>

	<div class='home-section-container-bottom-border'></div>

	<div class='home-section-container'>
		<div class='home-articles-section'>
			<a class='home-category-more-link' href='/category/featured-video/'>MORE <span>&plus;</span></a>

			<h2 class='home-section-heading'><a href='/category/featured-video/'>Featured Videos</a></h2>

			<div style='clear: left;'></div>

			<div class='home-featured-video-column'>
				<?php
				$query_args           = array_merge( $wp_query->query_vars, array(
					'cat'            => STHomeHelper::getCategoryIdForCategorySlug( 'featured-video' ),
					'posts_per_page' => 6
				) );
				$featured_video_query = new WP_Query( $query_args );


				$ctr = 1;
				while ( $featured_video_query->have_posts() )
				{
				$featured_video_query->next_post();

				echo STHomeHelper::getArticleHtml( $featured_video_query->post->ID, 'horizontal', 'small', false, true );

				if ( $ctr == 2 || $ctr == 4 )
				{
				?>
			</div>
			<div class='home-featured-video-column'>
				<?php
				}

				$ctr ++;
				}
				?>
			</div>
		</div>
	</div>

	<div class='home-section-container-bottom-border'></div>

<?php
if ( ST_SITE == 'ENGLISH' ) {
	?>
	<div class='home-section-container'>
		<div class='home-articles-section'>
			<a class='home-category-more-link' href='/category/self-improvement/'>MORE <span>&plus;</span></a>

			<h2 class='home-section-heading'><a href='/category/self-improvement/'>Tips and Self Improvement</a>
			</h2>

			<div style='clear: left;'></div>

			<?php
			$query_args    = array_merge( $wp_query->query_vars, array(
				'cat'            => STHomeHelper::getCategoryIdForCategorySlug( 'self-improvement' ),
				'posts_per_page' => 3
			) );
			$reviews_query = new WP_Query( $query_args );

			while ( $reviews_query->have_posts() ) {
				$reviews_query->next_post();

				echo STHomeHelper::getArticleHtml( $reviews_query->post->ID, 'vertical', 'large', true, true );
			}
			?>

		</div>
	</div>

	<div class='home-section-container-bottom-border'></div>
	<?php
}
?>

<?php
if ( ST_SITE == 'ENGLISH' ) {
	?>
	<div class='home-section-container'>
		<div class='home-right-col' id='divSecretProAd'>
			<?php include( TEMPLATEPATH . '/inc/ad_home_secret_pro.php' ); ?>
		</div>
		<div class='home-left-col'>
			<div class='home-articles-section'>
				<a class='home-category-more-link' href='/tag/the-secret-pro/'>MORE <span>&plus;</span></a>

				<h2 class='home-section-heading'><a href='/tag/the-secret-pro/'>The Secret Pro</a></h2>

				<div style='clear: left;'></div>

				<?php
				$query_args       = array_merge( $wp_query->query_vars, array(
					'tag'            => 'the-secret-pro',
					'posts_per_page' => 5
				) );
				$secret_pro_query = new WP_Query( $query_args );

				if ( $secret_pro_query->have_posts() ) {
					$secret_pro_query->next_post();

					$secret_pro_homepage_quote = get_post_custom_values( 'secret_pro_homepage_quote', $secret_pro_query->post->ID );
				}
				?>

				<blockquote class='home-secret-pro-blockquote'>
					<?php
					if ( isset( $secret_pro_homepage_quote[0] ) ) {
						echo $secret_pro_homepage_quote[0];
					}
					?>
				</blockquote>

				<div class='home-secret-pro-logo'></div>
				<div class='home-secret-pro-article-list'>

					<?php
					echo STHomeHelper::getArticleHtml( $secret_pro_query->post->ID, 'title-and-date', '', true, true );

					while ( $secret_pro_query->have_posts() ) {
						$secret_pro_query->next_post();

						echo STHomeHelper::getArticleHtml( $secret_pro_query->post->ID, 'title-and-date', '', true, true );
					}
					?>
				</div>
			</div>
		</div>
		<div style='clear:both;'></div>
	</div>

	<div class='home-section-container-bottom-border'></div>
	<?php
}
?>

<?php
if ( ST_SITE == 'ENGLISH' ) {
	?>
	<div class='home-section-container'>
		<div class='home-right-col-no-border'>
			<div class='home-articles-section'>
				<h2 class='home-section-heading'>Proudly Supporting</h2>
				<img class='home-supporting-logo'
				     src='<?php echo get_template_directory_uri(); ?>/images/supporting-amy-gillet-foundation.jpg'/>
				<img class='home-supporting-logo'
				     src='<?php echo get_template_directory_uri(); ?>/images/supporting-candle-network.png'/>
			</div>

		</div>
		<div class='home-left-col'>
			<div class='home-articles-section'>
				<h2 class='home-section-heading'>Longterm Supporters</h2>
				<img class='home-supporter-logo'
				     src='<?php echo get_template_directory_uri(); ?>/images/supporter-company1.png'/>
				<img class='home-supporter-logo'
				     src='<?php echo get_template_directory_uri(); ?>/images/supporter-company2.png'/>
				<img class='home-supporter-logo' width="84" height="77"
				     src='<?php echo get_template_directory_uri(); ?>/images/logo-company3-2015.svg'/>
				<img class='home-supporter-logo'
				     src='<?php echo get_template_directory_uri(); ?>/images/supporter-styletours.jpg'/>
			</div>
		</div>
		<div style='clear:left;'></div>
	</div>
	<?php
}
?>

	</div>

<?php
get_footer();
?>