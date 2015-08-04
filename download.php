<?php
/* Generate a trip report and return a downloadable file */
require_once("./include/entries.php");
require_once("./include/usercontrol.php");
require_once("./include/static.php");
require_once("./include/tripify.php");
$u = new UserControl();
$dry = new DRYHelper();

if (!$u->CheckLogin())
{
  // Not logged in.
  header("Location: index.php");
  die();
}

$sess_user = $_SESSION[$u->GetLoginSessionVar()];
$e = new EntriesController($sess_user);

$ids = explode(",", $_GET["ids"]);
$tripify = new Tripify($sess_user, $ids);
if ($_GET["format"] == "html")
{
  header("Content-type: text/html");
  header("Content-Disposition: attachment; filename=trip.html");

  // HTML file to download here.
  $dry->build_header("Trip Report");
  $tripify->generate_html_header();
  $tripify->generate_html();
  $tripify->generate_html_footer();
  $dry->build_footer();
} else if ($_GET["format"] == "txt")
{
  header("Content-type: text/plain");
  header("Content-Disposition: attachment; filename=trip.txt");

  // TXT file to download here.
  $tripify->generate_txt_header();
  $tripify->generate_txt();
  $tripify->generate_txt_footer();
}
?>