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
    <p class="game_header">Wähle bitte hier dein gewünschtes Fach, die Anzahl der Fragen und ob die Antworten angezeigt werden soll, aus.</p>
	  

  </section>
<div class="quiz">
	
<?php
	include "db_connect.php";
// Werte übernehmen aus Auswahl

$fragenlimit = 5;
if (isset($_REQUEST["fragenlimit"])) {
    $fragenlimit = $_REQUEST["fragenlimit"];
}

$selectedKursId = 0;
if (isset($_REQUEST["kurs"])) {
    $selectedKursId = ($_REQUEST["kurs"]);
}

$zeige_loesung = "ja";
if (isset($_REQUEST["antwortanzeigen"])) {
    $zeige_loesung = ($_REQUEST["antwortanzeigen"]);
}
	
		 // Kurs ermitteln
 $select = $db->query("SELECT `kursid`, `kurs` FROM `Kurse`");
 $kurse = $select->fetchAll(PDO::FETCH_OBJ);
 $kurs_array = array();
 echo '<form action="" method="get"><fieldset><legend><b>Quiz-Auswahl</b></legend>' .
  '<p><label>Kurs: <select style="width: 80%;" name="kurs">';
 foreach ($kurse as $kurs) {
  $kurs_array[] = $kurs->kurs;
  echo '<option value="' . $kurs->kursid . '"';
   if ($selectedKursId === $kurs->kursid) {
    echo ' selected="selected"';
   }
  echo '>' . $kurs->kurs . '</option>';
 }


  echo '</select></label>';
// Auswahl der Fragenanzahl	
  echo '<form action="" method="get">' .	
       '<p><label>Anzahl Fragen: <input type="number" name="fragenlimit" value="' .$fragenlimit . '" required="required" style="width: 50px;"></label><br> ' .
	   '<span class="info">Wenn ein Kurs weniger Fragen als gewählt hat, werden nur vorhandene Fragen angezeigt.</span></p>';
// Auswahl Antwortenanzeige	
  echo '<p><label>Antworten anzeigen: <select name="antwortanzeigen">';

  if ($zeige_loesung === "ja") echo '<option value="ja" selected>Ja</option><option value="nein">Nein</option>';
  else echo '<option value="ja">Ja</option><option value="nein" selected>Nein</option>';

  echo '</select></label></p>';
  echo '<input type="submit" value="Fragen anzeigen" title="Auswahl ausführen"> &emsp;' .
  '</p></fieldset></form>';
	
// Übergabe an Quiz	
if (isset($_GET["kurs"])) {	
	$kurs = ($_GET["kurs"]);
	include "quiz.php";
}
?>
</div>
</div>
</body>
</html>
