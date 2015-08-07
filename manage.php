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
      <div id="content">
      <!-- File-specific script -->
    <script>
      // Main function
        $(document).ready(function() {
          // Validate the form
          mjm.setForm("#addentry");
          mjm.watchForm();
        });
    </script>
    <form id="addentry">
      <fieldset>
        <label for="drug">Drug name: </label>
        <input type="text" name="drug" required />
        <br />

        <label for="dose">Dose: </label>
        <input type="text" size="5" name="dose" required />
        <input type="radio" name="dose_unit" value="mg" checked> mg
        <input type="radio" name="dose_unit" value="g"> g
        <input type="radio" name="dose_unit" value="oz."> oz
        <br />

        <label for="datetime">Date and time: </label>
        <input type="datetime-local" name="datetime" required value="<?php echo date("Y-m-d\TH:i"); ?>" />
        <br />

        <label for="comment">Comments: </label>
        <input type="text" name="comment" value=" " />
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
    <script>
      // Main function
        $(document).ready(function() {
          // Validate the form
          mjm.setForm("#rem-entry");
          mjm.watchForm();
        });
    </script>
    <p class="warning">To find the ID of the entry you want to remove, check in your MedLog.</p>
    <form id="rem-entry">
      <fieldset>
        <label for="id">Entry ID: </label>
        <input type="text" size=3 name="id" required/>
        <input type="hidden" name="op" value="rem" />
        <input type="submit" value="Submit" />
      </fieldset>
    </form>
    <?php
  } else if ($op == "edit")
  {
    ?>
    <h2>Edit an entry</h2>
    <script>
      // Main function
        $(document).ready(function() {
          // Validate the form
          mjm.setForm("#edit-entry");
          mjm.fetch("#edit-entry-values", sendEditForm);

          // Second form now
          function sendEditForm() {
            mjm.setForm("#edit-entry-values");
            mjm.watchForm();
          } 
        });
    </script>
    <p class="warning">To find the ID of the entry you want to edit, check in your MedLog.</p>
    <form id="edit-entry">
      <fieldset>
        <label for="id">Entry ID: </label>
        <input type="text" size=3 name="id" required />
        <input type="hidden" name="op" value="fetch" />
        <input type="submit" value="Submit" />
      </fieldset>
    </form>

    <form id="edit-entry-values" style="display: none;">
      <fieldset>
      <label for="drug">Drug name: </label>
      <input type="text" name="drug" value=""/ required>
      <br />

      <label for="dose">Dose: </label>
      <input type="text" size="5" name="dose" value="" required/>
      <input type="radio" name="dose_unit" id="mg" value="mg"> mg
      <input type="radio" name="dose_unit" id="g" value="g"> g
      <input type="radio" name="dose_unit" id="oz." value="oz."> oz
      <br />

      <label for="datetime">Date and time: </label>
      <input type="datetime-local" name="datetime" required value="" />
      <br />

      <label for="comment">Comments: </label>
      <input type="text" name="comment" value=""/>
      <br />

      <input type="hidden" name="op" value="edit" />
      <input type="hidden" name="id" value="" />
      <input type="submit" value="Submit" />
    </fieldset>
    </form>
    <?php
  } else if ($op == "trip")
  {
    ?>
    <h2>Generate a trip report</h2>
    <p class="warning">To generate a trip report, specify the trip's date and then select all the entries
      to be included in the report.</p>
      <form id="gen-trip" action="trip.php" method="POST" data-parsley-validate>
        <fieldset>
          <label for="date">Trip date: </label>
          <input type="date" name="date" required/>
          <input type="hidden" name="done" value="no" />
          <input type="submit" value="Submit" />
        </fieldset>
      </form>
    <?php
  }
echo "<div id='incoming'></div>";
echo "</div>";
$dry->build_links();
$dry->build_footer();
  ?>
