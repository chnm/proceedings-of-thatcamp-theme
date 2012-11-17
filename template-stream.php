<?php
/**
 * Stream template
 *
 * @package thatcamp
 * @since thatcamp 1.0
 *
 * Template Name: Stream Template
 */
?>
<?php get_header(); ?>

<div class="sidebar four columns alpha">
    <?php get_sidebar( 'stream' ); ?>
</div>

<div id="article" class="ten columns offset-by-two omega">
	<div id="content" class="clearfix feature-box thatcamp-stream" role="main">
		<?php if ( function_exists( 'get_sitewide_tags_option' ) ) switch_to_blog( get_sitewide_tags_option( 'tags_blog_id' ) ); ?>

		<?php
			$stream_args = array();
                        $category_name = $category_slug = '';
			if ( isset( $_GET['category'] ) ) {
                                $category_slug = urldecode( $_GET['category'] );
				$stream_args['category_name'] = $category_slug;
                                $category = get_term_by( 'slug', $category_slug, 'category' );
                                if ( $category ) {
                                        $category_name = $category->name;
                                }
                        }

			if ( isset( $_GET['per_page'] ) )
				$stream_args['posts_per_page'] = intval( $_GET['per_page'] );

                        $stream_args['paged'] = thatcamp_get_paged();
		?>

                <h1>THATCamp Stream<?php if ( $category_name ) : ?>: <?php echo esc_html( $category_name ) ?><?php endif ?></h1>
		<?php $stream_query = new WP_Query( $stream_args ) ?>
		<?php if ( $stream_query->have_posts() ) :
			while ( $stream_query->have_posts() ) : $stream_query->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-avatar">
							<span class="img-wrapper floatright"><?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?></span>
					</div>
					<div class="post-meta">
						<header class="post-header">
							<?php
								// Sitewide Tags keeps the original post permalink in postmeta
								$permalink = get_post_meta( get_the_ID(), 'permalink' );
								if ( ! $permalink ) {
									$permalink = get_permalink();
								}

								// We need some data about the source blog
								$source_blog_url = $source_blog_name = $source_blog_link = '';
								$source_blog_id = get_post_meta( get_the_ID(), 'blogid', true );
								if ( $source_blog_id ) {
									$source_blog_url  = get_blog_option( $source_blog_id, 'home', true );
									$source_blog_name = get_blog_option( $source_blog_id, 'blogname', true );
									$source_blog_link = '<a href="' . $source_blog_url . '">' . $source_blog_name . '</a>';
								}
							?>
							<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'thatcamp'), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="postlink"><?php the_title(); ?></a></h3>
						</header>
						<span class="meta-date"><?php echo get_the_date(); ?></span>
						<span class="meta-author"><?php printf( _x( 'by %s', 'Post written by...', 'thatcamp' ), bp_core_get_userlink( $post->post_author ) ); ?></span>
						<?php if ( $source_blog_link ) : ?>
							<span class="meta-source"><?php printf( _x( 'on %s', 'From the blog...', 'thatcamp' ), $source_blog_link ) ?></span>
						<?php endif ?>
						<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'thatcamp'), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="readmore postlink">Read more...</a>
					</div>
				</article><?php
			endwhile;
		      endif; ?>

		<?php restore_current_blog() ?>

		<div class="nav-previous"><?php thatcamp_next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'thatcamp' ), $stream_query->max_num_pages ); ?></div>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'thatcamp' ) ); ?></div>
		<?php endif; ?>


	</div>
</div>
<?php get_footer() ?>
