<?php
// Assurez-vous que votre connexion à la base de données est établie ici
$servername = "websithbadyss.mysql.db";
$username = "websithbadyss";
$password = "Ilian5721";
$dbname = "websithbadyss";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Vérifier si les mots de passe correspondent
    if ($password == $confirm_password) {
        // Hasher le nouveau mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Mettre à jour le mot de passe dans la table des utilisateurs
        $update_password_query = $conn->prepare("UPDATE Utilisateur SET motdepasse = ? WHERE mail = ?");
        $update_password_query->bind_param("ss", $hashed_password, $email);
        $update_password_query->execute();
        $update_password_query->close();

        // Marquer le token comme utilisé dans la table des tokens
        $update_token_query = $conn->prepare("UPDATE PasswordResetTokens SET used = 1 WHERE token = ?");
        $update_token_query->bind_param("s", $token);
        $update_token_query->execute();
        $update_token_query->close();

        header("Location: ../login.html?mailsent=true");
    } else {
        echo "Les mots de passe ne correspondent pas.";
    }
}
else {
    echo 'erreur';
}

$conn->close();
?>
