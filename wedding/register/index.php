<?php
include("model.php");
error_reporting(0);
class Controller {
  public const VALID_EMAIL_PATTERN = "/^[^@]*@[^@]*\.[^@]*$/";
  public const VALID_PHONE_PATTERN = "/^[0-9]{10}$/";
  public function Controller() {
  }
  protected function adjustQuotes() {
    if (get_magic_quotes_gpc() == true) {
      array_walk_recursive($_GET, array($this, 'stripSlashes_Gpc'));
      array_walk_recursive($_POST, array($this, 'stripSlashes_Gpc'));
      array_walk_recursive($_COOKIE, array($this, 'stripSlashes_Gpc'));
      array_walk_recursive($_REQUEST, array($this, 'stripSlashes_Gpc'));
    }
  }
  protected function getArg($name) {
    $arg = NULL;
    if (isset($_GET[$name])) {
      $arg = $_GET[$name];
    } else if (isset($_POST[$name])) {
      $arg = $_POST[$name];
    }
    return $arg;
  }
  protected function getRequestedUri() {
    $uri = urlencode($_SERVER['REQUEST_URI']);
    return $uri;
  }
  protected function guestUpdate() {
    $msg = "";
    $attending;
    $email = "";
    $alias = "I";
    $phone = "";
    $keycode = 0;
    $partySize = "";
    $db = new Model();
    $loggedIn = false;
    if ($this->getArg('keycode') != NULL && is_numeric($this->getArg('keycode'))) {
      $keycode = $this->getArg('keycode');
    } else {
      $msg .= "* Keycode not given\n";
    }
    if ($this->getArg('accept') != NULL) {
      $attending = true;
    } else if ($this->getArg('decline') != NULL) {
      $attending = false;
    } else {
      $msg = "* Attendance not given\n";
    }
    if ($attending == true) {
      if ($this->getArg('partysize') != NULL) {
        $partySize = $this->getArg('partysize');
        if (!is_numeric($partySize) || $partySize < 0 || $partySize > 10) {
          $msg .= "* Invalid party size given\n";
        }
      } else {
        $msg .= "* Party size not given\n";
      }
      if ($this->getArg('phone') != NULL) {
        $phone = $this->getArg('phone');
        if (!preg_match(Controller::VALID_PHONE_PATTERN, $phone)) {
          $msg .= "* Invalid phone number given\n";
        }
      } else {
        $msg .= "* Phone number not given\n";
      }
      if ($this->getArg('email') != NULL) {
        $email = $this->getArg('email');
        if (!preg_match(Controller::VALID_EMAIL_PATTERN, $email)) {
          $msg .= "* Invalid email given\n";
        }
      }
    } else {
      $partySize = NULL;
      $email = NULL;
      $phone = NULL;
    }
    if (strlen($msg) == 0) {
      $guest = $db->guestView($keycode);
      if ($guest != NULL && !is_null($guest['attending'])) {
        $alias = $guest['alias'];
        $msg = "Registration for \"$alias\" has already been completed.";
      } else {
        $rows = $db->guestUpdate($keycode, $attending, $partySize, $email, $phone);
        if ($rows != 1) {
          $msg .= "Something went wrong updating your information.";
        } else {
          $guest = $db->guestView($keycode);
          if ($guest != NULL) {
            $alias = $guest['alias'];
            $keycode = "";
            $msg .= "$alias's reserveration information updated successfully.";
          }
        }
      }
    } else {
      $guest = $db->guestView($keycode);
      if ($guest != NULL) {
        $alias = $guest['alias'];
      }
      $loggedIn = true;
    }
    include('view.php');
  }
  public function handleRequest() {
    $action = "";
    $this->secureConnection();
    $this->adjustQuotes();
    if ($this->getArg('action') != NULL) {
      $action = $this->getArg('action');
    }
    if(!$this->isLoggedIn() && $action != "login" && $action != "loginprocess") {
      $url = 'index.php?action=login';
      $this->redirect( $url );
    } else {
      switch ($action) {
      case 'login' :
        $this->loginView();
        break;
      case 'loginprocess':
        $this->loginProcess();
        break;
      case 'guestupdate':
        $this->guestUpdate();
        break;
      default:
        $this->redirect('../index.php');
        break;
      }
    }
  }
  protected function isLoggedIn() {
    $keycode = 0;
    $loggedIn = false;
    $db = new Model();
    if ($this->getArg('keycode') != NULL && is_numeric($this->getArg('keycode'))) {
      $keycode = $this->getArg('keycode');
    }
    $guest = $db->guestView($keycode);
    if ($guest != NULL) {
      $loggedIn = true;
    }
    return $loggedIn;
  }
  protected function loginView() {
    $keycode = "";
    $loggedIn = false;
    include('view.php');
  }
  protected function loginProcess() {
    $db = new Model();
    $keycode = 0;
    $loggedIn = false;
    if ($this->getArg('keycode') != NULL && is_numeric($this->getArg('keycode'))) {
      $keycode = $this->getArg('keycode');
    }
    $guest = $db->guestView($keycode);
    if ($guest == NULL) {
      $msg = "Code \"$keycode\" not found.";
      $keycode = "";
    } else if (!is_null($guest['attending'])) {
      $alias = $guest['alias'];
      $msg = "Registration for \"$alias\" has already been completed.";
    } else {
      $loggedIn = true;
      $alias = $guest['alias'];
      $partySize = $guest['partysize'];
      $email = $guest['email'];
      $phone = $guest['phone'];
    }
    include('view.php');
  }
  protected function redirect($url) {
    header("Location:" . $url);
    exit();
  }
  function secureConnection() {
    if ( !isset( $_SERVER['HTTPS'] ) ) {
      $url = 'https://' . $_SERVER['HTTP_HOST'] . $this->getRequestedUri();
      $this->redirect($url);
    }
  }
  protected function startSession() {
    if (!isset($_SESSION)) {
      session_start();
    }
  }
  protected function stopSession() {
    if (isset($_SESSION)) {
      $_SESSION = array();
      session_destroy();
    }
  }
  protected function stripSlashes_Gpc(&$value) {
    $value = stripslashes($value);
  }
  protected function toDisplayDate($date) {
    $phpDate = strtotime($date);
    if ($phpDate == FALSE) {
      return "";
    } else {
      return date('m/d/Y', $phpDate);
    }
  }
  protected function toMySqlDate($date) {
    $phpDate = strtotime($date);
    if ($phpDate == FALSE) {
      return "";
    } else {
      return date('Y-m-d', $phpDate);
    }
  }
  function unsecureConnection($requestedPage = "") {
    if (isset($_SERVER['HTTPS'])) {
      $url = "";
      if(empty($requestedPage)) {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $this->getRequestedUri();
      } else {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $requestedPage;
      }
      $this->redirect($url);
    }
  }
} 

$controller = new Controller();
$controller->handleRequest();

//$db = new Model();

//echo ($db->guestUpdate('12345', 1, 2, 'email', 'phone'));

//$guest = $db->guestView('12345');

//var_dump($guest);

?>

