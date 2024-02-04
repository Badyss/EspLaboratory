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

// Récupérer le token depuis l'URL
$token = $_GET["token"];
// Rechercher le token dans la base de données pour obtenir l'email associé
$find_email_query = $conn->prepare("SELECT email FROM PasswordResetTokens WHERE token = ? AND used = 0 AND created_at_full >= NOW() - INTERVAL 1 HOUR");
$find_email_query->bind_param("s", $token);
$find_email_query->execute();
$find_email_query->store_result();

// Si le token est trouvé, récupérer l'email associé
if ($find_email_query->num_rows > 0) {
    $find_email_query->bind_result($email);
    $find_email_query->fetch();

    // Afficher le formulaire de réinitialisation du mot de passe
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="utf-8">
      <link rel="stylesheet" type="text/css" href="../css/register.css">
      <link rel="shortcut icon" href="../../logos/logo.png" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
      <title> EspLaboratory - Réinitialisation du mot de passe</title>
    </head>
    <body>
      <div class="formbold-main-wrapper">
      <div class="formbold-form-wrapper">
        <div class="imagecentre">
          <img src="../../logos/login.jpg" alt="Login Image">
        </div>
        <div class="mailsend" id="mailsend">Mail envoyé avec succès</div>
        <form action="traitement_recuperation.php" method="post">
          <div class="formbold-form-title">
            <h2>Réinitialisation du mot de passe</h2>
          </div>
          <div class="formbold-mb-3">
            <div>
                <input type="hidden" name="email" value="' . $email . '">
                <label for="password" class="formbold-form-label">Nouveau mot de passe:</label>
                <input type="password" name="password" class="formbold-form-input">
            </div>
          </div>
          <div class="formbold-mb-3">
            <label for="confirm_password" class="formbold-form-label">Confirmer le nouveau mot de passe:</label>
            <input type="password" name="confirm_password" required class="formbold-form-input">
          </div>
          <button class="formbold-btn" type="submit" style="height:100%">Changer le mot de passe</button>
        </form>
      </div>
    </div>
    </body>
    </html>';
} else {
    // Le token est invalide ou expiré
    echo "Le lien de réinitialisation du mot de passe est invalide ou a expiré.";
}

$find_email_query->close();
$conn->close();
?>
