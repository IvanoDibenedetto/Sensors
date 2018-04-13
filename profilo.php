<?php
include("connessione.php");
session_start();

$idutente = $_SESSION['id'];
$flag_vis=0;
$flag_dati=0;

if(isset($_POST['Trasferisci'])){
mysqli_query($connessione, "INSERT INTO Trasferimento (Flag) VALUES (1) ");
}


if(isset($_POST['Visualizza'])){
$flag_vis=1;
}

if(isset($_POST['VisualizzaDati'])){
$flag_dati=1;
}



if(isset($_POST['Cancella'])){
$idapp = $_POST['CancellaApp'];


$cerca = mysqli_prepare($connessione,"SELECT * FROM ApplicazioneEsterna WHERE Codice = ?  ");
	mysqli_stmt_bind_param($cerca, "i", $idapp);
	mysqli_stmt_execute($cerca);
	
if(mysqli_num_rows($cerca)>0){

$elimina = mysqli_prepare($connessione,"DELETE FROM ApplicazioneEsterna WHERE Codice = ?  ");
	mysqli_stmt_bind_param($elimina, "i", $idapp);
	mysqli_stmt_execute($elimina);

if($elimina) echo"<script> alert( 'Applicazione esterna rimossa'); </script>";
else {echo"<script> alert( 'Errore di rete durante la cancellazione'); </script>"; }
}

else{
echo"<script> alert( 'Codice inesistente'); </script>";
}
}


