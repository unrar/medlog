<?php
/* Helper functions for other files! DRY module. */
class DRYHelper
{
  function build_links()
  {
    ?>
    <ul id="links">
      <li><a href="index.php">Home</a></li>
      <li><a href="journal.php">See your whole MedLog</a></li>
      <li><a href="manage.php?op=add">Add an entry to your journal</a></li>
      <li><a href="manage.php?op=rem">Remove an entry from your journal</a></li>
      <li><a href="manage.php?op=edit">Edit an entry from your journal</a></li>
      <li><a href="manage.php?op=trip">Generate a trip report</a></li>
      <li><a href="login.php?logout=true">Log out</a></li>
    </ul>
    <?php
  }

  function build_header($title = null)
  {
    if ($title == null)
    {
      $title = "MedLog";
    } else {
      $title = "MedLog - $title";
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <title><?php echo $title; ?></title>
      <link rel="stylesheet" type="text/css" href="http://medlog.io/assets/medlog.css" />
    </head>
    <body>
    <?php
  }

  function build_footer()
  {
    ?>
  </body>
  </html>
  <?php
  }

  function build_table_header()
  {
    ?>
    <table id="entries">
      <!-- Headers -->
      <tr class="headings">
        <th>Entry ID</th>
        <th>Drug name</th>
        <th>Dose</th>
        <th>Date</th>
        <th>Comments</th>
      </tr>
    <?php

  }

  /* Build a row of the journal.
     PARAM: $row = mysqli.result->fetch_assoc()[$row]; */
  function build_table_row($row)
  {
    echo "<tr class=\"data\">\n";
    echo "<td class=\"id\">"   . $row["id"]   . "</td>\n";
    echo "<td class=\"drug\">" . $row["drug"] . "</td>\n";
    echo "<td class=\"dose\">" . $row["dose"] . $row["dose_unit"] . "</td>\n";
    echo "<td class=\"date\">" . $row["date"] . "</td>\n";
    echo "<td class=\"comment\">" . $row["comment"] . "</td>\n";
    echo "</tr>";
  }


  /* Build all rows of the journal.
     PARAM: $rows = mysqli.result->fetch_assoc(); */
  function build_table_rows($rows)
  {
    foreach ($rows as $row)
    {
      echo "<tr class=\"data\">\n";
      echo "<td class=\"id\">"   . $row["id"]   . "</td>\n";
      echo "<td class=\"drug\">" . $row["drug"] . "</td>\n";
      echo "<td class=\"dose\">" . $row["dose"] . $row["dose_unit"] . "</td>\n";
      echo "<td class=\"date\">" . $row["date"] . "</td>\n";
      echo "<td class=\"comment\">" . $row["comment"] . "</td>\n";
      echo "</tr>";
    }
    echo "</table>";
  }

  /* Trip report generation table */
  function build_trg_table_header()
  {
    ?>
    <form action="trip.php" method="POST">
    <input type="hidden" name="done" value="yes" />

    <table id="entries">
      <!-- Headers -->
      <tr class="headings">
        <th>Include?</th>
        <th>Entry ID</th>
        <th>Drug name</th>
        <th>Dose</th>
        <th>Date</th>
        <th>Comments</th>
      </tr>
    <?php

  }

  function build_trg_table_row($row)
  {
    echo "<tr class=\"data\">\n";
    echo "<td class=\"check\"><input type=\"checkbox\" name=\"included[]\" value=\"" . $row["id"] . "\" /></td>\n";
    echo "<td class=\"id\">"   . $row["id"]   . "</td>\n";
    echo "<td class=\"drug\">" . $row["drug"] . "</td>\n";
    echo "<td class=\"dose\">" . $row["dose"] . $row["dose_unit"] . "</td>\n";
    echo "<td class=\"date\">" . $row["date"] . "</td>\n";
    echo "<td class=\"comment\">" . $row["comment"] . "</td>\n";
    echo "</tr>";
  }

  function build_trg_table_footer()
  {
    echo "</table>\n";
    echo "<input type=\"submit\" value=\"Submit\" />\n";
    echo "</form>";
  }


}

?>
