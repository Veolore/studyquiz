<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Study Quiz</title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
<!-- Matomo Image Tracker-->
<img referrerpolicy="no-referrer-when-downgrade" src="https://kevin-geisler.de/statistik/matomo.php?idsite=4&amp;rec=1" style="border:0" alt="" />
<!-- End Matomo -->
</head>
<body>
<!-- Main Container -->
<div class="container"> 
  <!-- Navigation -->
  <header> <a href="https://kevin-geisler.de/quiz/">
    <h4 class="logo">Study Quiz</h4>
    </a>
    <nav>
      <ul>
        <li><a href="#datenschutz">Datenschutz</a></li>
        <li><a href="#impressum">Impressum</a></li>
      </ul>
    </nav>
  </header>
  <!-- Game Section -->
  <section class="game_banner">
    <p class="game_header">Hier kannst du die Fragen teilen. Kopiere dafür einfach den Link aus deinem Browser.</p>
    <p class="game_header">&nbsp;</p>


<?php
// Verbindung zur Datenbank aufbauen.  
 include "db_connect.php"; 

// Eintrag anzeigen
 if (isset($_GET["fragenid"])) {

// Frage aus der Datenbank zum bearbeiten in ein Formular laden.
// ID übergeben?
if (isset($_GET["fragenid"])) {

// Frage mit der ID auslesen
 $select = $db->prepare("SELECT `fragenid`, `frage`, `antwort1`, `antwort2`, `antwort3`, `antwort4`, `richtigeantwort`, `kursfs`
                         FROM `Fragen`
                         WHERE `fragenid` = :fragenid");


 $select->bindParam(':fragenid', $_GET["fragenid"], PDO::PARAM_INT);
 $select->execute(); 
 $eintrag = $select->fetch();

 // überprüfen ob ein Datensatz zurückgegeben wurde.
 if ($select->rowCount() == 1) {


 // Frage mit Antwortmöglichkeiten anzeigen
	 echo '<form action="teilen.php?fragenid=' . $_GET["fragenid"] . '" method="post"><fieldset><legend><b>Frage</b></legend>' .
     '<div class="frage">' . nl2br($eintrag["frage"]) . '</div>' .
     '<p class="antwort"><label><input type="radio" name="aw" value="1"> ' . $eintrag["antwort1"] . '</label></p>' .
     '<p class="antwort"><label><input type="radio" name="aw" value="2"> ' . $eintrag["antwort2"] . '</label></p>' .
     '<p class="antwort"><label><input type="radio" name="aw" value="3"> ' . $eintrag["antwort3"] . '</label></p>' .
     '<p class="antwort"><label><input type="radio" name="aw" value="4"> ' . $eintrag["antwort4"] . '</label></p>' .
	 '</fieldset></form>';
	 
	 
  // Kommentarfunktion - Eingabefeld
	 
	 echo ' 
   <form name="Form" action="teilen.php?fragenid=' . $_GET["fragenid"] . '" method="post" autocomplete="off">
   <fieldset>	 
   <p><label>Kommentar: <br>
   <textarea name="kommentar" rows="5" required="required"></textarea><br>
   <span class="info">Hier hast du die Möglichkeit ein Kommentar zur Frage einzugeben.</span>
   <input type="hidden" name="fragefs" value="' . $_GET["fragenid"] . '">
   </p>
   <p><input type="submit" name="neu" value="Kommentar speichern"></p>
   </fieldset>
   </form>';
	 
 $fragefs = ($_GET["fragenid"]); 
	 
 // Neues Kommentar speichern
 if (isset($_POST["neu"])) {
  $update = $db -> prepare("INSERT INTO `Kommentare`(`kommentar`, `fragefs`)
                                             VALUES (:kommentar, :fragefs)");
  if ($update->execute(array(
   ':kommentar' => $_POST["kommentar"], ':fragefs' => $_POST["fragefs"]))) {
   echo '<p><span style="color: #00B1FF; font-weight: bold;">&#10149;</span> Der Eintrag wurde gespeichert.</p>';
  }
 }
	 
// Kommentare auslesen

$select = $db->query("SELECT `kommentarid`, `kommentar`, `fragefs`
                      FROM `Kommentare`
					  WHERE `fragefs` = '" . $_GET["fragenid"] . "'
                      ORDER BY `kommentarid` ASC");

// gibt ein Objekt mit allen Datensätzen zurück.
// Zeige Nachricht, wenn kein Kommentar vorhanden ist
$kommentare = $select->fetchAll(PDO::FETCH_OBJ);
if (count($kommentare) == 0){
	echo '<p>Bisher gibt es noch keine Kommentare. Schreibe doch gern eins!</p>';
    }
	 
// Zeige Kommentare, wenn welche vorhanden sind
// Anzahl der Kommentare ausgeben.
	 else{
		echo '<form name="Form">
   <fieldset>
    <legend><b>' . count($kommentare) . (count($kommentare) == 1 ? ' Kommentar wurde gefunden.' : ' Kommentare wurden gefunden.') . '</b></legend>'; 
	 
// Ausgabe über eine Foreach-Schleife
foreach ($kommentare as $kommentar) {
	echo '<p><b>Kommentar #' . $kommentar->kommentarid . '</b><br>';
	echo '' . $kommentar->kommentar . '</p>';
	echo '--------------------------------------------';
}
	echo'   </fieldset>
  			</form>
		<p>Bist du der Meinung, dass ein Kommentar nicht passt, schreib uns gerne eine Mail!</p>'; 
 }
 }
 else {
  echo '<p>Dieser Datensatz existiert nicht!</p>';
 }
}	   
}	  
?>
  </section>
</div>
</body>
</html>
