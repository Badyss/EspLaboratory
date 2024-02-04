<?php
$servername = "websithbadyss.mysql.db";
$username = "websithbadyss";
$password = "Ilian5721";
$dbname = "websithbadyss";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST["mail"];

    // Générez un jeton unique
    $token = bin2hex(random_bytes(32));

    // Insérez le token dans la base de données
    $insert_token_query = $conn->prepare("INSERT INTO PasswordResetTokens (email, token, created_at_full) VALUES (?, ?, NOW())");
    $insert_token_query->bind_param("ss", $mail, $token);
    $insert_token_query->execute();
    $insert_token_query->close();

    // Construction du mail de récupération
    $subject = "Réinitialisation du mot de passe - EspLaboratory";
    $message = "
        <html>
        <head>
            <title>Réinitialisation du mot de passe - EspLaboratory</title>
            <style>
                .header {
                    background-color: rgba(35, 73, 118, 255);
                    padding: 20px;
                    border-radius: 10px 10px 0px 0px;
                    margin: 0px auto;
                    max-width: 720px;
                    margin-left: -400px;
                }
                
                .header h1 {
                    color: #ffffff;
                    font-size: 24px;
                    font-weight: bold;
                    text-align: center;
                }
                
                /* Media query for large screens (desktop) */
                
                @media (min-width: 760px) {
                    .header {
                    padding: 40px;
                    }
                }
                
                /* Corps */
                
                .body {
                    background-color: #eeeeee;
                    padding: 20px;
                    border-radius: 0px 0px 10px 10px;
                    text-align: center;
                    margin: 0px auto;
                    max-width: 760px;
                }
                
                .body h2 {
                    color: #000000;
                    font-size: 20px;
                    margin-bottom: 10px;
                }
                
                .body p {
                    color: #000000;
                    font-size: 16px;
                }
                
                /* Lien de réinitialisation */
                
                .reset-password-link {
                    color: #ffffff;
                    background-color: rgba(35, 73, 118, 255);
                    padding: 10px 20px;
                    border-radius: 4px;
                    text-decoration: none;
                    margin: 0px auto;
                    max-width: 200px;
                    margin-bottom: 20px;
                }
                
                .reset-password-link:hover {
                    background-color: rgba(35, 73, 118, 255);
                }
                
                /* Media query for small screens (mobile) */
                
                @media (max-width: 760px) {
                    .body {
                    padding: 20px;
                    margin-bottom: 20px;
                    }
                
                    .reset-password-link {
                    margin-bottom: 0px;
                    }
                }
                
                /* Lien de réinitialisation */
                
                .reset-password-link {
                    text-align: center;
                }
                
                .reset-password-link:hover {
                    background-color: rgba(35, 73, 118, 255);
                }
            </style>
        </head>
        <body>
            <div class='header'>
            <h1>EspLaboratory</h1>
            </div>

            <div class='body'>
            <h2>Vous avez demandé une réinitialisation de votre mot de passe.</h2></br>
            <p>Cliquez sur le lien suivant pour réinitialiser votre mot de passe :</p></br>
            <p><a href='http://websiteisfy.fr/esplab.fr/Messagerie/in/recuperation.php?token=$token' class='reset-password-link'>Réinitialiser le mot de passe</a></p></br>
            <p>Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet e-mail.</p></br>
            <p>Ceci est un message automatique merci de pas y répondre.</p>
            </div>
        </body>
        </html>";

    // En-têtes MIME pour le contenu HTML
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: ESPLAB"; 

    // Envoi de l'e-mail
    if (mail($mail, $subject, $message, $headers)) {
        header("Location: ../forget.html?mailsent=true");
        exit;
    } else {
        echo "Erreur lors de l'envoi de l'e-mail.";
    }
}
$conn->close();
?>
