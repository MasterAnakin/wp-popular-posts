<?php
/**
 * Plugin Name:       Eat sleep - Jetpack stats
 * Plugin URI:        https://valet.io
 * Description:       Handle most popular posts on category pages
 * Version:           1.0.0
 * Author:            Milos Milosevic
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 */


function etsleep_stats(){

	if(function_exists('stats_get_csv')){
	        $popular = stats_get_csv( 'postviews', array( 'days' => 30, 'limit' => 100 ) );
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
			//echo $category->term_id;

			$i = 0;
			
	        foreach ( $popular as $p ) {
				$term_list = wp_get_post_terms( $p['post_id'], 'category', array( 'fields' => 'ids' ) );

				if ( in_array( $category->term_id, $term_list ) ) :
	        	if ( $p['post_title'] == 'Home page' || $p['post_title'] == 'Home' || $p['post_id'] == '63404') {continue;}
	        	if ( $i == 3 ) { break; }
	        	$pop_arr[] = array( 'pop_id' => $p['post_id'] );
	        	//var_dump($pop_arr);
	        	
	        	$popular_link = $p[ 'post_permalink' ];
	        	$popular_title = $p[ 'post_title' ];
		        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $p['post_id'] ), 'full' );
		        $url = $thumb['0'];
	        	?>

  				<div class="column-popular-posts" style="background: linear-gradient( rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4) ), url('<?php echo $url;?>');">
  					<h3>
  						<a href="<?php echo $popular_link;?>">
  							<?php echo $popular_title;?>
  						</a>
					</h3>
				</div>

			<?php
			$i++;
				    endif;
	        }

	        if ( count($pop_arr) == 0 ) { $count_pop = 3;}
	        if ( count($pop_arr) == 1 ) { $count_pop = 2;}
	        if ( count($pop_arr) == 2 ) { $count_pop = 1;}
	        if ( count($pop_arr) == 3 ) { $count_pop = 0;}

	        	if ( $count_pop > 0 ){
	        	    $recent_posts = wp_get_recent_posts(array(
				        'numberposts' => $count_pop, // Number of recent posts thumbnails to display
				        'post_status' => 'publish' // Show only the published posts
				    ));
				    foreach( $recent_posts as $post_item ){

			        	$popular_title = $post_item['post_title'];
				        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post_item['ID'] ), 'full' );
				        $url = $thumb['0'];
			        	?>

		  				<div class="column-popular-posts" style="background: linear-gradient( rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4) ), url('<?php echo $url;?>');">
		  					<h3>
		  						<a href="<?php echo esc_url( get_permalink($post_item['ID']) );?>">
		  							<?php echo $popular_title;?>
		  						</a>
							</h3>
						</div>
			<?php   }

			}

	        ?> 
	    </div><div style="clear:both"></div>
	    <?php      
	}
}
add_shortcode ( 'etsleep_stats','etsleep_stats' );
