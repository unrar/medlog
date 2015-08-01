<?php
/* MedLog MANAGE Module ~~~~~ Add, Delete and Edit MedLog edits.
  Check login firstly... */
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
// Now we assume we're logged in! Get the username.
$sess_user = $_SESSION[$u->GetLoginSessionVar()];
$op = $_GET["op"];
// Start the HTML love
$dry->build_header("Manage");
?>
  <h1>MedLog MANAGE</h1>
  <div id="content">
  <?php


  // Let's try and find out what we're trying to do.
  if ($op == "add")
  {
    /* Add a new entry to the journal */
    ?>
    <h2>Add new entry</h2>
    <form id="addentry" action="result.php" method="POST">
      <fieldset>
        <label for="drug">Drug name: </label>
        <input type="text" name="drug" />
        <br />

        <label for="dose">Dose: </label>
        <input type="text" size="5" name="dose" />
        <input type="radio" name="dose_unit" value="mg" checked> mg
        <input type="radio" name="dose_unit" value="g"> g
        <input type="radio" name="dose_unit" value="oz."> oz
        <br />

        <label for="datetime">Date and time: </label>
        <input type="datetime-local" name="datetime" />
        <br />

        <label for="comment">Comments: </label>
        <input type="text" name="comment" />
        <br />

        <input type="hidden" name="op" value="add" />
        <input type="submit" value="Submit" />
      </fieldset>
    </form>
    <?php

  } else if ($op == "rem")
  {
    ?>
    <h2>Remove an entry</h2>
    <p class="warning">To find the ID of the entry you want to remove, check in your MedLog.</p>
    <form id="rem-entry" action="result.php" method="POST">
      <fieldset>
        <label for="id">Entry ID: </label>
        <input type="text" size=3 name="id" />
        <input type="hidden" name="op" value="rem" />
        <input type="submit" value="Submit" />
      </fieldset>
    </form>
    <?php
  } else if ($op == "edit")
  {
    ?>
    <h2>Edit an entry</h2>
    <p class="warning">To find the ID of the entry you want to edit, check in your MedLog.</p>
    <form id="edit-entry" action="edit.php" method="POST">
      <fieldset>
        <label for="id">Entry ID: </label>
        <input type="text" size=3 name="id" />
        <input type="submit" value="Submit" />
      </fieldset>
    </form>
    <?php
  }
echo "</div>";
$dry->build_links();
$dry->build_footer();
  ?>
