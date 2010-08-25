<?php
/*
Plugin Name: Throttle Comment Flood
Plugin URI: http://www.koikikukan.com/
Description: コメントの連続投稿を抑止します。
Version: 0.0.1
Author: Yujiro Araki
Author URI: http://www.koikikukan.com/
*/

function throttle_comment_flood($block, $time_lastcomment, $time_newcomment) {
    $opt_val = get_option( 'throttle_comment_flood' );
    if (($time_newcomment - $time_lastcomment) < $opt_val) {
        return true;
    } else {
        return false;
    }
}
add_filter('comment_flood_filter', 'throttle_comment_flood', 9, 3); 
remove_filter('comment_flood_filter', 'wp_throttle_comment_flood');


// http://wpdocs.sourceforge.jp/Adding_Administration_Menus
function throttle_comment_flood_menu() {
    add_options_page(__('Throttle Comment Options', 'wp_throttle_comment_flood'), __('Throttle Comment', 'wp_throttle_comment_flood'), 8, __FILE__, 'throttle_comment_flood_options');
}

function throttle_comment_flood_options() {
    $opt_name = 'throttle_comment_flood';
    $hidden_field_name = 'throttle_comment_flood_hidden';
    $data_field_name = 'throttle_comment_flood';

    $opt_val = get_option( $opt_name );
    if ($opt_val == '') {
        $opt_val = 15;
    }

    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        $opt_val = $_POST[ $data_field_name ];
        update_option( $opt_name, $opt_val );

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'wp_throttle_comment_flood' ); ?></strong></p></div>
<?php } ?>

<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2><?php _e( 'Throttle Comment Setting', 'wp_throttle_comment_flood' ); ?></h2>
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<table class="form-table">
<tbody>
<tr><th>
<label><?php _e("Throttle Comment Seconds:", 'wp_throttle_comment_flood' ); ?></label>
</th>
<td>
<input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="5"> <?php _e("seconds.", 'wp_throttle_comment_flood' ); ?>
</td>
</tbody>
</table>

<p class="submit">
<input class="button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'wp_throttle_comment_flood' ) ?>" />
</p>

</form>
</div>
</div>

<?php
}


add_action('admin_menu', 'throttle_comment_flood_menu');

add_action('init', 'init_textdomain');
function init_textdomain(){
    load_plugin_textdomain( 'wp_throttle_comment_flood', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

?>
