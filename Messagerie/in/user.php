<?php

class User
{
    public function get_data($id)
    {
        $conn = new mysqli("websithbadyss.mysql.db", "websithbadyss", "Ilian5721", "websithbadyss");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $query = "SELECT * FROM Utilisateur where user_id ='$id' limit 1";
        $result = $conn->query($query);

        if ($result)
        {
            $row = $result[0];
            return $row;
        } else{
           return false;
        }

        $conn->close();
    }

    public function get_user($id)
    {
    $conn = new mysqli("websithbadyss.mysql.db", "websithbadyss", "Ilian5721", "websithbadyss");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM Utilisateur WHERE id = '$id' LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Utiliser fetch_assoc pour obtenir un tableau associatif
        $row = $result->fetch_assoc();

        // Fermer la connexion
        $conn->close();

        return $row;
    } else {
        $conn->close();
        return false;
    }
}

}

?>