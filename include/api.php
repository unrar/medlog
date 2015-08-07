<?php
/*
 * MedLog's Internal API (mAPI).
 * This is not a RESTful API, but a rather simplistic API.
 * This API is managed by MJM, and their major version numbers (1.x.x) should be equal.
 * @method: POST
 * @endpoint: $_POST["op"]
 * @return: JSON encoded response. */


require_once("usercontrol.php");
require_once("entries.php");

class mAPI
{

  /* Properties */
  protected $method;  // HTTP method
  protected $u;       // UserControl
  protected $e;       // EntriesController
  protected $sess_user;    // Username (at this point we should be sure we're logged in)
  protected $data;    // Data

  /* Function: constructor
   * Initializes properties. Sends JSON headers.
   */
  function __construct($mthd, $pdata, $sess_user)
  {
    // Properties
    $this->sess_user = $sess_user;
    $this->method = $mthd;
    $this->data = $pdata;

    // Objects
    $this->u = new UserControl();

    if ($this->data["op"] != "reg") {
      // We don't need an entry controller if we're registering a user
      $this->e = new EntriesController($this->sess_user);
    }
    header("Content-Type: application/json");

  }

  /* Function:jsonResponse
   * Sends a JSON response.
   */
  private function jsonResponse($data, $status = 200)
  {
    header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
    echo json_encode(array("msg" => $data, "status" => $status));
  }

  /* Function: _requestStatus
   * Get string from the status code. */

  private function requestStatus($code)
  {
    $status = array(  
      200 => 'OK',
      403 => 'Forbidden',
      404 => 'Not Found',   
      405 => 'Method Not Allowed',
      500 => 'Internal Server Error'
    ); 
    return ($status[$code])?$status[$code]:$status[500]; 
  }

  /****************************************
   *      API ENDPOINT METHODS HERE       *
   ****************************************/

  ////////////// CRUD METHODS FOR ENTRIES ///////////

  function addEntry()
  {
    $drug = $this->data["drug"];
    $dose = $this->data["dose"];
    $dose_unit = $this->data["dose_unit"];
    $datetime = $this->data["datetime"];
    $comment = $this->data["comment"];

    if ($this->e->AddEntry($this->sess_user, $drug, $dose, $dose_unit, $datetime, $comment))
    {
      $this->jsonResponse("Entry added correctly.");
      die();
    } else {
      $this->jsonResponse("Error while adding entry.", 500);
      die();
    }
  }

  function remEntry()
  {
    /* Check if it's our entry */
    $user = $this->e->GetEntryUser($this->data["id"]);

    if ($user != $this->sess_user && $user != false ) {
      $this->jsonResponse("You don't own the entry you specified! It belongs to " . $user . "'s MedLog.", 403);
      die();
    } else if ($user == false) {
      $this->jsonResponse("The specified entry doesn't exist!", 404);
      die();
    }

    if($this->e->RemEntry($this->data["id"])) {
      $this->jsonResponse("Entry removed correctly.");
      die();
    }
  }

  function fetchEntry()
  {
    // Check if the user owns the entry
    $user = $this->e->GetEntryUser($this->data["id"]);
    if ($user != $this->sess_user && $user != false ) {
      $this->jsonResponse("You don't own the entry you specified! It belongs to $user's MedLog.", 403);
      die();
    } else if ($user == false) {
      $this->jsonResponse("The specified entry doesn't exist!", 404);
      die();
    }

    // Get the data from MySQL
    $query = "SELECT * FROM " . $this->e->tablename . " WHERE id=" . $this->data["id"] . ";";
    $exp = array();
    if ($res = $this->e->connection->query($query))
    {
      while($row = $res->fetch_assoc()) {
        $exp = array_merge(array(), $row);
      }
      header("HTTP/1.1 200 OK");
      echo json_encode($exp);
    } else {
      $this->jsonResponse("Database error.", 500);
    }
    die();
  }
  function editEntry()
  {
    $drug = $this->data["drug"];
    $dose = $this->data["dose"];
    $dose_unit = $this->data["dose_unit"];
    $datetime = $this->data["datetime"];
    $comment = $this->data["comment"];

    if($this->e->EditEntry($this->data["id"], $this->sess_user, $drug, $dose, $dose_unit, $datetime, $comment))
    {
      $this->jsonResponse("Entry edited correctly.");
      die();
    }
    else {
      $this->jsonResponse("Error while editing entry.", 500);
      die();
    }
  }

  ////////////// USERCONTROL-RELATED METHODS ///////////
  function registerUser()
  {
    if ($this->u->registerUser($this->data["username"], $this->data["password"], $this->data["email"], $this->data["realname"])) {
      $this->jsonResponse("Registration completed correctly!");
    } else {
      $this->jsonResponse("Internal error while trying to register you into the database.", 500);
    }
  }

  ////////////// DEBUG- AND META-RELATED METHODS ///////////
  function debug()
  {
    $rab = $this->data["wot"];
    $name = $this->data["name"];
    if ($rab == "work") {
      $this->jsonResponse("I see you debugging like a boss, " . $name . ".");
    } else {
      $this->jsonResponse("SOMETHING REALLY UGLY FAILED I AM FREAKING OUT BYE", 500);
    }
  }
}
?>