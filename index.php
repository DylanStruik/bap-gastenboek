<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gastenboek</title>
</head>
<body>

<h1>Gastenboek</h1>
<form method="post" action="index.php" >
    <label>Voornaam:</label><br>
    <input type="text" name="voornaam" id="voornaam"><br>
    <label>Achternaam:</label><br>
    <input type="text" name="achternaam" id="achterrnaam"><br>
    <label>Bericht</label><br>
    <textarea name="bericht" id="bericht"></textarea>
    <input type="submit" name="submit" id="submit" value="submit">
    <br><br><br>
</form>
</body>
</html>

<?php
$dbc = new PDO('mysql:host=localhost;dbname=gastenboek2', 'root', 'root');
//Prepared statement maken
$query = $dbc ->prepare("INSERT INTO gastenboek VALUES (0,NOW(),:voornaam,:achternaam,:bericht)");
//parameters binden
$query ->bindParam(':voornaam',$voornaam);
$query ->bindParam(':achternaam',$achternaam);
$query ->bindParam(':bericht',$bericht_filter);
//checken van de ingevoerde waardes
if (isset($_POST['submit'])) {
    if (!empty($_POST['voornaam']) && !empty($_POST['achternaam']) && !empty($_POST['bericht'])) {
        $voornaam = $_POST['voornaam'];
        $achternaam = $_POST['achternaam'];
        $bericht = $_POST['bericht'];
        if (strlen($voornaam) > 100 || strlen($achternaam) > 254) {
            echo "Je voornaam en/of achternaam is te lang.";
            exit();
        }
        if (preg_match("%[^A-Z]%i",$voornaam . $achternaam)){
            echo "Er staan tekens in velden die wij niet accepteren.";
            exit();
        }
        if (strlen($bericht) > 5000) {
            echo  "Je recensie is te lang.";
            exit();
        }
        $bericht_filter = preg_replace('/(kut|fuck|tering|tyfus|kanker|penis|lul|anus|klootzak)/i','bobba',$bericht);
        $query->execute() or die('PDO heeft een fout');
    }
}
//Prepared statement maken
$query = $dbc->query("SELECT * FROM gastenboek ORDER BY Date ASC");
//while loop
while($row = $query->fetch()):
    echo '<b>' . $row['voornaam'] . ' ' . $row['achternaam'] . '</b><br><br>' . $row['bericht'] . '<br><br><br>';
endwhile;
$dbc = null;
$query = null;
?>
