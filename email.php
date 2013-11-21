<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Wordpress video gallery Featured videos widget.
  Version: 2.3.1.0.1
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
$to             = filter_input(INPUT_POST, 'to', FILTER_VALIDATE_EMAIL);
$from           = filter_input(INPUT_POST, 'from', FILTER_VALIDATE_EMAIL);
$url            = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
$subject        = filter_input(INPUT_POST, 'Note', FILTER_SANITIZE_STRING);
$title          = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$referrer       = parse_url($_SERVER['HTTP_REFERER']);
$referrer_host  = $referrer['scheme'] . '://' . $referrer['host'];
$pageURL        = 'http';

if ($_SERVER["HTTPS"] == "on") {
    $pageURL .= "s";
}
$pageURL .= "://";

if ($_SERVER["SERVER_PORT"] != "80") {
    $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
} else {
    $pageURL .= $_SERVER["SERVER_NAME"];
}

if ($referrer_host === $pageURL) {
    $headers = "From: " . "<" . $from . ">\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "Return-path: " . $from;
    $message = $subject . "\n\n";
    $message .= "Video URL: " . $url;
    if (mail($to, $title, $message, $headers)) {
        echo "output=sent";
    } else {
        echo "output=error";
    }
} else {
    echo "output=error";
}
?>