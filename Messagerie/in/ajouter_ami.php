<?php
$servername = "websithbadyss.mysql.db";
$username = "websithbadyss";
$password = "Ilian5721";
$dbname = "websithbadyss";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

$sessionUserID = $_POST["sessionUserID"];
$profileUserID = $_POST["profileUserID"];
$nom = $_POST["nom"];
$prenom = $_POST["prenom"];
$formation = $_POST["formation"];
$UserID = $_POST["UserId"];

// Assurez-vous de définir les valeurs de sender_id, receiver_id et status
$sql = "INSERT INTO friendship (sender_id, receiver_id, status) VALUES ('$sessionUserID', '$profileUserID', '0')";

if ($conn->query($sql) === TRUE) {
    header("Location: profil.php?nom=$nom&prenom=$prenom&formation=$formation&UserId=$profileUserID");
} else {
    echo "Erreur lors de l'ajout de la demande d'ami : " . $conn->error;
}

$conn->close();
?>