if(isset($_POST['Autorizza'])){

$codice=0;
$stringa ="00000";

$checkbox = mysql_query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` IN ('RaccoltaDati', 'Sensore') AND COLUMN_NAME NOT LIKE 'Id_%' AND COLUMN_NAME NOT LIKE 'Fk_%'"); 

$i=0;

if(isset($_POST['NomeApp'])){
$nome = $_POST['NomeApp'];

}
while($row = mysql_fetch_assoc($checkbox)){
$temp = $row['COLUMN_NAME'];

 if(isset($_POST['test'.$temp])){
 
 $stringa[$i]='1';
 } else{
 $stringa[$i]='0';
 }
 
$i++;
}
//echo $stringa;
$codice = rand(1,1000000000);

echo"<script> alert( 'CODICE APPLICAZIONE ESTERNA: $codice'); </script>";
if(! mysqli_query($connessione,"INSERT INTO ApplicazioneEsterna (Fk_Utente,Codice,Nome,Dati_trasferiti) 
			values ('".$idutente."','".$codice."','".$nome."','".$stringa."' )")){
           echo mysqli_error($connessione);
            }

}



?>



<!DOCTYPE html>
<html lang="en">

<head>

 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 


<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php
$query = mysqli_query($connessione, "SELECT Data,Rilevazione FROM RaccoltaDati INNER JOIN  Sensore ON Fk_Sensore = Id_Sensore
								INNER JOIN Utenti ON Fk_Utente = IdUtente WHERE IdUtente = '".$idutente."' ");
																		
$dataPoints = array();

while ($row = mysqli_fetch_assoc($query)) {

$val = $row['Rilevazione'];
$int = (int)filter_var($val, FILTER_SANITIZE_NUMBER_INT);

$dataPoints[] = array("label" => $row['Data'], "y" =>$int );
}
?>


<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title: {
		text: "Varianza temperatura per data"
	},
	axisY: {
		title: "Temperatura",
		includeZero: true
	},
	data: [{
		type: "column",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>





<title>PROFILO</title>
</head>

<body style="background-color:#d6ffa8 !important;">
<div class="container">
<h2 align="center"> Benvenuto, <?=$_SESSION['NomeUtente'] ?>!
<a href="http://hitechstudios.altervista.org/logout.php" class="btn btn-default" align="center">Logout</a>
</h2> 


<div class="row">
  <div class="col-sm-4">
<h3>Autorizza Applicazione Esterna </h3>

<form align="center" action="profilo.php" method="post">

<input type="text" class="form-control" placeholder="Inserisci nome applicazione" name="NomeApp"><br>
<!--
rilevazione <input type='checkbox'  name='testRilevazione' value = 'Rilevazione'><br>
data <input type='checkbox'  name='testData' value = 'Data'><br>
dettagli <input type='checkbox'  name='testDettagli' value = 'Dettagli'><br>
marca <input type='checkbox'  name='testMarca' value = 'Marca'><br>
tipo <input type='checkbox'  name='testTipologia' value = 'Tipologia'><br>
!-->
<?


$checkbox = mysql_query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` IN ('RaccoltaDati', 'Sensore') AND COLUMN_NAME NOT LIKE 'Id_%' AND COLUMN_NAME NOT LIKE 'Fk_%'"); 

while($row = mysql_fetch_assoc($checkbox)){

$temp = $row['COLUMN_NAME'];
	$stringa = $temp."      "."<input type='checkbox' name='test$temp' value = '$temp'><br>";
 echo $stringa;
 
/*echo" <div class='checkbox-inline'>
  <label><input type='checkbox' value='$temp' name'test$temp'>$temp</label>
</div>";*/

}

?><br>
<input type="submit" class="btn btn-default" value="Autorizza" name="Autorizza"> <br><br>
</form>
<br><br>

<h3>Elimina Applicazione Esterna </h3>
<form align="center" action="profilo.php" method="post">
<input type="number" class="form-control" placeholder="Inserisci id applicazione esterna" name="CancellaApp"><br>
<input type="submit" class="btn btn-default" value="Cancella" name="Cancella"><br><br>
</form>

<form align="center" action="profilo.php" method="post">
<input type="submit" class="btn btn-default" value="Visualizza applicazioni esterne" name="Visualizza">

<table class="table">

<?php
global $flag_vis;
global $idutente;
if($flag_vis){

$vis= mysqli_query($connessione, "SELECT * FROM ApplicazioneEsterna WHERE Fk_Utente = '".$idutente."' ");

echo"<tr>";
echo"<th class='th'>CODICE</th>";
echo"<th class='th'>NOME APPLICAZIONE</th>";

echo"</tr>";

}
while ($row = mysqli_fetch_assoc($vis)) {
		echo"<tr>";
	$r1 = "<td class='td'> ". $row['Codice']."</td> ";
	$r2 = "<td class='td'> ". $row['Nome']." </td>";
        echo $r1;
        echo $r2;
        echo "</tr>";
        }

?>
</table>
<input type="submit" class="btn btn-default" value="Visualizza Dati" name="VisualizzaDati">
<table class="table">

<?php
global $flag_dati;
global $idutente;
if($flag_dati){

$d= mysqli_query($connessione, "SELECT * FROM RaccoltaDati INNER JOIN  Sensore ON Fk_Sensore = Id_Sensore
								INNER JOIN Utenti ON Fk_Utente = IdUtente WHERE IdUtente = '".$idutente."' ");

echo"<tr>";
echo"<th class='th'>CODICE SENSORE</th>";
echo"<th class='th'>DATA RILEVAZIONE</th>";
echo"<th class='th'>RILEVAZIONE</th>";
echo"<th class='th'>DETTAGLI</th>";

echo"</tr>";

}
while ($row = mysqli_fetch_assoc($d)) {
		echo"<tr>";
		$r1 = "<td class='td'> ". $row['Fk_Sensore']."</td> ";
		$r2 = "<td class='td'> ". $row['Data']." </td>";
		$r3 = "<td class='td'> ". $row['Rilevazione']." </td>";
		$r4 = "<td class='td'> ". $row['Dettagli']." </td>";
        echo $r1;
        echo $r2;
        echo $r3;
        echo $r4;
        echo "</tr>";
        }

?>
</table>
<br>
<input type="submit" class="btn btn-default" value="Trasferisci dati" name="Trasferisci">
</form>

</div>

<div class="col-sm-2">
</div>

<div class="col-sm-6">
<h3>Dashboard</h3>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</div>


</div>
</div>
</body>
</html>
