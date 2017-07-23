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
  function getGuests() {
    $conn = $this->createDbConnection();
    $query = "SELECT * FROM wedding.guest WHERE alias != '';";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $guests = $stmt->fetchAll();
    $stmt->closeCursor();
    return $guests;
  }
  function getNAccepted() {
    $conn = $this->createDbConnection();
    $query = "SELECT Count(keycode) FROM wedding.guest WHERE attending = true;";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetch();
    $stmt->closeCursor();
    return $results[0];
  }
  function getNAttending() {
    $conn = $this->createDbConnection();
    $query = "SELECT Sum(partysize) FROM wedding.guest;";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetch();
    $stmt->closeCursor();
    return $results[0];
  }
  function getNDenied() {
    $conn = $this->createDbConnection();
    $query = "SELECT Count(keycode) FROM wedding.guest WHERE attending = false;";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetch();
    $stmt->closeCursor();
    return $results[0];
  }
  function getNResponded() {
    $conn = $this->createDbConnection();
    $query = "SELECT Count(keycode) FROM wedding.guest WHERE attending IS NOT NULL;";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetch();
    $stmt->closeCursor();
    return $results[0];
  }
  function getNTotal() {
    $conn = $this->createDbConnection();
    $query = "SELECT Count(keycode) FROM wedding.guest WHERE alias != '';";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetch();
    $stmt->closeCursor();
    return $results[0];
  }
  function authenticate($username, $password) {
    $user = NULL;
    $conn = $this->createDbConnection();
    $query = "SELECT * FROM wedding.user WHERE username = :username AND password = Sha1(:password);";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
    $users = $stmt->fetchAll();
    $stmt->closeCursor();
    if (count($users) == 1) {
      $user = $users[0];
      $user = array($user['id'], $user['username']);
    }
    return $user;
  }
}

