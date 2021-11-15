<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Study Quiz</title>
<link href="../css/stylesheet.css" rel="stylesheet" type="text/css">
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
    <p class="game_header">Hier kannst du die Kurse erstellen und bearbeiten.</p>
    <p class="game_header">&nbsp;</p>


		 <nav_pflege>
 			<p><a href="kurse.php?kurs_eintragen">Eintragen</a> |
 			  <a href="kurse.php?kurs_bearbeiten">Bearbeiten</a> |
			  <a href="datenpflege.html">Zurück zur Datenpflege</a>
		    </p>
 			<p>&nbsp;</p>
		 </nav_pflege>

<?php
// Verbindung zur Datenbank aufbauen.  
 include "db_connect.php";
 
// Neuen Kurs anlegen
if (isset($_GET["kurs_eintragen"])) {
	
  echo '<form name="Form" action="kurse.php" method="post" autocomplete="off">
   <fieldset>
    <legend><b>Neuer Kurs</b></legend>
   <p><label>Kurs: <input type="text" name="kurs" style="width: 80%;" maxlength="80" required="required"></label><br>
   <p><input type="submit" name="kurs_eintragen" value="Kurs eintragen"></p>
   </fieldset>
  </form>';	
 }	
// Neuen kurs speichern	
if (isset($_POST["kurs_eintragen"])) {
	  $update = $db -> prepare("INSERT INTO `Kurse`(`kurs`)
                                                 VALUES (:kurs)");
  if ($update->execute(array(
   ':kurs' => $_POST["kurs"]))) {
   echo '<p><span style="color: #00B1FF; font-weight: bold;">&#10149;</span> Der Eintrag wurde gespeichert.</p>';
  }  
  }	
	
// Eintrag bearbeiten/löschen (Form)
 if (isset($_GET["kurs_bearbeiten"])) {

// Kurs aus der Datenbank zum bearbeiten in ein Formular laden.
// Wurde eine ID übergeben?
if (isset($_GET["kursid"])) {

// Kurs mit der ID auslesen
 $select = $db->prepare("SELECT `kursid`, `kurs`
                         FROM `Kurse`
                         WHERE `kursid` = :kursid");

 $select->bindParam(':kursid', $_GET["kursid"], PDO::PARAM_INT);
 $select->execute(); 

 // holt die betreffende Zeile aus dem Ergebnis.
 $kurs = $select->fetch();

 // überprüfen ob ein Datensatz zurückgegeben wurde.
 if ($select->rowCount() == 1) {

 // Formular zum bearbeiten vom Kurs ausgeben
	 	 
  echo '<form name="Form" action="kurse.php?kurs_bearbeiten" method="post" autocomplete="off">
   <fieldset>
    <legend><b>Kurs bearbeiten</b></legend>
   <p><label>Kurs: <input type="text" name="kurs" value="' . $kurs["kurs"] . '" style="width: 80%;" maxlength="80" required="required"></label><br>
   <p>
    <label><input type="radio" name="option" value="edit" checked="checked"> Ändern</label>
    <label><input type="radio" name="option" value="delete" required="required"> Löschen</label>
    <input type="hidden" name="kursid" value="' . $kurs["kursid"] . '">
   </p>
   <p>    <input type="submit" name="execute" value="Absenden"></p>
   </fieldset>
  </form>';
	  

 }
 else {
  echo '<p>Dieser Datensatz ist nicht vorhanden!</p>';
 }
}

// Kurs ändern oder löschen
if (isset($_POST["execute"])) {

 // Kurs ändern
 if ($_POST["option"] == 'edit') {

  // Anweisung für die Ausführung vorbereiten.
  $update = $db->prepare("UPDATE `Kurse`
                          SET
                            `kurs`     = :kurs
                            WHERE `kursid` = :kursid");

  // Die Platzhalter werden über ein assoziatives Array mit dem Inhalt der POST-Variablen übergeben.
  if ($update->execute( [':kurs' => $_POST["kurs"],
                         ':kursid' => $_POST["kursid"] ])) {
   echo '<p>&#9655; Der Kurs wurde überschrieben.</p>';
  }
  else {
   // SQL-Fehlermeldung anzeigen.
   print_r($update->errorInfo());
  }
 }

 // Kurs löschen
 if ($_POST["option"] == 'delete') {

  $delete = $db->prepare("DELETE FROM `Kurse`
                          WHERE `kursid` = :kursid");

  if ($delete->execute( [':kursid' => $_POST["kursid"] ])) {
   echo '<p>&#9655; Der Kurs wurde gelöscht.</p>';
  }
 }
}

// Kurse auslesen
// Ergebnismenge als PDOStatement Objekt
$select = $db->query("SELECT `kursid`, `kurs`
                      FROM `Kurse`
                      ORDER BY `kursid` DESC");

// Objekt mit allen Datensätzen 
$kurse = $select->fetchAll(PDO::FETCH_OBJ);

// Anzahl der Kurse ausgeben.
echo '<h4>' . count($kurse) .
 (count($kurse) == 1 ? ' Kurs wurde gefunden.' : ' Kurse wurden gefunden.') . '</h4>';

		echo '<form name="Form">
   <fieldset>
    <legend><b>Gefundene Kurse</b></legend>'; 
	 
// Ausgabe über eine Foreach-Schleife
foreach ($kurse as $kurs) {
	echo '<a href="kurse.php?kurs_bearbeiten&kursid=' . $kurs->kursid . '"></small> - <b>' . $kurs->kurs . '</b><br>';	
}
	echo'   </fieldset>
  			</form>';	
}
?>


  </section>
</div>
</body>
</html>