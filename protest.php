<?php
/*
Plugin Name: xwolf Protest Page
Plugin URI: http://piratenkleider.xwolf.de/plugins/xwolf-protest-page
Description: Black Day Plugin for protest days like the world day against cyber censorship 2013. Plugin enables a dark colored notice. 
Tags:  Internet Censorship, Worldday 2013, cyber censorship, protest, black day
Plugin URI: https://github.com/xwolfde/xwolf-protest-page
Version: 1.0.4
Author: xwolf
Author URI: http://blog.xwolf.de
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
/*  Copyright 2013  xwolf  ( http://blog.xwolf.de)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/    
 

/**
 * Constants 
 */
define("XW_PROTEST_URL", 'http://www.piratenpartei.de/2013/03/07/welttag-gegen-internetzensur-2013/');
define("XW_PROTEST_IMAGE", 'protest.png');
define("XW_PROTEST_TITLE", 'Welttag gegen Internetzensur <br>12.03.2013');
define("XW_PROTEST_LONGTEXT", 'Wir zeigen uns solidarisch mit allen durch Überwachung und Zensur eingeschränkten Journalisten und Aktivisten weltweit. Die Organisationen Reporter ohne Grenzen und die Piratenpartei rufen am Welttag gegen Internetzensur zu Protesten auf. ');
define("XW_PROTEST_MORE", 'Weitere Informationen bei <a href="http://www.reporter-ohne-grenzen.de/">Reporter ohne Grenzen</a>. <a href="http://wiki.piratenpartei.de/Welttag-gegen-Internetzensur-2013">Informationen, sowie Plugins und Banner</a> zur Teilnahme finden sich auf dem Wiki der Piratenpartei Deutschland.');

define("XW_PROTEST_usecookie", false);
define("XW_PROTEST_cookiename", 'seen_worldday2013');

define("XW_TESTMODE", false);
define("XW_PROTEST_date", '2013-03-12');
define("XW_PROTEST_timestart",  8);
define("XW_PROTEST_timeend",  20);
define("XW_PROTEST_timezone", null);

/**
 * Retrieve the options
 */
function xw_get_options() {
	$xw_protest_opts = get_option( 'xw_protest', XW_PROTEST_date );
	if ( ! is_array( $xw_protest_opts ) )
		$xw_protest_opts = array( 'protest_date' => $xw_protest_opts );
	
	$xw_protest_opts = array_merge( array(
		'protest_date'	    => XW_PROTEST_date,
		'protest_timestart' => XW_PROTEST_timestart,
		'protest_timeend'   => XW_PROTEST_timeend,
		'protest_timezone'  => XW_PROTEST_timezone,
		'usecookie'	    => XW_PROTEST_usecookie,
		'cookiename'        => XW_PROTEST_cookiename,
		'mainurl'	    => XW_PROTEST_URL,
		'image'		    => XW_PROTEST_IMAGE,
		'title'		    => XW_PROTEST_TITLE,
		'longtext'	    => XW_PROTEST_LONGTEXT,
		'moretext'	    => XW_PROTEST_MORE,
	), $xw_protest_opts );
	
	return $xw_protest_opts;
}


function xw_protest_init() {
	$xw_protest_path = plugin_dir_url( __FILE__ );
        $xw_opts = xw_get_options();
	
	 if (xw_protest_checkdate()) {
	    if ( xw_see_check() ) { 				
		    wp_register_style( 'xw_protest_css', $xw_protest_path . 'protest.css' );
		    wp_enqueue_style( 'xw_protest_css' );
                    wp_enqueue_script('jquery');            
		    add_action('wp_footer', 'xw_protest_footercode');
                    if ( $xw_opts['usecookie'] )
				setcookie( $xw_opts['cookiename'], 1, 0, '/' );
		
	    } 
	 }
 //       load_plugin_textdomain('xw-protest', '', dirname(plugin_basename(__FILE__)) . '/lang' ); 
}
add_action( 'init', 'xw_protest_init' );


