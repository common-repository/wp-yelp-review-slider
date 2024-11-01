<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Yelp_Review_Slider
 * @subpackage WP_Yelp_Review_Slider/admin/partials
 */
 
     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
?>
<div class="wrap wp_yelp-settings">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 
include("tabmenu.php");
?>
<div class="wpfbr_margin10">

<h1>Get the Pro Version of this plugin and unlock these great features!</h1>

<ul style="
    list-style-type: circle;
    margin-left: 30px;
">
	<li>Customer support via email and a forum.</li>
	<li>Download your Yelp reviews as well.</li>
	<li>Hide certain reviews from displaying.</li>
	<li>Manually add reviews to your database.</li>
	<li>Download all your reviews in CSV format to your computer.</li>
	<li>Access more Review Template styles!</li>
	<li>Advanced slider controls like: Autoplay, slide animation direction, hide navigation arrows and dots, adjust slider height for each slide.</li>
	<li>Change the minimum rating of the reviews to display. Allows you to hide low rating reviews.</li>
	<li>Use a minimum and maximum word count so you can hide short or long reviews.</li>
	<li>Add a read more link to long reviews.</li>
	<li>Only display reviews of a certain type (Facebook, Yelp, manually input).</li>
	<li>Specify which Facebook page to display reviews from per a template.</li>
	<li>Individually choose which reviews you want to display per a template.</li>
	<li>Access to all new features we add in the future!</li>
</ul>
<a href="http://ljapps.com/wp-review-slider-pro/" class="btn_green dashicons-before dashicons-external"><?php _e('Get Pro Version Here!', 'wp-fb-reviews'); ?></a>
</div>

</div>

	

