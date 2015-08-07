<?php
/* Controller for managing MedLog's entries */
require_once("config.php");
class EntriesController
{

  /* Properties */
  var $connection;
  var $tablename;
  var $username;
  var $s;

  /* Constructor */
  function __construct($username)
  {
    $this->s = new Settings();

    // Set properties
    $this->username = $username;
    $this->tablename = "entries";
    // Connect to the database
    $conn = new mysqli($this->s->config["sqlhost"], $this->s->config["sqluser"], 
                       $this->s->config["sqlpassword"], $this->s->config["database"]);
    if ($conn->connect_error)
    {
      $this->HandleError("Unable to connect to the database.");
    } else {
      $this->connection = $conn;
    }
  }

  /* Find entries of a user's */
  function FindEntries($un = null, $date = null)
  {
    if ($un == null) $un = $this->username;
    // Added "ORDER BY" and date support
    $query = ($date == null ? "SELECT * FROM $this->tablename WHERE user='$un' ORDER BY id DESC;"
              : "SELECT * FROM $this->tablename WHERE user='$un' AND date LIKE '$date%' ORDER BY id DESC;");
    $result = $this->connection->query($query);
    if (!$result || $result->num_rows <= 0)
    {
      $this->HandleError("No entries found in your MedLog.");
      return false;
    } else {
      return $result;
    }

  }

  function GetEntryByID($id)
  {
    $query = "SELECT * FROM $this->tablename WHERE id=$id;";
    $result = $this->connection->query($query);
    if (!$result || $result->num_rows <= 0)
    {
      $this->HandleError("No entry found with that ID.");
      return false;
    } else 
    {
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
    $comment = $this->Sanitize($comment, false);
    $query = "INSERT INTO $this->tablename (`user`, `date`, `drug`, `dose`, `dose_unit`, `comment`) VALUES (\"$uname\", \"$datetime\", \"$drug\", $dose, \"$dose_unit\", \"$comment\");";
    $result = $this->connection->query($query);
    if (!$result)
    {
      $this->HandleError("Error while adding entry: " . $this->connection->error);
      return false;
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
    $comment = $this->Sanitize($comment, false);
    $query = "UPDATE $this->tablename SET user=\"$uname\", drug=\"$drug\", dose=\"$dose\", dose_unit=\"$dose_unit\", date=\"$datetime\", comment=\"$comment\" WHERE id=$id;";
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

  function Sanitize($str,$remove_nl=true)
  {
      $str = $this->StripSlashes($str);
      if($remove_nl)
      {
          $injections = array('/(\n+)/i',
              '/(\r+)/i',
              '/(\t+)/i',
              '/(%0A+)/i',
              '/(%0D+)/i',
              '/(%08+)/i',
              '/(%09+)/i'
              );
          $str = preg_replace($injections,'',$str);
      }
      return $str;
  }
  function StripSlashes($str)
  {
      if(get_magic_quotes_gpc())
      {
          $str = stripslashes($str);
      }
      return $str;
  }

}
