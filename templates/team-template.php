<?php
/**
* The template for displaying the homepage.
*
* Template Name: TM-team
* Template Post Type: team
*
* @package TM
*/
get_header(); ?>

<div id="primary" class="content-area content-area-right-sidebar">
	<main id="main" class="site-main" role="main">
		<div class="teampage-widgets">
			<?php if( is_active_sidebar( 'tm-teampage-main-1' ) ) : ?>
				<?php dynamic_sidebar( 'tm-teampage-main-1' ); ?>
			<?php endif; ?>
			<?php
			if (have_posts()):
				while (have_posts()) : the_post();

				the_content();

			endwhile;
		else:
			echo '<p>Sorry, no posts matched your criteria.</p>';
		endif;
		?>
	</div><!-- .entry-content -->
</main><!-- #main -->
</div><!-- #primary -->

<div id="secondary" class="widget-area widget-area-right" role="complementary">
	<?php if( is_active_sidebar( 'tm-teampage-side-1' ) ) : ?>
		<?php dynamic_sidebar( 'tm-teampage-side-1'); ?>
	<?php endif; ?>
</div><!-- #secondary -->

<?php get_footer(); ?>
