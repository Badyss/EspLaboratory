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

$sqlGetCurrentAdminStatus = "SELECT admin FROM Utilisateur WHERE id = ?";
$stmtGetCurrentAdminStatus = $conn->prepare($sqlGetCurrentAdminStatus);

$stmtGetCurrentAdminStatus->bind_param("i", $userId);
$stmtGetCurrentAdminStatus->execute();
$stmtGetCurrentAdminStatus->store_result();
$stmtGetCurrentAdminStatus->bind_result($currentAdminStatus);
$stmtGetCurrentAdminStatus->fetch();

$newAdminStatus = $currentAdminStatus == 1 ? 0 : 1;
$sqlUpdateAdminStatus = "UPDATE Utilisateur SET admin = ? WHERE id = ?";
$stmtUpdateAdminStatus = $conn->prepare($sqlUpdateAdminStatus);

if ($stmtUpdateAdminStatus === false) {
    die('Erreur de préparation de la requête : ' . $conn->error);
}

$stmtUpdateAdminStatus->bind_param("ii", $newAdminStatus, $userId);

if (!$stmtUpdateAdminStatus->execute()) {
    echo "Erreur lors de la mise à jour du statut admin.";
    exit;
}

$stmtGetCurrentAdminStatus->close();
$stmtUpdateAdminStatus->close();
$conn->close();
header("Location: dashboard.php?verification_success=true");
exit;
?>