function xw_see_check() {
    $xw_opts = xw_get_options();
    if (is_admin()) {
        return false;
    }    
    if (XW_TESTMODE) {
	    return true;
    }
    if ( isset( $_COOKIE ) && array_key_exists( $xw_opts['cookiename'], $_COOKIE ) && ($xw_opts['usecookie']) ) {		
	    return false;
    }

    return true;
}
function xw_protest_checkdate(){
     $xw_opts = xw_get_options();
	if(XW_PROTEST_timezone){
		date_default_timezone_set($xw_opts['protest_timezone']);
	}
	$toreturn = false;
	if (XW_TESTMODE) {
	    return true;
	}
	if(date('Y-m-d')== $xw_opts['protest_date']){
		if(date('H')>=$xw_opts['protest_timestart'] && date('H')<$xw_opts['protest_timeend']){
			$toreturn = true;
		}
	}
	return $toreturn;
}


function xw_protest_footercode() {
    $xw_opts = xw_get_options();
    $xw_protest_path = plugin_dir_url( __FILE__ );
	echo '<script type="text/javascript">
	/* <![CDATA[ */ 
        ';
	echo 'jQuery(document).ready(function ($) {  $(\'body\').append($(
        \'<div id=\"protest\"><div><a href=\"#\" class=\"close\" tabindex=\"1\">X</a>\' + ';
	if ($xw_opts['image']) {
	    echo '\'<p>';
	    if ($xw_opts['mainurl']) { echo '<a class=\"link\" href=\"'.$xw_opts['mainurl'].'\">'; }
	    echo '<img src=\"';
	    if (strpos($xw_opts['image'], 'http') === false) {
		echo $xw_protest_path;
	    } 
	    echo $xw_opts['image'];	
	    
	    echo '\"';
	    if ($xw_opts['title']) { echo ' alt=\"'.$xw_opts['title'].'\"'; }
	    if ($xw_opts['longtext']) { echo ' longdesc=\"'.$xw_opts['longtext'].'\"'; }
	    echo '>';
	    if ($xw_opts['mainurl']) { echo '</a>'; }
	    echo '</p>\' + ';
	} else {
	    if ($xw_opts['title']) { 
		echo '\'<h1>';
		if ($xw_opts['mainurl']) { echo '<a class=\"link\" href=\"'.$xw_opts['mainurl'].'\">'; }
		echo $xw_opts['title'];
		if ($xw_opts['mainurl']) { echo '</a>'; }
		echo '</h1>'; 
		echo '\' + ';
	    }	    
	    if ($xw_opts['longtext']) { echo '\'<p>'.$xw_opts['longtext'].'</p>\' + '; } 
	}
	
        if ($xw_opts['moretext']) { echo '\'<p class=\"more\">'.$xw_opts['moretext'].'</p>\' + '; } 
            
        echo '\'</div>';
        echo '</div>\' )); ';
        echo '

    $(\'#protest\').css(\'height\', $(window).height());

    $(\'#protest .close\').click(function () {
    	$(\'#protest\').fadeOut();
    	return false;
    });
    if ($(window).width() >= 600) {
	$(\'#protest\').fadeIn();
    }
});

$(window).bind(\'resize\', function(){
 	$(\'#protest\').css(\'height\', $(window).height());
});

    ';
    
	echo '/* ]]> */
      </script> ';
}



/**
 * Add options page to the administration menu
 */
function xw_add_options_page() {
	add_submenu_page( 'options-general.php',
		__( 'Protest Page Options' , 'xw-protest-plugin'),
		__( 'Protest Page Options' , 'xw-protest-plugin'), 
		'manage_options', 'xw_options_page', 'xw_options_page_callback' );
	add_action( 'admin_init', 'register_xw_options' );
}
add_action( 'admin_menu', 'xw_add_options_page' );

/**
 * Whitelist the SOPA options and set up the options page
 */
