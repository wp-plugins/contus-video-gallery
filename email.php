<?php
/**
 * Email File for video player
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
$to   = filter_input( INPUT_POST, 'to', FILTER_VALIDATE_EMAIL );
$from = filter_input( INPUT_POST, 'from', FILTER_VALIDATE_EMAIL );
$url  = filter_input( INPUT_POST, 'url', FILTER_VALIDATE_URL );
$subject  = filter_input( INPUT_POST, 'Note', FILTER_SANITIZE_STRING );
$message_content =  filter_input( INPUT_POST, 'Note', FILTER_SANITIZE_STRING );
$title    = filter_input( INPUT_POST, 'title', FILTER_SANITIZE_STRING );
$referrer = parse_url( $_SERVER['HTTP_REFERER'] );
$referrer_host = $referrer['scheme'] . '://' . $referrer['host'];
$pageURL  = 'http';

if (isset( $_SERVER['HTTPS'] )&& $_SERVER['HTTPS'] == 'on' ) {
	$pageURL .= 's';
}
$pageURL .= '://';

if ( $_SERVER['SERVER_PORT'] != '80' ) {
	$pageURL .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];
} else {
	$pageURL .= $_SERVER['SERVER_NAME'];
}

if ( $referrer_host === $pageURL ) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";	
	$headers .= "From: " . "<" . $from . ">\r\n";
	$headers .= "Reply-To: " . $from . "\r\n";
	$headers .= "Return-path: " . $from;
	$username = explode('@' , $from );   
	$username = ucfirst($username['0']);
	$subject  =  $username . ' has shared a video with you.';
	$emailtemplate_path  = plugin_dir_url( __FILE__ ).'front/emailtemplate/Emailtemplate.html';	
	$message =  file_get_contents( $emailtemplate_path);
	$message = str_replace( '{subject}', $subject, $message );
	$message = str_replace( '{message}', $message_content, $message);
	$message = str_replace( '{videourl}',$url,$message );
	$message = str_replace('{username}',$username ,$message );
	if ( @mail( $to, $title, $message, $headers ) ) {
		echo 'success=sent';
	} else {
		echo 'success=error';
	}
} else {
	echo 'success=error';
}
?>