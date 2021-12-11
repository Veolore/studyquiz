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
    <p class="game_header">Hier kannst du die Fragen erstellen und bearbeiten.</p>
    <p class="game_header">&nbsp;</p>



<?php
// Verbindung zur Datenbank aufbauen.  
include "db_connect.php";	
	
 // Kurs ermitteln
 $select = $db->query("SELECT `kursid`, `kurs` FROM `Kurse`");
 $kurse = $select->fetchAll(PDO::FETCH_OBJ);
 $kurs_array = array();
 echo '<form action="" method="get">' .
  '<p><label>Kurs: <select style="width: 80%;" name="kurs"><option value="nichtzugeordnet">Nicht zugeordnet</option>';
 foreach ($kurse as $kurs) {
  $kurs_array[] = $kurs->kurs;
  echo '<option value="' . $kurs->kurs . '"';
  if (isset($_GET["kurs"])) {
   if (rawurlDEcode($_GET["kurs"]) == $kurs->kurs) {
    echo ' selected="selected"';
   }
  }
  echo '>' . $kurs->kurs . '</option>';
 }
  echo '</select></label> <input type="submit" value="Fragen anzeigen" title="Auswahl ausführen"> &emsp;' .
  '<p><a href="fragen.php?neu" title="Eine neue Kursfrage eintragen">Neue Kursfrage</a> | ' .
  '<a href="datenpflege.html">Zurück zur Datenpflege</a></p>' .
  '</p></form>';

 // Neue Kursfrage (Form)
 if (isset($_GET["neu"])) {
  echo '<form name="Form" action="fragen.php" method="post" autocomplete="off">
   <fieldset>
    <legend><b>Neue Kursfrage</b></legend>';
	
	echo '<form action="" method="get">' .
	  '<p><label>Kurs: <select style="width: 80%;" name="kursfs">';
	 foreach ($kurse as $kurs) {
	  $kurs_array[] = $kurs->kurs;
	  echo '<option value="' . $kurs->kursid . '"';
	  if (isset($_GET["kurs"])) {
	   if (rawurlDEcode($_GET["kurs"]) == $kurs->kurs) {
		echo ' selected="selected"';
	   }
	  }
	  echo '>' . $kurs->kurs . '</option>';
	 }
	
	
   echo ' 
   </select>
   <p><label>Frage: <br>
   <textarea name="frage" rows="5" required="required"></textarea><br>
   <span class="info">Hier gibst du die Frage ein (HTML-Tags sind möglich).</span>
   </p>
   <p>
   <label>Antwort 1: <input type="text" name="antwort1" style="width: 80%;" required="required"></label> <br>
   <label>Antwort 2: <input type="text" name="antwort2" style="width: 80%;" required="required"></label> <br>
   <label>Antwort 3: <input type="text" name="antwort3" style="width: 80%;" required="required"></label> <br>
   <label>Antwort 4: <input type="text" name="antwort4" style="width: 80%;" required="required"></label> </p>
   <p><label>Lösung: <input type="number" name="richtigeantwort" value="1" min="1" max="4"
    required="required" style="width:50px;"></label> <br>
    <span class="info">Hier wählst du aus welche Antwort (1 - 4) richtig ist.</span>
   </p>
   <p><input type="submit" name="neu" value="Kursfrage eintragen"></p>
   </fieldset>
  </form>';
 }

 // Neue Kursfrage speichern
 if (isset($_POST["neu"])) {
  $update = $db -> prepare("INSERT INTO `Fragen`(`kursfs`, `frage`, `antwort1`, `antwort2`, `antwort3`, `antwort4`, `richtigeantwort`)
                                                 VALUES (:kursfs, :frage, :antwort1, :antwort2, :antwort3, :antwort4, :richtigeantwort)");
  if ($update->execute(array(
   ':kursfs' => $_POST["kursfs"], ':frage' => $_POST["frage"],
   ':antwort1' => $_POST["antwort1"], ':antwort2' => $_POST["antwort2"],
   ':antwort3' => $_POST["antwort3"], ':antwort4' => $_POST["antwort4"],
   ':richtigeantwort' => $_POST["richtigeantwort"],
   ))) {
   echo '<p><span style="color: #00B1FF; font-weight: bold;">&#10149;</span> Der Eintrag wurde gespeichert.</p>';
  }
 }

 // Eintrag bearbeiten/löschen (Form)
 if (isset($_GET["bearbeiten"])) {
  $select = $db->query("SELECT `fragenid`, `frage`, `antwort1`, `antwort2`, `antwort3`, `antwort4`, `richtigeantwort`, `kursfs`
                                          FROM `Fragen`
                                          WHERE `fragenid` = '" . $_GET["bearbeiten"] . "'");
  $eintrag = $select->fetch();
  echo '<form name="Form" action="fragen.php?quiz=' . $_GET["bearbeiten"] . '" method="post" autocomplete="off">
   <fieldset>
    <legend><b>Kursfrage bearbeiten</b> </legend>';
   
   	echo '<form action="" method="get">' .
	  '<p><label>Kurs: <select style="width: 80%;" name="kursfs">';
	 foreach ($kurse as $kurs) {
	  $kurs_array[] = $kurs->kurs;
	  echo '<option value="' . $kurs->kursid . '"';
	// Abgleich ob kursid und kursfs stimmen - Wenn ja, vorauswahl im select	 
	  if ($kurs->kursid === $eintrag["kursfs"]) { 
		echo 'selected="selected"';	
	  }
	 echo '>' . $kurs->kurs . '</option>';
	}
	 	   
   echo '
   </select>
   <p><label>Frage:  <br>
   <textarea name="frage" rows="5" required="required">' . $eintrag["frage"] . '</textarea><br>
   <span class="info">Hier gibst du die Frage ein (HTML-Tags sind möglich).</span>
   </p>
   <p>
   <label>Antwort 1: <input type="text" name="antwort1" value="' . $eintrag["antwort1"] . '" style="width: 80%;" required="required"></label> <br>
   <label>Antwort 2: <input type="text" name="antwort2" value="' . $eintrag["antwort2"] . '" style="width: 80%;" required="required"></label> <br>
   <label>Antwort 3: <input type="text" name="antwort3" value="' . $eintrag["antwort3"] . '" style="width: 80%;" required="required"></label><br>
   <label>Antwort 4: <input type="text" name="antwort4" value="' . $eintrag["antwort4"] . '" style="width: 80%;" required="required"></label></p>
   <p>
   <label>Lösung: <input type="number" name="richtigeantwort" value="' . $eintrag["richtigeantwort"] . '" min="1" max="4"
    required="required" style="width: 50px;"></label> <br>
    <span class="info">Hier wählst du aus welche Antwort (1 - 4) richtig ist.</span>
   <input type="hidden" name="fragenid" value="' . $eintrag["fragenid"] . '"></p>
   <p><label><input type="radio" name="option" value="edit" checked="checked">Frage ändern</label>
   <label><input type="radio" name="option" value="delete"> Frage löschen</label> &emsp; 
   <input type="submit" name="bearbeiten" value="Ausführen"><br>
   </p>
   </fieldset>
  </form>';
 }

 // Quizfrage bearbeiten/löschen
 if (isset($_POST["bearbeiten"])) {
  if ($_POST["option"] == "edit") {
    $update = $db -> prepare("UPDATE `Fragen`
                                                      SET `kursfs` = :kursfs, `frage` = :frage,
                                                          `antwort1` = :antwort1, `antwort2` = :antwort2, `antwort3` = :antwort3, `antwort4` = :antwort4,
                                                          `richtigeantwort` = :richtigeantwort
                                                    WHERE `fragenid` = :fragenid");
    if ($update->execute(array(
     ':kursfs' => $_POST["kursfs"], ':frage' => $_POST["frage"],
     ':antwort1' => $_POST["antwort1"], ':antwort2' => $_POST["antwort2"],
     ':antwort3' => $_POST["antwort3"], ':antwort4' => $_POST["antwort4"],
     ':richtigeantwort' => $_POST["richtigeantwort"],
     ':fragenid' => $_POST["fragenid"]))) {
     echo '<p><span style="color: #00B1FF; font-weight: bold;">&#10149;</span> Die Frage wurde überschrieben.</p>';
    }
  }
  if ($_POST["option"] == "delete") {
   $delete = $db->prepare("DELETE FROM `Fragen`
                                                WHERE `fragenid` = :fragenid");
   if ($delete->execute(array(':fragenid' => $_POST["fragenid"]))) {
    print '<p><span style="color: #00B1FF; font-weight: bold;">&#10149;</span> Die Frage wurde gelöscht.</p>';
   }
  }
 }

 // Fragen anzeigen
 // Zeige nicht zugeordnete Fragen
 if (isset($_GET["kurs"])) {
	 if ($_GET["kurs"] == "nichtzugeordnet") {
		 $select = $db->query("SELECT `fragenid`, `frage`, `kursfs`
                                           FROM `Fragen`
										   WHERE `kursfs` IS NULL");
   echo '<fieldset>
	   <legend><b>Nicht zugeordnet</b></legend>';
   $fragenliste = $select->fetchAll(PDO::FETCH_OBJ);
   foreach ($fragenliste as $flist) {
    echo '<p>' . ' <a href="fragen.php?quiz=nichtzugeordnet&amp;bearbeiten=' . $flist->fragenid . '" title="Quizfrage bearbeiten">' . substr(strip_tags($flist->frage), 0, 80) . '</a></p>';
   }
   echo '</fieldset>';
   }
	 
   else {	 
  // Quizfrage zum bearbeiten auswählen
   echo '<fieldset>
    <legend><b>' . ($_GET["kurs"]) . '</b></legend>';
   $select = $db->query("SELECT `fragenid`, `frage`, `kursfs`, `kursid`, `kurs`
                                           FROM `Fragen` INNER JOIN `Kurse`
										   ON  `kursfs`=`kursid`
                                           WHERE `kurs` = '" . $_GET["kurs"] . "'");
   $fragenliste = $select->fetchAll(PDO::FETCH_OBJ);
   foreach ($fragenliste as $flist) {
    echo '<p>' . ' <a href="fragen.php?quiz=' . rawurlENcode($flist->kursid) . '&amp;bearbeiten=' . $flist->fragenid . '" title="Quizfrage bearbeiten">' . substr(strip_tags($flist->frage), 0, 80) . '</a></p>';
   }
   echo '</fieldset>';
  }
 }

?>


  </section>
</div>
</body>
</html>