function register_xw_options() {
	register_setting( 'xw_options_page', 'xw_protest', 'sanitize_xw_opts' );
	add_settings_section( 'xw_options_section', 
		__( 'Protest Page' , 'xw-protest-plugin'), 'xw_options_section_callback', 'xw_options_page' );
	
	add_settings_field( 'protest_date', __( 'Blackout date:' , 'xw-protest-plugin'),
		'xw_options_field_callback', 'xw_options_page', 'xw_options_section', 
		array( 'label_for' => 'xw_protest_date', 'field_name' => 'protest_date' ) );

	add_settings_field( 'protest_timestart', __( 'Start time:' , 'xw-protest-plugin'),
		'xw_options_field_callback', 'xw_options_page', 'xw_options_section', 
		array( 'label_for' => 'xw_protest_timestart', 'field_name' => 'protest_timestart' ) );

	add_settings_field( 'protest_timeend', __( 'Ending time:' , 'xw-protest-plugin'),
		'xw_options_field_callback', 'xw_options_page', 'xw_options_section', 
		array( 'label_for' => 'xw_protest_timeend', 'field_name' => 'protest_timeend' ) );

	add_settings_field( 'usecookie', 
		__( 'Use cookies' ,'xw-protest-plugin'), 
		'xw_options_field_callback', 'xw_options_page', 'xw_options_section',
		array( 'label_for' => 'xw_usecookie', 'field_name' => 'usecookie' ) );

	add_settings_field( 'mainurl', 
		__( 'Link to an information page' , 'xw-protest-plugin'),
		'xw_options_field_callback', 'xw_options_page', 'xw_options_section',
		array( 'label_for' => 'xw_mainurl', 'field_name' => 'mainurl' ) );

	add_settings_field( 'image', 
		__( 'Image' , 'xw-protest-plugin'),
		'xw_options_field_callback', 'xw_options_page', 'xw_options_section',
		array( 'label_for' => 'xw_image', 'field_name' => 'image' ) );

	add_settings_field( 'title', 
		__( 'Title' , 'xw-protest-plugin'),
		'xw_options_field_callback', 'xw_options_page', 'xw_options_section',
		array( 'label_for' => 'xw_title', 'field_name' => 'title' ) );

	add_settings_field( 'longtext', 
		__( 'Information text' , 'xw-protest-plugin'),
		'xw_options_field_callback', 'xw_options_page', 'xw_options_section',
		array( 'label_for' => 'xw_longtext', 'field_name' => 'longtext' ) );
	
	add_settings_field( 'moretext', 
		__( 'Additional information text' , 'xw-protest-plugin'),
		'xw_options_field_callback', 'xw_options_page', 'xw_options_section',
		array( 'label_for' => 'xw_moretext', 'field_name' => 'moretext' ) );	
}

/**
 * Sanitize the updated SOPA options
 * @param array $input the value of the options
 * @return array the sanitized values
 */
function sanitize_xw_opts( $input ) {
	$input['protest_date'] = esc_attr( stripslashes( $input['protest_date'] ) );
	$input['usecookie'] = array_key_exists( 'usecookie', $input ) && ( '1' === $input['usecookie'] || 1 === $input['usecookie'] ) ? 1 : 0;
	$input['mainurl'] = esc_attr( stripslashes( $input['mainurl'] ) );
	$input['image'] = esc_attr( stripslashes( $input['image'] ) );
	$input['title'] = stripslashes(  $input['title']  );
	$input['longtext'] =   stripslashes($input['longtext'] );
	$input['moretext'] =   stripslashes($input['moretext'] );
	$input['protest_timeend'] = (int)$input['protest_timeend'];
	
	if ($input['protest_timeend']>23) {
	    $input['protest_timeend'] =23;
	}
	$input['protest_timestart'] = (int)$input['protest_timestart'];
	if (($input['protest_timestart']>23) || ($input['protest_timestart']>$input['protest_timeend'])) {
	    $input['protest_timestart'] =0;
	}
	$input['cookiename'] = XW_PROTEST_cookiename . '_'. md5( time() );
	
	return $input;
}

/**
 * Output the options page HTML
 */
function xw_options_page_callback() {
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( 'You do not have sufficient permissions to view this page.' );
?>
<div class="wrap">
	<h2><?php _e( 'xwolf Protest Page Options' , 'xw-protest-plugin') ?></h2>
    <form method="post" action="options.php">
    <?php settings_fields( 'xw_options_page' ) ?>
    <?php do_settings_sections( 'xw_options_page' ) ?>
    <p><input type="submit" class="button-primary" value="<?php _e( 'Save Changes' , 'xw-protest-plugin') ?>"/></p>
    </form>
</div>
<?php
}

/**
 * Output the message to be displayed at the top of the options section
 */
