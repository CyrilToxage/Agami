<?php
// Autoriser les requêtes depuis n'importe quel domaine
header("Access-Control-Allow-Origin: *");
// Autoriser les requêtes avec les méthodes GET, POST, PUT, DELETE, OPTIONS
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Autoriser les en-têtes personnalisés et l'envoi de cookies
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userName"]) && isset($_POST["userPassword"])) {

    $userName = mb_substr(trim($_POST['userName']), 0, 50);
    $userPassword = mb_substr(trim($_POST['userPassword']), 0, 50);

    // Connexion à la base de données
    require_once 'connexion.php';

    // Vérifier la connexion
    if ($conn->connect_error) {

        die(
            json_encode(
                array(
                    'status' => 'fail',
                    'data' => array(
                        'message' => "La connexion allah base de données a échoué"
                    )
                )
            )
        );
    }

    $stmt = $conn->prepare('SELECT * FROM user WHERE userName = ? AND userPassword = ?');
    $stmt->bind_param("ss", $userName, $userPassword);
    $stmt->execute();

    $result = $stmt->get_result();

    // Check if user is found
    if($result->num_rows == 1)
    {

        $res_user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        die(
            json_encode(
                array(
                    'status' => 'success',
                    'data' => array(
                        'user' => $res_user
                    )
                )
            )
        );

    }
    else
    {

        $stmt->close();
        $conn->close();

        die(
            json_encode(
                array(
                    'status' => 'fail',
                    'data' => array(
                        'message' => "Identifiants incorrects"
                    )
                )
            )
        );

    }

}

die(
    json_encode(
        array(
            'status' => 'fail',
            'data' => array(
                'message' => "Erreur de formulaire"
            )
        )
    )
);