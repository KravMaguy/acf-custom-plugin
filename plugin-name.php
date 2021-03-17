<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       abes map plugin
 * Plugin URI:        http://srsexteriors.com/abes-map-plugin/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            abe schur
 * Author URI:        http://srsexteriors.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new Plugin_Name();
	$plugin->run();

}
run_plugin_name();


add_shortcode('programCount', 'programCountFunction');

function programCountFunction(){
	$args = array(
		'numberposts'	=> -1,
		'post_type'		=> 'jobs',
	);
	$the_query = new WP_Query( $args );
	 if( $the_query->have_posts() ): ?>
	 <div class="map-card" style="box-shadow:0px 0px 10px rgba(0, 0, 0, 0.5);">
	<div class="acf-map">
		<?php while ($the_query->have_posts()):
		$the_query->the_post(); 
		$location = get_field('location');
		?>
		<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>">
		<div class="map-info-window">
        <h4><?php the_title(); ?></h4>
		<div class="map-details">
        <p class="map-address"><?php echo $location['address']; ?></p>
		<p class="map-phone"><a href="tel:6309959001">+1 630 995-9001</a></p>
		</div>
		<a class="btn-primary map-link" href="<?php the_permalink(); ?>">
		Read More</a>
    	</div>
		</div>
		<? endwhile; ?>
	</div>
	<div class="map-card-body">
	<div class="job-total card-title"><h3>Total Completed: <?php echo sizeof($args); ?> Jobs</h3></div>
	<p class="card-text">
      This Map displays all the jobs we have completed in the chicago land area, click on a map marker for more details, click the button within each marker to be taken to a detailed breakdown of the work completed and service provided.
    </p>
    <div class="map-footer panel-footer">
        <a class="btn btn-primary btn-panel-services map-link" href="https://srsexteriors.com/all-completed-jobs/">
		All Jobs</a>   
    </div>
	</div>
	</card>
	<?php
	endif;
	wp_reset_query();
}

add_shortcode('testLocation', 'testLocFunction');


function testLocFunction(){
	$args = array(
		'numberposts'	=> -1,
		'post_type'		=> 'jobs',
	);
	$the_query = new WP_Query( $args );
	 if( $the_query->have_posts() ): ?>
	<ul>
		<?php while ($the_query->have_posts()):
		$the_query->the_post(); ?>
		<?
		$values = get_field('location');
		$city=$values['city'];
		if ($values)
		{
			echo 'the city is: '.$city.'<br/>';
		}
	endwhile; ?>
	</ul>
	<?php
	endif;
	wp_reset_query();
}







add_shortcode('testmaps', 'testmap');

function testmap($atts){
    $atts=shortcode_atts(
        array(
            'city'=>'false'
            ),
            $atts
        );
        
    $city=strtolower($atts['city']);
    echo $city;
    
    // $msgType= (esc_attr($atts['city'])=='true')?'':'';
	$args = array(
		'numberposts'	=> -1,
		'post_type'		=> 'jobs',
	);
	$a=array();
	$b=array();

	$the_query = new WP_Query( $args );
	 if( $the_query->have_posts() ){ 
	 ?>
	 <div class="map-card" style="box-shadow:0px 0px 10px rgba(0, 0, 0, 0.5);">
	<div class="acf-map">
		<?php 
		$count=0;
		while ($the_query->have_posts()):
		$the_query->the_post(); 
		$location = get_field('location');
	    $cityToMatch=strtolower($location['city']);
        array_push($a,$cityToMatch);
        $match=($cityToMatch===$city)?'true':'false';
        array_push($b,$match);
        if($city==='false'){
			$count++
		?>
		<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>">
		<div class="map-info-window">
        <h4><?php the_title(); ?></h4>
		<div class="map-details">
        <p class="map-address"><?php echo $location['address']; ?></p>
		<p class="map-phone"><a href="tel:6309959001">+1 630 995-9001</a></p>
		</div>
		<a class="btn-primary map-link" href="<?php the_permalink(); ?>">
		Read More</a>
    	</div>
		</div>
		<? } elseif($match==="true"){ 
			$count++
			?>
				<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>">
		<div class="map-info-window">
        <h4><?php the_title(); ?></h4>
		<div class="map-details">
        <p class="map-address"><?php echo $location['address']; ?></p>
		<p class="map-phone"><a href="tel:6309959001">+1 630 995-9001</a></p>
		</div>
		<a class="btn-primary map-link" href="<?php the_permalink(); ?>">
		Read More</a>
    	</div>
		</div>
		<? } ?>
		<? endwhile; ?>
	</div>
	<div class="map-card-body">
	<div class="job-total card-title"><h3>Total Completed: <?php echo $count; ?> Jobs in <?=$city?></h3></div>
	<p class="card-text">
      This Map displays all the jobs we have completed in the chicago land area, click on a map marker for more details, click the button within each marker to be taken to a detailed breakdown of the work completed and service provided.
    </p>
    <div class="map-footer panel-footer">
        <a class="btn btn-primary btn-panel-services map-link" href="#">
		All Jobs</a>   
    </div>
	</div>
	</card>
	<?php
	print_r($a);
	echo '</br>';
	print_r($b);
	};
	wp_reset_query();
}