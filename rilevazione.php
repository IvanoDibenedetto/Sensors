<?php
include "connessione.php";

if(isset($_POST['Stringa'])){
$stringa = $_POST['Stringa'];

define("NUM_MAX1", 10);
define("NUM_MIN1", 0);	
	
define("NUM_MAX2", 19);
	
define("NUM_MAX3", 29);	
define("NUM_MIN3", 3);
	
	define("NUM_MAX4", 50);
define("NUM_MIN4", 33);	

$Fk_Sensore = substr($stringa, NUM_MIN1, NUM_MAX1);
$Data = substr($stringa, NUM_MAX1, NUM_MAX2); 
$Rilevazione = substr($stringa, NUM_MAX3, NUM_MIN);
$Dettagli = substr($stringa, NUM_MIN4, NUM_MAX4);
$stringaSplitted = " $Fk_Sensore, $Data, $Rilevazione, $Dettagli ";	

echo htmlspecialchars($stringaSplitted, ENT_QUOTES, 'UTF-8');
//"00000000092018-03-17 12:39:4429°RilevazioneSenzaErrori"




$inserisciRiv = mysqli_prepare($connessione, "INSERT INTO RaccoltaDati (Fk_Sensore, Data, Rilevazione, Dettagli)
				VALUES ('?','?','?','?') ");
                
 mysqli_stmt_bind_param($inserisciRiv, "isss", $Fk_Sensore, NOW(), $Rilevazione, $Dettagli);
	mysqli_stmt_execute($InserisciRiv);             

if(isset($risultatoInserimento)){
	$r1 = '<script> alert("Inserimento riuscito") </script>';
echo $r1;
}
else{
	$r1 = '<script> alert("Errore di rete") </script>';
echo $r1;
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
<title>Rilevazione</title>
</head>
<body style="background-color:#d6ffa8 !important;">

<div class="container">
<h3>Decodifica stringa del sensore</h3>
<br><br>
<form action="rilevazione.php" method="post">
<label for="usr">Stringa di esempio:</label>
<input type="text" id="usr" class="form-control" value="00000000092018-03-17 12:39:4429°DettagliRilevazione" readonly>	<br>	
<input type="text" class="form-control" name="Stringa"><br>
<input type="submit" class="btn btn-default" value="Invia Stringa">
</form>

</div>

</body>
</html>
