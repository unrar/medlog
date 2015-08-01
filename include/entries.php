<?php
/* Controller for managing MedLog's entries */
class EntriesController
{

  /* Properties */
  var $connection;
  var $tablename;
  var $username;

  /* Constructor */
  function EntriesController($username)
  {
    // Set properties
    $this->username = $username;
    $this->tablename = "entries";
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "medlog");
    if ($conn->connect_error)
    {
      $this->HandleError("Unable to connect to the database.");
    } else {
      $this->connection = $conn;
    }
  }

  /* Find entries of a user's */
  function FindEntries($un = null)
  {
    if ($un == null) $un = $this->username;
    $query = "SELECT * FROM $this->tablename WHERE user='$un';";
    $result = $this->connection->query($query);
    if (!$result || $result->num_rows <= 0)
    {
      $this->HandleError("No entries found in your MedLog.");
      return false;
    } else {
      return $result;
    }

  }

  function GetEntryUser($id) {
    $query = "SELECT user FROM $this->tablename WHERE id=$id;";
    $res = $this->connection->query($query);
    if (!empty($res))
    {
      return $res->fetch_array()[0];
    } else {
      return false;
    }
  }
  /* Add an entry to a user's log */
  function AddEntry($uname, $drug, $dose, $dose_unit, $datetime, $comment)
  {
    $query = "INSERT INTO $this->tablename (`user`, `date`, `drug`, `dose`, `dose_unit`, `comment`) VALUES ('$uname', '$datetime', '$drug', $dose, '$dose_unit', '$comment');";
    $result = $this->connection->query($query);
    if (!$result)
    {
      $this->HandleError("Error while adding entry.");
    } else {
      return $result;
    }
  }

  /* Remove an entry by its ID */
  function RemEntry($id)
  {
    $query = "DELETE FROM $this->tablename WHERE id=$id";
    $res = $this->connection->query($query);
    if (!$res)
    {
      $this->HandleError("Error while removing entry.");
      return false;
    } else {
      return true;
    }
  }
  /* Edit entry by ID and all */
  function EditEntry($id, $uname, $drug, $dose, $dose_unit, $datetime, $comment)
  {
    $query = "UPDATE $this->tablename SET user='$uname', drug='$drug', dose='$dose', dose_unit='$dose_unit', date='$datetime', comment='$comment' WHERE id=$id;";
    $res = $this->connection->query($query);
    if (!$res)
    {
      $this->HandleError("Error while editing entry.");
      return false;
    }
    else {
      return true;
    }
  }

  /* Handle errors */
  function HandleError($err_desc)
  {
    echo "<p class=\"error\">An error has occurred: " . $err_desc . "</p>\n";
  }

}
