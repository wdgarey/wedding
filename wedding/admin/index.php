<?php
include("model.php");
//error_reporting(0);
error_reporting(E_ALL);
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
    $uri = $_SERVER['REQUEST_URI'];
    return $uri;
  }
  protected function guestsView() {
    $db = new Model();
    $guests = $db->getGuests();
    $accepted = $db->getNAccepted();
    $attending = $db->getNAttending();
    $denied = $db->getNDenied();
    $responded = $db->getNResponded();
    $total = $db->GetNTotal();
    $unresponded = $total - $responded;
    $acceptedPerc = intval(($accepted / $total) * 100);
    $deniedPerc = intval(($denied / $total) * 100);
    $respondedPerc = intval(($responded / $total) * 100);
    $unrespondedPerc = 100 - $unresponded;
    include('view.php');
  }
  public function handleRequest() {
    $action = "";
    $this->startSession();
    $this->secureConnection();
    $this->adjustQuotes();
    if ($this->getArg('action') != NULL) {
      $action = $this->getArg('action');
    }
    if (!$this->isLoggedIn() && $action != "login"
      && $action != "loginprocess") {
      $this->redirect('index.php?action=login');
    } else {
      switch ($action) {
      case 'login' :
        $this->loginView();
        break;
      case 'loginprocess':
        $this->loginProcess();
        break;
      case 'logout':
        $this->logout();
        break;
      case 'guestsview':
        $this->guestsView();
        break;
      default:
        $this->redirect('index.php?action=login');
        break;
      }
    }
  }
  protected function isLoggedIn() {
    $loggedIn = (isset($_SESSION)
      && isset($_SESSION['user']));
    return $loggedIn;
  }
  protected function loginView() {
    if ($this->isLoggedIn ()) {
      $url = 'index.php?action=guestsview';
      $this->redirect($url);
    }
    $username = "";
    $password = "";
    include('view.php');
  }
  protected function loginProcess() {
    if ($this->isLoggedIn ()) {
      $url = 'index.php?action=guestsview';
      $this->redirect($url);
    }
    $username = "";
    $password = "";
    $db = new Model();
    if ($this->getArg('username') != NULL) {
      $username = $this->getArg('username');
    }
    if ($this->getArg('password') != NULL) {
      $password = $this->getArg('password');
    }
    $user = $db->authenticate($username, $password);
    if ($user == NULL) {
      $msg = "Invalid username and/or password given.";
      $password = "";
    } else {
      $_SESSION['user'] = $user;
      $url = 'index.php?action=guestsview';
      $this->redirect($url);
    }
    include('view.php');
  }
  protected function logout() {
    $this->stopSession();
    $this->redirect('../index.php');
  }
  protected function redirect($url) {
    header("Location:" . $url);
    exit();
  }
  function secureConnection() {
    if (!isset($_SERVER['HTTPS'])) {
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
?>

