<?php
session_start();

$subject = "Code de vérification";
$mail = "badyss572@gmail.com";

$code = sprintf("%06d", rand(0, 999999)); // Changement pour générer un code à 6 chiffres

$message = "
<html>
<head>
  <title>Code de vérification</title>
  <style>
    /* Global */
    body {
      font-family: sans-serif;
      font-size: 16px;
      margin: 0;
      padding: 0;
    }

    a {
      text-decoration: none;
      color: #00A5FF;
    }

    /* header */
    .header {
      background-color: rgba(35, 73, 118, 255);
      padding: 20px;
      border-radius: 10px 10px 0px 0px;
      text-align: center;
      max-width: 720px;
    }

    .header h1 {
      color: #ffffff;
      font-size: 24px;
      font-weight: bold;
    }

    /* Body */
    .body {
      background-color: #eeeeee;
      padding: 20px;
      border-radius: 0px 0px 10px 10px;
      text-align: center;
      max-width: 720px;
    }

    .body h1 {
      color: #333333;
      font-size: 24px;
      font-weight: bold;
    }

    .body p {
      color: #000000;
      font-size: 16px;
    }

    /* Lien de vérification */
    .verification-link {
      color: #ffffff;
      background-color: #00A5FF;
      padding: 10px 16px;
      border-radius: 4px;
      text-decoration: none;
      margin: 0px auto;
      max-width: 200px;
      width: 100%;
      display: inline-block;
      margin-bottom: 16px;
    }

    .verification-link:hover {
      background-color: #0088FF;
    }

    /* Media query pour les petits écrans (mobile) */
    @media (max-width: 760px) {
      /* Body */
      .body {
        padding: 20px;
        margin-bottom: 20px;
      }

      /* Lien de vérification */
      .verification-link {
        margin-bottom: 0px;
      }

      .verification-link:hover {
        background-color: #0088FF;
      }
    }
  </style>
</head>
<body>
  <div class='header'>
    <h1>EspLaboratory</h1>
  </div>

  <div class='body'>
    <h1>Votre code de vérification à 6 chiffres est :</h1>
    <p>$code</p>
  </div>
</body>
</html>";

$_SESSION['verification_code'] = $code;

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: ESPLAB";

if (mail($mail, $subject, $message, $headers)) {
  header("Location: dashboard.php?verification_sent=true");
  exit();
} else {
  header("Location: dashboard.php?verification_sent=false");
  exit();
}
?>
