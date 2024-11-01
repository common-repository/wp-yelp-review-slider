<?php
$urltrimmedtab = remove_query_arg( array('page', '_wpnonce', 'taction', 'tid', 'sortby', 'sortdir', 'opt','settings-updated') );

$urlreviewlist = esc_url( add_query_arg( 'page', 'wp_yelp-reviews',$urltrimmedtab ) );
$urltemplateposts = esc_url( add_query_arg( 'page', 'wp_yelp-templates_posts',$urltrimmedtab ) );
$urlgetpro = esc_url( add_query_arg( 'page', 'wp_yelp-get_yelp',$urltrimmedtab ) );
$urlforum = esc_url( add_query_arg( 'page', 'wp_yelp-get_pro',$urltrimmedtab ) );
?>	
	<h2 class="nav-tab-wrapper">
	<a href="<?php echo $urlgetpro; ?>" class="nav-tab <?php if($_GET['page']=='wp_yelp-get_yelp'){echo 'nav-tab-active';} ?>"><?php _e('Get Yelp Reviews', 'wp-yelp-review-slider'); ?></a>
	<a href="<?php echo $urlreviewlist; ?>" class="nav-tab <?php if($_GET['page']=='wp_yelp-reviews'){echo 'nav-tab-active';} ?>"><?php _e('Reviews List', 'wp-yelp-review-slider'); ?></a>
	<a href="<?php echo $urltemplateposts; ?>" class="nav-tab <?php if($_GET['page']=='wp_yelp-templates_posts'){echo 'nav-tab-active';} ?>"><?php _e('Templates', 'wp-yelp-review-slider'); ?></a>
	<a href="<?php echo $urlforum; ?>" class="nav-tab <?php if($_GET['page']=='wp_yelp-get_pro'){echo 'nav-tab-active';} ?>"><?php _e('Get Pro Version', 'wp_yelp-get_pro'); ?></a>

	</h2>