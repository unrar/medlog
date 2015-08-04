<?php
/* Trip Report Generator, part of MedLog.
   This file displays all the entries belonging to a date, waits for input on which entries to 
   include to the report and then generates it. */
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

// Get the username and generate its entries controller
$sess_user = $_SESSION[$u->GetLoginSessionVar()];
$e = new EntriesController($sess_user);

// Are we done yet?
$done = $_POST["done"];

if ($done == "no")
{
  // No, so let's display all the entries belonging to this date.
  $date = $_POST["date"];
  $dry->build_header("Generate Trip Report");
  ?>
  <h1>Generate Trip Report</h1>
  <div id="content">
    <p class="warning">Please select the entries you want to include in this trip report</p>
  <?php
    $res = $e->FindEntries(null, $date);
    if ($res != false) 
    {
      $dry->build_trg_table_header();
      while ($row = $res->fetch_assoc())
      {
        $dry->build_trg_table_row($row);
      }
      $dry->build_trg_table_footer();
    }
    echo "</div>";
    $dry->build_links();
    $dry->build_footer();

} else if ($done == "yes")
{
  $included = $_POST["included"]; // All included entries IDs
  $included = array_reverse($included); // Reverse because we want them ascending

  $dry->build_header("Trip Report");
  ?>
  <h1>Trip Report</h1>
  <div id="content">
    <p class="warning">Your trip report is shown below.</p>
  <?php
    /* Start trip report generator logic */
    $tripify = new Tripify($sess_user, $included);
    $tripify->generate_html();

    
    $ids = implode(",", $included);
    $download_html = "\"download.php?format=html&ids=" . $ids. "\"";
    $download_txt = "\"download.php?format=txt&ids=" .  $ids. "\"";
  ?>

    <p><a href=<?php echo $download_html; ?>>Download HTML</a> | <a href=<?php echo $download_txt ?>>Download TXT</a></p>
  <?php
    echo "</div>";
    $dry->build_links();
    $dry->build_footer();
}