<?php
$mysqli = new mysqli("localhost:3306", "root", "password", "db");
$username = "administrator";
$password_hash = hash("sha256", "To3gangsc0de");

$result = $mysqli->query("CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `password` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;");

$stmt = $mysqli->prepare("SELECT id FROM users WHERE name = ?;");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user);
$stmt->fetch();
$stmt->close();
if (!$user) {
  $stmt = $mysqli->prepare("INSERT INTO `users` (name, password) VALUES (?, ?);");
  $stmt->bind_param("ss", $username, $password_hash);
  $stmt->execute();
}

echo "Successfully initialized: Go to <a href='/'>Dashboard</a>";
