<?php
/* This is PHP-generated JavaScript.
 * This file must be linked BEFORE medlog.js, as it declares 
 * some variables used by it. */
header("content-type: application/x-javascript");
require_once("../include/config.php");
$s = new Settings();
?>
var apiURL = "<?php echo $s->config["siteurl"] . "/api/api.php"; ?>";