<?php
/**
* The template for displaying the homepage.
*
* Template Name: TM-Fixture
* Template Post Type: fixture
*
* @package TM
*/

get_header(); ?>

<div id="primary" class="content-area content-area-right-sidebar">
	<main id="main" class="site-main" role="main">
		<div class="fixture-widgets">
			<?php if( is_active_sidebar( 'tm-fixture-main-1' ) ) : ?>
				<?php dynamic_sidebar( 'tm-fixture-main-1' ); ?>
			<?php endif; ?>
			<?php
			if (have_posts()):
				while (have_posts()) : the_post();

        $post = get_post();
				$team = tm_fixture_get_team( $post->ID )->name;
				$opposition = tm_fixture_get_opposition( $post->ID );
				$scorefor = tm_fixture_get_scorefor( $post->ID );
				$scoreagainst = tm_fixture_get_scoreagainst( $post->ID );

        echo __( '<h1>' . $team . ' v ' . $opposition . '</h1>');
        echo __( '<h3>' . $scorefor . ' - ' . $scoreagainst . '</h3>');

				$match_report = tm_fixture_get_matchreport( $post->ID );
				if ($match_report != '') {
					echo __( '<h3>Match Report</h3>', 'tm');
					echo $match_report;
				}

				echo __( '<h3>Description</h3>', 'tm');
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
	<?php if( is_active_sidebar( 'tm-fixture-side-1' ) ) : ?>
		<?php dynamic_sidebar( 'tm-fixture-side-1'); ?>
	<?php endif; ?>
</div><!-- #secondary -->

<?php get_footer(); ?>
