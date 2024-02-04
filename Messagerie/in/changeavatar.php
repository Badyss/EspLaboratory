<?php
if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'])) {
    $tailleMax = 2097152;
    $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
    $cheminDossier = "avatars/";
    
    $path_parts = pathinfo($_FILES['avatar']['name']);
    $extensionUpload = strtolower($path_parts['extension']);

    $ancienChemin = $cheminDossier . $getID . "." . $extensionUpload;

    if (file_exists($ancienChemin)) {
        unlink($ancienChemin);
    }

    $nouveauChemin = $cheminDossier . $getID . "." . $extensionUpload;

    $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $nouveauChemin);

    if($resultat) {
        $updateavatar = $bdd->prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
        $updateavatar->execute(array(
            'avatar' => $getID . "." . $extensionUpload,
            'id' => $getID
        ));
        header("Location: profil.php?nom=$nom&prenom=$prenom&formation=$formation&id=$UserID");
    } else {
        $msg = "Erreur durant l'importation de votre photo de profil";
    }
} else {
    $msg = "Aucun fichier n'a été sélectionné";
}
?>

?>