<?php

class Model {
  function Model() {
  }
  function createDbConnection() {
    $conn = NULL;
    try {
      $conn = new PDO ("mysql:host=localhost;dbname=wedding", "weddingwebuser", "weddingwebuser1234");
    } catch (PDOException $e) {
      echo ("Connection failed: " . $e->getMessage());
    }
    return $conn;
  }
  function guestView($keycode) {
    $guest = NULL;
    $conn = $this->createDbConnection();
    $query = "SELECT * FROM wedding.guest WHERE keycode = :keycode";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':keycode', $keycode, PDO::PARAM_STR);
    $stmt->execute();
    $guests = $stmt->fetchAll();
    $stmt->closeCursor();
    if (count($guests) == 1) {
      $guest = $guests[0];
    }
    return $guest;
  }
  function guestUpdate($keycode, $attending, $partySize, $email, $phone) {
    $conn = $this->createDbConnection();
    $query = "UPDATE wedding.guest SET"
      . " attending = :attending,"
      . " partysize = :partysize,"
      . " email = :email,"
      . " phone = :phone"
      . " WHERE keycode = :keycode "
      . ";";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':keycode', $keycode, PDO::PARAM_STR);
    $stmt->bindValue(':attending', $attending, PDO::PARAM_INT);
    $stmt->bindValue(':partysize', $partySize, PDO::PARAM_INT);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->closeCursor();
    $affected = $stmt->rowCount();
    return $affected;
  }
}

?>
