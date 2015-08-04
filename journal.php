<?php
require_once("./include/usercontrol.php");
require_once("./include/entries.php");
require_once("./include/static.php");
$u = new UserControl();
$dry = new DRYHelper();
if (!$u->CheckLogin())
{
  // Not logged-in, redirect to index
  header("Location: index.php");
  die();
}
$sess_user = $_SESSION[$u->GetLoginSessionVar()];
$e = new EntriesController($sess_user);
$dry->build_header("My Journal");
 ?>
<h1>My Journal</h1>
<div id="site-info">
  <p>This is your journal, it contains all the data you've logged in MedLog.</p>
</div>
<div id="content">
  <?php
  $res = $e->FindEntries();
  if ($res != false)
  {
    $dry->build_table_header();
    //$rows = [];
    while ($row = $res->fetch_assoc())
    {
      //array_push($rows, $row);
      $dry->build_table_row($row);
    }
    echo "</table>";
    //$rows = array_reverse($rows);
    //$dry->build_table_rows($rows);
  }
$dry->build_links();
$dry->build_footer();
  ?>
