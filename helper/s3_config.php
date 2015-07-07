<?php
 /** Videos S3Buckets store Class file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
global $wpdb;
$query = "SELECT player_colors FROM ".$wpdb->prefix."hdflvvideoshare_settings";
$result = $wpdb->get_row($query);
$setting_res = $result->player_colors;
$dispenable = unserialize($setting_res);
$bucket = '';
if (isset($dispenable['amazonbuckets_enable']) && $dispenable['amazonbuckets_enable'] == 1)
{
	if (isset($dispenable['amazonbuckets_name']))
	{
		$bucket = $dispenable['amazonbuckets_name'];
	}

	if (!class_exists('S3'))
	{
		require_once (APPTHA_VGALLERY_BASEDIR.'/helper/s3.php');
	}

	## AWS access info
	if (!defined('awsAccessKey'))
	{
		if (isset($dispenable['amazon_bucket_access_key']))
		{
			define('awsAccessKey', $dispenable['amazon_bucket_access_key']);
		}
	}

	if (!defined('awsSecretKey'))
	{
		if (isset($dispenable['amazon_bucket_access_secretkey']))
		{
			define('awsSecretKey', $dispenable['amazon_bucket_access_secretkey']);
		}
	}
	$s3 = new S3(awsAccessKey, awsSecretKey);
	$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
}		 
?>