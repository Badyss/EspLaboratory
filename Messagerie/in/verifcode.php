<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $generatedCode = $_SESSION['verification_code'];

    $userEnteredCode = $_POST['codeInput'];

    if ($generatedCode === $userEnteredCode) {
        header("Location: dashboard.php?verification_success=true");
        exit();
    } else {
        header("Location: ../../../403.html");
        exit();
    }
} else {
    echo "Invalid request.";
}
?>
