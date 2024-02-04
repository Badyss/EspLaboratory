<?php

class Post 
{
    private $error = "";

    public function create_post($user_id, $data)
    {
        if (!empty($data['post']))
        {
            $post = addslashes($data['post']);
            $titre = addslashes($data['titre']);
            $post_id = $this->create_post_id();

            $conn = new mysqli("websithbadyss.mysql.db", "websithbadyss", "Ilian5721", "websithbadyss");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Préparer et exécuter la requête
            $query = "INSERT INTO Posts (user_id, post_id, post, titre, date) VALUES ('$user_id', '$post_id', '$post', '$titre', NOW())";
            $result = $conn->query($query);
            //if ($result === TRUE) {
                // Succès
            //    echo "Nouveau post ajouté avec succès.";
            //} else {
                // Échec
            //    echo "Erreur lors de l'ajout du post : " . $conn->error;
            //}

            // Fermer la connexion
            $conn->close();
        }
        else
        {
            $this->error .= "Post vide<br>";
        }
        return $this->error;
    }

    public function get_posts()
    {
    $conn = new mysqli("websithbadyss.mysql.db", "websithbadyss", "Ilian5721", "websithbadyss");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM Posts ORDER BY id DESC LIMIT 10";
    $result = $conn->query($query);

    if ($result === false) {
        $conn->close();
        return []; 
    }

    $conn->close();

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }

    return $posts;
    }

    private function create_post_id()
    {
        $length = rand(4, 19);
        $num = "";
        for ($i = 0; $i < $length; $i++) {
            $new_rand = rand(0, 9);
            $num = $num . $new_rand;
        }

        return $num;
    }
}

?>
