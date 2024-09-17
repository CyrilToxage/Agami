<?php
// Autoriser les requêtes depuis n'importe quel domaine
header("Access-Control-Allow-Origin: *");
// Autoriser les requêtes avec les méthodes GET, POST, PUT, DELETE, OPTIONS
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Autoriser les en-têtes personnalisés et l'envoi de cookies
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Vérifier si l'ID du client à supprimer est fourni dans la requête
if (isset($_POST['cliID']) && isset($_POST['userName']) && isset($_POST['userPassword'])) {

    // Récupérer l'ID du client depuis la requête
    $cliID = $_POST['cliID'];
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

    // Check if admin user is found
    if ($result->num_rows == 1) {

        // Recuperer les photos de cliID
        $stmt = $conn->prepare('SELECT phoNom FROM photo_client WHERE phoCliID = ?');
        $stmt->bind_param("i", $cliID);
        $stmt->execute();
        $result = $stmt->get_result();

        // Supprimer les fichier photos
        while ($row = $result->fetch_assoc()) {
            if (file_exists(__DIR__ . '/client/photos/' . $row['phoNom'])) {
                unlink(__DIR__ . '/client/photos/' . $row['phoNom']);
            }
        }

        // Supprimer les photos de la DB
        $stmt = $conn->prepare("DELETE FROM photo_client WHERE phoCliID = ?");
        $stmt->bind_param("i", $cliID);
        $stmt->execute();


        // Récuperer le nom du document du client
        $stmt = $conn->prepare("SELECT cliDocOC FROM client WHERE cliID = ?");
        $stmt->bind_param("i", $cliID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Supprimer le document
        if (file_exists(__DIR__ . '/client/documents/' . $row['cliDocOC'])) {
            unlink(__DIR__ . '/client/documents/' . $row['cliDocOC']);
        }


        // Supprimer le client
        $stmt = $conn->prepare("DELETE FROM client WHERE cliID = ?");
        $stmt->bind_param("i", $cliID);
        $stmt->execute();

    } else {

        die(
            json_encode(
                array(
                    'status' => 'fail',
                    'data' => array(
                        'message' => "Erreur de ché pas quoi mais t'es pas autorisé"
                    )
                )
            )
        );

    }

} else {
    echo "ID du client non fourni.";
}
?>