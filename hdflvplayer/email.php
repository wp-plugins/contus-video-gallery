<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: email file for player.
Version: 2.2
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

$to             = $_POST['to'];
$from           = $_POST['from'];
$url            = $_POST['url'];
$subject        = $_POST['Note'];
$headers        = "From: "."<" . $_POST['from'] .">\r\n";
$headers       .= "Reply-To: " . $_POST['from'] . "\r\n";
$headers       .= "Return-path: " . $_POST['from'];
$message        = $_POST['Note'] . "\n\n";
$message       .= "Video URL: " . $url;
if(mail($to, $subject, $message, $headers))
{
	echo "output=sent";
} else {
	echo "output=error";
}
?>