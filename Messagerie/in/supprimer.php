<?php
error_reporting(E_ALL);

$servername = "websithbadyss.mysql.db";
$username = "websithbadyss";
$password = "Ilian5721";
$dbname = "websithbadyss";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

$userId = $_GET['userId'];

// Supprimer d'abord les enregistrements dépendants dans la table MessagesGroupes
$sqlDeleteMessages = "DELETE FROM MessagesGroupes WHERE sender_id = ?";
$stmtDeleteMessages = $conn->prepare($sqlDeleteMessages);

if ($stmtDeleteMessages === false) {
    die('Erreur de préparation de la requête : ' . $conn->error);
}

$stmtDeleteMessages->bind_param("i", $userId);

if (!$stmtDeleteMessages->execute()) {
    echo "Erreur lors de la suppression des messages de l'utilisateur.";
    // Vous pouvez choisir de gérer cette erreur selon vos besoins
}

// Ensuite, supprimer les enregistrements dépendants dans la table UtilisateursGroupes
$sqlDeleteUserGroups = "DELETE FROM UtilisateursGroupes WHERE id_utilisateur = ?";
$stmtDeleteUserGroups = $conn->prepare($sqlDeleteUserGroups);

if ($stmtDeleteUserGroups === false) {
    die('Erreur de préparation de la requête : ' . $conn->error);
}

$stmtDeleteUserGroups->bind_param("i", $userId);

if (!$stmtDeleteUserGroups->execute()) {
    echo "Erreur lors de la suppression des groupes de l'utilisateur.";
    // Vous pouvez choisir de gérer cette erreur selon vos besoins
}

// Ensuite, supprimer l'utilisateur de la table Utilisateur
$sqlDeleteUser = "DELETE FROM Utilisateur WHERE id = ?";
$stmtDeleteUser = $conn->prepare($sqlDeleteUser);

if ($stmtDeleteUser === false) {
    die('Erreur de préparation de la requête : ' . $conn->error);
}

$stmtDeleteUser->bind_param("i", $userId);

if ($stmtDeleteUser->execute()) {
    header("Location: dashboard.php?verification_success=true");
} else {
    echo "Erreur lors de la suppression de l'utilisateur : " . $stmtDeleteUser->error;
}

$stmtDeleteUser->close();
$stmtDeleteMessages->close();
$stmtDeleteUserGroups->close();
$conn->close();
?>
