<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Yelp_Review
 * @subpackage WP_Yelp_Review/admin/partials
 */
 
     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
	$dbmsg = "";
	$html="";
	$currentreview= new stdClass();
	$currentreview->id="";
	$currentreview->rating="";
	$currentreview->review_text="";
	$currentreview->reviewer_name="";
	$currentreview->created_time="";
	$currentreview->created_time_stamp="";
	$currentreview->userpic="";
	$currentreview->review_length="";
	$currentreview->type="";

//db function variables
global $wpdb;
$table_name = $wpdb->prefix . 'wpyelp_reviews';
$rowsperpage = 20;

	//form updating here---------------------------
	if(isset($_GET['taction'])){
		$rid = htmlentities($_GET['rid']);
		//for updating
		if($_GET['taction'] == "edit" && $_GET['rid'] > 0){
			//security
			check_admin_referer( 'tedit_');
			//get form array
			$currentreview = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$rid );
		}
		
	}
	//------------------------------------------
	

	//form posting here--------------------------------
	//check to see if form has been posted.
	//if template id present then update database if not then insert as new.

	if (isset($_POST['wpyelp_submitreviewbtn'])){
		//verify nonce wp_nonce_field( 'wpyelp_save_review');
		check_admin_referer( 'wpyelp_save_review');
		
		//get form submission values and then save or update
		$r_id = htmlentities($_POST['editrid']);
		$rating = htmlentities($_POST['wpyelp_nr_rating']);
		$text = htmlentities($_POST['wpyelp_nr_text']);
		$name = htmlentities($_POST['wpyelp_nr_name']);
		$avatar_url = htmlentities($_POST['wpyelp_nr_avatar_url']);
		$rdate = htmlentities($_POST['wpyelp_nr_date']);
		$time = strtotime($rdate);
		$newdateformat = date('Y-m-d H:i:s',$time);
		$review_length = str_word_count($text);
		//santize
		$rating = sanitize_text_field( $rating );
		$text = sanitize_text_field( $text );
		$name = sanitize_text_field( $name );
		$avatar_url = sanitize_text_field( $avatar_url );
		//insert or update
			$data = array( 
				'rating' => "$rating",
				'review_text' => "$text",
				'reviewer_name' => "$name",
				'created_time' => "$newdateformat",
				'created_time_stamp' => "$time",
				'userpic' => "$avatar_url",
				'review_length' => "$review_length",
				'type' => "Manual",
				);
			$format = array( 
					'%d',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s',
					'%d',
					'%s'
				); 

		if($r_id==""){
			//insert
			$wpdb->insert( $table_name, $data, $format );
		} else {
			//update
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $r_id ), $format, array( '%d' ));
			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wpyelp_message" class="updated settings-error notice is-dismissible">'.__('<p><strong>Review Updated!</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-yelp-review-slider').'</div>';
			}
		}
	}
?>
<div class="wrap wp_yelp-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 
include("tabmenu.php");

				$urltrimmedtemp = remove_query_arg( array('taction', 'rid') );
				$tempdownloadbtn =  add_query_arg(  array(
					'taction' => 'downloadallrevs'
					),$urltrimmedtemp);
				$url_tempdownbtn = wp_nonce_url( $tempdownloadbtn, 'tdownloadrevs_');
?>
<div class="wpyelp_margin10">
	<a id="wpyelp_helpicon" class="wpyelp_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wpyelp_removeallbtn" class="button dashicons-before dashicons-no"><?php _e('Remove All Reviews', 'wp-yelp-review-slider'); ?></a>
	<a id="wpyelp_addnewreviewbtn" class="button dashicons-before dashicons-plus-alt"><?php _e('Manually Add Review', 'wp-yelp-review-slider'); ?></a>
	<a href="<?php echo $url_tempdownbtn;?>" class="button dashicons-before dashicons-download"><?php _e('Download CSV File of Reviews', 'wp-yelp-review-slider'); ?></a>
