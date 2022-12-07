<?php
/**
 * Plugin Name: Wp-Popular-Posts
 * Description: Handle most popular posts on category pages
 * Plugin URI:  https://mmilosevic.com/
 * Author:      Milos Milosevic
 * Version:     1.0.1
 */
function wp_popular_posts() {

	if ( function_exists( 'stats_get_csv' ) ) {
			$popular_posts = stats_get_csv(
				'postviews',
				array(
					'days'  => 30,
					'limit' => 100,
				)
			);
			echo '
		<style>
	        .column-popular-posts {
				float: left;
			    width: 32%;
				text-align: center;
				margin-right:1%;
				height:160px;
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover !important;
			}

			@media screen and (max-width: 768px){
				.column-popular-posts {
					width: 99%;
					text-align: center;
					margin-right:1%;
					margin-bottom: 3%;
					height:210px;
				}
			}

			@media screen and (max-width: 768px){
				.column-popular-posts h3 {
					font-size: 1.8em;
				}
			}

			.column-popular-posts h3{
				font-size: 1.1em;
				margin-top: 20%;
			}
			.column-popular-posts h3 a {
				color: #fff;

			  text-decoration: none !important;
			}
			.column-popular-posts a:hover {
				color: #edb059;
			}

		</style>
		<script>
		jQuery(document).ready(function(){
			    jQuery(".column-popular-posts a").each(function(){
			        this.href = this.href.replace("s40219.p1521.sites.pressdns.com", "eatsleepcruise.com");
			    });
			});
		</script>
		<div class="row">';

		$category = get_queried_object();

			$i = 0;

		foreach ( $popular_posts as $popular_single ) {
			$term_list = wp_get_post_terms( $popular_single['post_id'], 'category', array( 'fields' => 'ids' ) );

			if ( in_array( $category->term_id, $term_list, true ) ) :
				if ( 'Home page' === $popular_single['post_title'] || 'Home' === $popular_single['post_title'] ) {
					continue;}
				// Get only 3 elements.
				if ( 3 === $i ) {
					break; }
				$pop_arr[] = array( 'pop_id' => $popular_single['post_id'] );

				$popular_link  = $popular_single['post_permalink'];
				$popular_title = $popular_single['post_title'];
				$thumb         = wp_get_attachment_image_src( get_post_thumbnail_id( $popular_single['post_id'] ), 'full' );
				$url           = $thumb['0'];
				?>

				<div class="column-popular-posts" style="background: linear-gradient( rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4) ), url('<?php echo esc_html( $url ); ?>');">
					<h3>
						<a href="<?php echo esc_html( $popular_link ); ?>">
						<?php echo esc_html( $popular_title ); ?>
						</a>
					</h3>
				</div>

				<?php
				$i++;
				endif;
		}

		if ( 0 === count( $pop_arr ) ) {
			$count_pop = 3;}
		if ( 1 === count( $pop_arr ) ) {
			$count_pop = 2;}
		if ( 2 === count( $pop_arr ) ) {
			$count_pop = 1;}
		if ( 3 === count( $pop_arr ) ) {
			$count_pop = 0;}

		if ( $count_pop > 0 ) {
			$recent_posts = wp_get_recent_posts(
				array(
					'numberposts' => $count_pop, // Number of recent posts thumbnails to display.
					'post_status' => 'publish', // Show only the published posts.
				)
			);
			foreach ( $recent_posts as $post_item ) {

				$popular_title = $post_item['post_title'];
				$thumb         = wp_get_attachment_image_src( get_post_thumbnail_id( $post_item['ID'] ), 'full' );
				$url           = $thumb['0'];
				?>

						<div class="column-popular-posts" style="background: linear-gradient( rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4) ), url('<?php echo esc_html( $url ); ?>');">
							<h3>
							<a href="<?php echo esc_url( get_permalink( $post_item['ID'] ) ); ?>">
							<?php echo esc_html( $popular_title ); ?>
							</a>
							</h3>
						</div>
				<?php
			}
		}

		?>

		</div><div style="clear:both"></div>
		<?php
	}
}
add_shortcode( 'wp_popular_posts', 'wp_popular_posts' );
