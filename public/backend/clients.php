<?php
// Autoriser les requêtes depuis n'importe quel domaine
header("Access-Control-Allow-Origin: *");
// Autoriser les requêtes avec les méthodes GET, POST, PUT, DELETE, OPTIONS
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Autoriser les en-têtes personnalisés et l'envoi de cookies
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// Répondre aux requêtes OPTIONS avec un code de succès
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}


if(isset($_POST['userName']) && isset($_POST['userPassword'])) {
    
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
                        'message' => "La connexion à la base de données a échoué"
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
    if($result->num_rows == 1) {

        $stmt->close();


        // Récupérer les clients depuis la base de données
        $sql = "SELECT * FROM client ORDER BY cliID DESC";
        $result = $conn->query($sql);

        $clients = array();

        // Si des clients sont trouvés, les récupérer et les stocker dans un tableau
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $clientId = $row['cliID']; // Supposons que la clé primaire du client est 'id'

                // Récupérer les photos pour ce client
                $photos = array();
                $photoStmt = $conn->prepare("SELECT phoNom AS photoNom FROM photo_client WHERE phoCliID = ?");
                $photoStmt->bind_param("i", $clientId);
                $photoStmt->execute();
                $photoResult = $photoStmt->get_result();

                if ($photoResult->num_rows > 0) {
                    while ($photo = $photoResult->fetch_assoc()) {
                        $photos[] = $photo;
                    }
                }
                $photoStmt->close();

                // Ajouter les photos au tableau associatif du client
                $row['cliPhotos'] = $photos;

                // Ajouter le client au tableau des clients
                $clients[] = $row;
            }
        }
        
        // Fermer la connexion à la base de données
        $conn->close();

        // Convertir le tableau des clients en format JSON et le renvoyer
        die(json_encode($clients));

    }
    else {

        die(
            json_encode(
                array(
                    'status' => 'fail',
                    'data' => array(
                        'message' => "Pas autorisé"
                    )
                )
            )
        );

    }

}

?>