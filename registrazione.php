<?php
include("connessione.php");
$flag=0;
if(isset($_POST['AdminReg']))$flag=1;

if(isset( $_POST['NomeUtente']) && isset( $_POST['EmailUtente']) &&
isset( $_POST['PasswordUtente'])){


$nomeutente = $_POST['NomeUtente'];



if(  $_POST['CognomeUtente'] ==" " || $_POST['CognomeUtente'] ==""  ){
$cognomeutente = 0;
}
else{
$cognomeutente = $_POST['CognomeUtente'];
}


$emailutente = $_POST['EmailUtente'];
$passwordutente = $_POST['PasswordUtente'];


$controlloEmail = "SELECT IdUtente FROM Utenti WHERE EmailUtente = '".$emailutente."'   ";
$risultatoEmail = mysqli_query($connessione, $controlloEmail);


if(mysqli_num_rows($risultatoEmail)>0){
echo"<script> alert( 'Email esistente'); </script>";
}
else{
//se email non esiste inserisco i dati

$queryInserisci = "INSERT INTO Utenti (NomeUtente, CognomeUtente, EmailUtente, PasswordUtente)
						VALUES('$nomeutente','$cognomeutente','$emailutente','$passwordutente') " ;
                        
$risultatoInserimento = mysqli_query($connessione, $queryInserisci);  

if($risultatoInserimento){
echo"<script> alert( 'Registrazione riuscita'); </script>";
if($flag){
header('location :admin.php');
}
else{
header('location :login.php');
}
}
else{
echo"<script> alert( 'Registrazione fallita'); </script>";
}


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
<title>REGISTRATI</title>
</head>

<body style="background-color:#d6ffa8 !important;">
<div class="container">
<h2> Registrati </h2>
<form action="registrazione.php" method="post">

<input type="text" class="form-control" placeholder="Inserisci nome" name="NomeUtente" required><br>
<input type="text" class="form-control" placeholder="Inserisci cognome" name="CognomeUtente" ><br>
<input type="email" class="form-control" placeholder="Inserisci email" name="EmailUtente" required><br>
<input type="password"  class="form-control" placeholder="Inserisci password" name="PasswordUtente" required><br>
<input type="password" class="form-control" placeholder="Ripeti password" name="RipetiPassword" required><br>
<input type="submit" class="btn btn-default" value="Registrati" name="Registrazione">


</form>
</div>
</body>

<html>