</div>

<div class="wpyelp_margin10" id="wpyelp_new_review">
<form name="newreviewform" id="newreviewform" action="?page=wp_yelp-reviews" method="post" onsubmit="return validateForm()">
	<table class="form-table ">
		<tbody>
			<tr class="wpyelp_row">
				<th scope="row">
					<?php _e('Review Rating (1 - 5):', 'wp-yelp-review-slider'); ?>
				</th>
				<td><div id="divtemplatestyles">

					<input type="radio" name="wpyelp_nr_rating" id="wpyelp_nr_rating1-radio" value="1" <?php if($currentreview->rating=="1"){echo "checked";}?>>
					<label for="wpyelp_template_type1-radio"><?php _e('1', 'wp-yelp-review-slider'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<input type="radio" name="wpyelp_nr_rating" id="wpyelp_nr_rating2-radio" value="2" <?php if($currentreview->rating=="2"){echo "checked";}?>>
					<label for="wpyelp_template_type2-radio"><?php _e('2', 'wp-yelp-review-slider'); ?></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wpyelp_nr_rating" id="wpyelp_nr_rating3-radio" value="3" <?php if($currentreview->rating=="3"){echo "checked";}?>>
					<label for="wpyelp_template_type2-radio"><?php _e('3', 'wp-yelp-review-slider'); ?></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wpyelp_nr_rating" id="wpyelp_nr_rating4-radio" value="4" <?php if($currentreview->rating=="4"){echo "checked";}?>>
					<label for="wpyelp_template_type2-radio"><?php _e('4', 'wp-yelp-review-slider'); ?></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wpyelp_nr_rating" id="wpyelp_nr_rating5-radio" value="5" <?php if($currentreview->rating=="5" || $currentreview->rating==""){echo "checked";}?>>
					<label for="wpyelp_template_type2-radio"><?php _e('5', 'wp-yelp-review-slider'); ?></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					</div>

				</td>
			</tr>
			<tr class="wpyelp_row">
				<th scope="row">
					<?php _e('Review Text:', 'wp-yelp-review-slider'); ?>
				</th>
				<td>
					<textarea name="wpyelp_nr_text" id="wpyelp_nr_text" cols="50" rows="4"><?php echo $currentreview->review_text; ?></textarea>
				</td>
			</tr>
			<tr class="wpyelp_row">
				<th scope="row">
					<?php _e('Reviewer Name:', 'wp-yelp-review-slider'); ?>
				</th>
				<td>
					<input id="wpyelp_nr_name" data-custom="custom" type="text" name="wpyelp_nr_name" placeholder="" value="<?php echo $currentreview->reviewer_name; ?>" required>
					<span class="description">
					<?php _e('Enter the name of the person who wrote this review.', 'wp-yelp-review-slider'); ?>		</span>
				</td>
			</tr>
			<tr class="wpyelp_row">
				<th scope="row">
					<?php _e('Reviewer Pic URL:', 'wp-yelp-review-slider'); ?>
				</th>
				<td>
					<input id="wpyelp_nr_avatar_url" data-custom="custom" type="text" name="wpyelp_nr_avatar_url" placeholder="" value="<?php if($currentreview->userpic!=""){echo $currentreview->userpic; } else {echo plugin_dir_url( __FILE__ ) . 'fb_profile.jpg';} ?>"> <a id="upload_avatar_button" class="button"><?php _e('Upload', 'wp-yelp-review-slider'); ?></a>
					<span class="description">
					<?php _e('Avatar for the person who wrote the review.', 'wp-yelp-review-slider'); ?>		</span>
					</br>
					<img class="wpyelp_margin10" height="100px" id="avatar_preview" src="<?php if($currentreview->userpic!=""){echo $currentreview->userpic; } else {echo plugin_dir_url( __FILE__ ) . 'fb_profile.jpg';} ?>">
				</td>
			</tr>
			<tr class="wpyelp_row">
				<th scope="row">
					<?php _e('Review Date:', 'wp-yelp-review-slider'); ?>
				</th>
				<td>
					<input id="wpyelp_nr_date" data-custom="custom" type="text" name="wpyelp_nr_date" placeholder="" value="<?php if($currentreview->created_time!=""){echo $currentreview->created_time; } else {echo date("Y-m-d H:i:s");} ?>" required>
				</td>
			</tr>
		</tbody>
	</table>
	<?php 
	//security nonce
	wp_nonce_field( 'wpyelp_save_review');
	?>
	<input type="hidden" name="editrid" id="editrid"  value="<?php echo $currentreview->id; ?>">
	<input type="submit" name="wpyelp_submitreviewbtn" id="wpyelp_submitreviewbtn" class="button button-primary" value="<?php _e('Save Review', 'wp-yelp-review-slider'); ?>">
	<a id="wpyelp_addnewreview_cancel" class="button button-secondary"><?php _e('Cancel', 'wp-yelp-review-slider'); ?></a>
</form>
</div>

<?php 

	//remove all, first make sure they want to remove all
	if(isset($_GET['opt']) && $_GET['opt']=="delall"){
		$delete = $wpdb->query("TRUNCATE TABLE `".$table_name."`");
	}
	
	//pagenumber
	if(isset($_GET['pnum'])){
	$temppagenum = $_GET['pnum'];
	} else {
	$temppagenum ="";
	}
	if ( $temppagenum=="") {
		$pagenum = 1;
	} else if(is_numeric($temppagenum)){
		$pagenum = intval($temppagenum);
	}
	
	if(!isset($_GET['sortdir'])){
		$_GET['sortdir'] = "";
	}
	if ( $_GET['sortdir']=="" || $_GET['sortdir']=="DESC") {
		$sortdirection = "&sortdir=ASC";
	} else {
		$sortdirection = "&sortdir=DESC";
	}
	$currenturl = remove_query_arg( 'sortdir' );
	
	//make sure sortby is valid
	if(!isset($_GET['sortby'])){
		$_GET['sortby'] = "";
	}
	$allowed_keys = ['created_time_stamp', 'reviewer_name', 'rating', 'review_length', 'pagename', 'type' , 'hide'];
	$checkorderby = sanitize_key($_GET['sortby']);
	
		if(in_array($checkorderby, $allowed_keys, true) && $_GET['sortby']!=""){
			$sorttable = $_GET['sortby']. " ";
		} else {
			$sorttable = "created_time_stamp ";
		}
		if($_GET['sortdir']=="ASC" || $_GET['sortdir']=="DESC"){
			$sortdir = $_GET['sortdir'];
		} else {
			$sortdir = "DESC";
		}
		unset($sorticoncolor);
		for ($x = 0; $x <= 10; $x++) {
			$sorticoncolor[$x]="";
		} 
		if($sorttable=="hide "){
			$sorticoncolor[0]="text_green";
		} else if($sorttable=="reviewer_name "){
			$sorticoncolor[1]="text_green";
		} else if($sorttable=="rating "){
			$sorticoncolor[2]="text_green";
		} else if($sorttable=="created_time_stamp "){
			$sorticoncolor[3]="text_green";
		} else if($sorttable=="review_length "){
			$sorticoncolor[4]="text_green";
		} else if($sorttable=="pagename "){
			$sorticoncolor[5]="text_green";
		} else if($sorttable=="type "){
			$sorticoncolor[6]="text_green";	
		}
		
		$html .= '
		<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="60px" sortdir="DESC" sorttype="hide" class="wpyelp_tablesort manage-column"><i class="dashicons dashicons-sort '.$sorticoncolor[0].'" aria-hidden="true"></i> '.__('Hide', 'wp-yelp-review-slider').'</th>
					<th scope="col" width="50px" class="manage-column">'.__('Pic', 'wp-yelp-review-slider').'</th>
					<th scope="col" style="min-width:70px" sortdir="DESC" sorttype="name" class="wpyelp_tablesort manage-column"><i class="dashicons dashicons-sort '.$sorticoncolor[1].'" aria-hidden="true"></i> '.__('Name', 'wp-yelp-review-slider').'</th>
					<th scope="col" width="70px" sortdir="DESC" sorttype="rating" class="wpyelp_tablesort manage-column"><i class="dashicons dashicons-sort '.$sorticoncolor[2].'" aria-hidden="true"></i> '.__('Rating', 'wp-yelp-review-slider').'</th>
					<th scope="col" class="manage-column">'.__('Review Text', 'wp-yelp-review-slider').'</th>
					<th scope="col" width="75px" sortdir="DESC" sorttype="stime" class="wpyelp_tablesort manage-column"><i class="dashicons dashicons-sort '.$sorticoncolor[3].'" aria-hidden="true"></i> '.__('Date', 'wp-yelp-review-slider').'</th>
					<th scope="col" width="70px" sortdir="DESC" sorttype="stext" class="wpyelp_tablesort manage-column" ><i class="dashicons dashicons-sort '.$sorticoncolor[4].'" aria-hidden="true"></i> '.__('Length', 'wp-yelp-review-slider').'</th>
					<th scope="col" width="100px" sortdir="DESC" sorttype="pagename" class="wpyelp_tablesort manage-column"><i class="dashicons dashicons-sort '.$sorticoncolor[5].'" aria-hidden="true"></i> '.__('Page Name', 'wp-yelp-review-slider').'</th>
					<th scope="col" width="80px" sortdir="DESC" sorttype="type" class="wpyelp_tablesort manage-column"><i class="dashicons dashicons-sort '.$sorticoncolor[6].'" aria-hidden="true"></i> '.__('Type', 'wp-yelp-review-slider').'</th>
				</tr>
				</thead>
				<thead>
				<tr id="wpyelp_searchbar">
					<th scope="col" class="manage-column" colspan="9"><span class="dashicons dashicons-search" style="font-size: 30px;"></span>
					<input id="wpyelp_filter_table_name" type="text" name="wpyelp_filter_table_name" placeholder="Enter Search Text" >
					<select name="wpyelp_filter_table_min_rating" id="wpyelp_filter_table_min_rating">
					<option value="0" >'.__('All', 'wp-yelp-review-slider').'</option>
					  <option value="1" >'.__('1 Star', 'wp-yelp-review-slider').'</option>
					  <option value="2" >'.__('2 Star', 'wp-yelp-review-slider').'</option>
					  <option value="3" >'.__('3 Star', 'wp-yelp-review-slider').'</option>
					  <option value="4" >'.__('4 Star', 'wp-yelp-review-slider').'</option>
					  <option value="5" >'.__('5 Star', 'wp-yelp-review-slider').'</option>
					</select>
					</th>
				</tr>
			</thead>';
$html .= '<tbody id="review_list">';		
			
		//get reviews from db
		$lowlimit = ($pagenum - 1) * $rowsperpage;
		$tablelimit = $lowlimit.",".$rowsperpage;
		$reviewsrows = $wpdb->get_results(
			$wpdb->prepare("SELECT * FROM ".$table_name."
			WHERE id>%d
			ORDER BY ".$sorttable." ".$sortdir." 
			LIMIT ".$tablelimit." ", "0")
		);
		//total number of rows
		$reviewtotalcount = $wpdb->get_var( 'SELECT COUNT(*) FROM '.$table_name );
		//total pages
		$totalpages = ceil($reviewtotalcount/$rowsperpage);
		
		if($reviewtotalcount>0){
			foreach ( $reviewsrows as $reviewsrow ) 
			{
				//remove query args we just used
				$urltrimmed = remove_query_arg( array('taction', 'rid') );
				$tempeditbtn =  add_query_arg(  array(
					'taction' => 'edit',
					'rid' => "$reviewsrow->id",
					),$urltrimmed);
					
				$url_tempeditbtn = wp_nonce_url( $tempeditbtn, 'tedit_');
		
				//hide link
				if($reviewsrow->hide!="yes"){
					$hideicon = '<i title="Shown" class="hiderevbtn dashicons dashicons-visibility text_green" aria-hidden="true"></i>';
				} else {
					$hideicon = '<i title="Hidden" class="hiderevbtn dashicons dashicons-hidden" aria-hidden="true"></i>';
				}
				
				//user profile link
				if($reviewsrow->type=="Facebook"){
					//user image
					$userpic = '<img style="-webkit-user-select: none;width: 50px;" src="https://graph.facebook.com/'.$reviewsrow->reviewer_id.'/picture?type=square">';
					$profilelink = "http://facebook.com/".$reviewsrow->reviewer_id;
					$editdellink = '';
				} else if( $reviewsrow->type=="Yelp"){
					$userpic = '<img style="-webkit-user-select: none;width: 50px;" src="'.$reviewsrow->userpic.'">';
					$editdellink = '';
				}else {
					$userpic = '<img style="-webkit-user-select: none;width: 50px;" src="'.$reviewsrow->userpic.'">';
					$editdellink = '<a title="Edit" href="'.$url_tempeditbtn.'"><span class="reveditbtn dashicons dashicons-edit"></span></a><span title="Delete" class="revdelbtn text_red dashicons dashicons-trash"></span>';
					
				}
				if(isset($profilelink)){
					$userpic = '<a href="'.$profilelink.'" target=_blank>'.$userpic.'</a>';
				}
	
				$html .= '<tr id="'.$reviewsrow->id.'">
						<th scope="col" class="manage-column">'.$hideicon.''.$editdellink.'</th>
						<th scope="col" class="wprev_row_userpic manage-column">'.$userpic.'</th>
						<th scope="col" class="wprev_row_reviewer_name manage-column">'.stripslashes($reviewsrow->reviewer_name).'</th>
						<th scope="col" class="wprev_row_rating manage-column">'.$reviewsrow->rating.'</th>
						<th scope="col" class="wprev_row_review_text manage-column">'.stripslashes($reviewsrow->review_text).'</th>
						<th scope="col" class="wprev_row_created_time manage-column">'.$reviewsrow->created_time.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->review_length.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->pagename.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->type.'</th>
					</tr>';
			}
		} else {
				$html .= '<tr>
						<th colspan="9" scope="col" class="manage-column">'.__('No reviews found. Please visit the <a href="?page=wp_yelp-get_yelp">Get Yelp Reviews</a> page to retrieve reviews from Yelp.', 'wp-yelp-review-slider').'</th>
					</tr>';
		}					
				
				
		$html .= '</tbody>
		</table>';
		
		//pagination bar
		$html .= '<div id="wpyelp_review_list_pagination_bar">';
		$currenturl = remove_query_arg( 'pnum' );
		for ($x = 1; $x <= $totalpages; $x++) {
			if($x==$pagenum){$blue_grey = "blue_grey";} else {$blue_grey ="";}
			$html .= '<a href="'.esc_url( add_query_arg( 'pnum', $x,$currenturl ) ).'" class="button '.$blue_grey.'">'.$x.'</a>';
		}
		
		$html .= '</div>';
				
		$html .= '</div>';		
 
 echo $html;
?>
	<div id="popup_review_list" class="popup-wrapper wpyelp_hide">
	  <div class="popup-content">
		<div class="popup-title">
		  <button type="button" class="popup-close">&times;</button>
		  <h3 id="popup_titletext"></h3>
		</div>
		<div class="popup-body">
		  <div id="popup_bobytext1"></div>
		  <div id="popup_bobytext2"></div>
		</div>
	  </div>
	</div>