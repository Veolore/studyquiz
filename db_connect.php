<?php
// PHP Fehlermeldungen anzeigen
error_reporting(E_ALL);
ini_set('display_errors', true);

// Zugangsdaten zur Datenbank
$DB_HOST = "localhost"; // Host-Adresse
$DB_NAME = "xxxxxxxxx"; // Datenbankname
$DB_BENUTZER = "xxxxxxxxx"; // Benutzername
$DB_PASSWORT = "xxxxxxxxx"; // Passwort

// Zeichenkodierung UTF-8 (utf8mb4) bei der Verbindung setzen und eine PDOException bei einem Fehler auslösen
$OPTION = [
  PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
 ];

try {
 // Verbindung zur Datenbank aufbauen
 $db = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME,
  $DB_BENUTZER, $DB_PASSWORT, $OPTION);
}
catch (PDOException $e) {
 // Bei einer fehlerhaften Verbindung eine Nachricht ausgeben
 exit("Verbindung fehlgeschlagen! " . $e->getMessage());
}
?>