function xw_options_section_callback() {
	_e( '<p>Please choose the date on which you would like the notice for protest to occur.</p>' , 'xw-protest-plugin');
	_e( '<p><em>Saving these options will reset all of the  cookies, so visitors will see the message again even if they have already seen it.</em></p>' , 'xw-protest-plugin');
}

/**
 * Output the HTML for the options form elements
 */
function xw_options_field_callback( $args ) {
	$xw_opts = xw_get_options();
	
	switch ( $args['field_name'] ) {
		case 'protest_date':
?>
	<input class="widefat" type="text" value="<?php echo $xw_opts['protest_date'] ?>" 
	       name="xw_protest[protest_date]" id="xw_protest_date"/><br />
<em><?php _e( 'Please enter protest-date in YYYY-MM-DD format.' , 'xw-protest-plugin') ?></em>
<?php
		break;
		
		case 'usecookie':
?>
	<input type="checkbox" name="xw_protest[usecookie]" id="xw_usecookie" 
	       value="1"<?php checked( 1, $xw_opts['usecookie'] ) ?>/><br />
<em><?php _e( 'By default, after a visitor has seen the message, all other visits 
    to your site will show the regular content. 
    If you check the box above, they will see the message every time they 
    visit your site (as long as it\'s active).' , 'xw-protest-plugin') ?></em>
<?php
		break;
		case 'protest_timestart':
?>
<input type="text" length="2" maxlength="2" value="<?php echo $xw_opts['protest_timestart'] ?>" 
	       name="xw_protest[protest_timestart]" id="xw_protest_timestart"/><br />
<em><?php _e( 'Please enter the time for starting protest on the protest date (hour number).' , 'xw-protest-plugin') ?></em>
<?php


		
		break;
		case 'protest_timeend':
?>
<input type="text" length="2" maxlength="2" value="<?php echo $xw_opts['protest_timeend'] ?>" 
	       name="xw_protest[protest_timeend]" id="xw_protest_timeend"/><br />
<em><?php _e( 'Please enter the time for ending protest on the protest date (hour number).' , 'xw-protest-plugin') ?></em>
<?php
		break;
		case 'mainurl':
?>
<input class="widefat" type="text" value="<?php echo $xw_opts['mainurl'] ?>" 
	       name="xw_protest[mainurl]" id="xw_mainurl"/><br />
<em><?php _e( 'Optional URL for information page to link to.' , 'xw-protest-plugin') ?></em>
<?php

		break;
		case 'image':
?>
<input class="widefat" type="text" value="<?php echo $xw_opts['image'] ?>" 
	       name="xw_protest[image]" id="xw_image"/><br />
<em><?php _e( 'Optional Image (banner or logo). if starts with http it will be external, otherwise it must residue in plugin directory' , 'xw-protest-plugin') ?></em>
<?php

		break;
		case 'title':
?>
<input class="widefat" type="text" value="<?php echo $xw_opts['title'] ?>" 
	       name="xw_protest[title]" id="xw_title"/><br />
<em><?php _e( 'Title for protest notice (if an image is active as banner, used as alt-attribute' , 'xw-protest-plugin') ?></em>
<?php
		break;
		case 'longtext':
?>
<textarea class="widefat"  name="xw_protest[longtext]" id="xw_longtext"/><?php echo esc_textarea( $xw_opts['longtext']) ?></textarea> 
	       <br />
<em><?php _e( 'Short information text about protest' , 'xw-protest-plugin') ?></em>
<?php
		break;
		case 'moretext':
?>
<textarea class="widefat"  name="xw_protest[moretext]" id="xw_moretext"/><?php echo esc_textarea( $xw_opts['moretext']) ?></textarea> 
	       <br />
<em><?php _e( 'Additional informations for protest' , 'xw-protest-plugin') ?></em>
<?php
		
		break;	    
	}
}



/**
 * Install or update plugin
 */
function xw_protest_install() {
    /* Nothing yet */

}
/**
 * deactivate plugin
 */
function xw_protest_uninstall() {
    /* Nothing yet */
    delete_option( 'xw_protest' );
    
}

register_activation_hook(__FILE__, 'xw_progressbar_install');
register_deactivation_hook(__FILE__, 'xw_progressbar_uninstall');

