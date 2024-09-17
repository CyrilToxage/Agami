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

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["mail"]) && isset($_POST["numero"]) && isset($_POST["type"]) && isset($_POST["description"]) && isset($_POST["sexe"]) && isset($_POST["rue"]) && isset($_POST["ville"]) && isset($_POST["soustype"]) && isset($_POST["datestimes"])) {

    // Variables
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $mail = trim($_POST["mail"]);
    $numero = trim($_POST["numero"]);
    $type = intval(trim($_POST["type"]));
    $description = trim($_POST["description"]);
    $sexe = trim($_POST["sexe"]);
    $rue = trim($_POST["rue"]);
    $ville = trim($_POST["ville"]);
    $soustype = intval(trim($_POST["soustype"]));
    $datestimes = $_POST["datestimes"];


    // Verification du nom
    if (strlen($nom) < 2 || strlen($nom) > 50)
        die(
            json_encode(
                array(
                    'status' => 'fail',
                    'data' => array(
                        'message' => 'Votre nom doit faire entre 2 et 50 caractères'
                    )
                )
            )
        );

    // Verification du prenom
    if (strlen($prenom) < 2 || strlen($prenom) > 50)
        die(
            json_encode(
                array(
                    'status' => 'fail',
                    'data' => array(
                        'message' => 'Votre prenom doit faire entre 2 et 50 caractères'
                    )
                )
            )
        );

    // Verification du mail
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || strlen($mail) > 50)
        die(
            json_encode(
                array(
                    'status' => 'fail',
                    'data' => array(
                        'message' => 'Adresse mail invalide'
                    )
                )
            )
        );


    // Verification du type
    if ($type < 1 || $type > 4)
        die(
            json_encode(
                array(
                    'status' => 'fail',
                    'data' => array(
                        'message' => 'Type invalide'
                    )
                )
            )
        );

    // Verification du sous-type
    if ($type == 1)
        $soustype = 0;

    // Connexion à la base de données
    require_once 'connexion.php';

    // Vérifier la connexion
    if ($conn->connect_error == "") {

        // Début de la transaction
        $conn->begin_transaction();

        // Préparer la requête d'insertion dans la table client
        $stmtClient = $conn->prepare("INSERT INTO client (cliNom, cliPrenom, cliMail, cliNumero, cliTypID, cliDescription, cliSexe, cliRue, cliVille, cliSouID, cliDatesTimes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtClient->bind_param("ssssissssis", $nom, $prenom, $mail, $numero, $type, $description, $sexe, $rue, $ville, $soustype, $datestimes);

        // Exécuter la requête et verifier
        if ($stmtClient->execute() !== TRUE) {

            $conn->rollback();
            $stmtClient->close();
            $conn->close();

            die(
                json_encode(
                    array(
                        'status' => 'fail',
                        'data' => array(
                            'message' => 'Une erreur s\'est produite lors de l\'ajout à la base de données'
                        )
                    )
                )
            );

        }


        // Récuperer l'ID du client qui a été inseré
        $insertedClientId = $conn->insert_id;

        $stmtClient->close();

        if (isset($_FILES['dococFile'])) {

            if ($_FILES['dococFile']['error'] != UPLOAD_ERR_NO_FILE) {

                $dococFile = $_FILES['dococFile'];

                // Définir le chemin du dossier ou sera upload le dococ
                $uploadDir = __DIR__ . '/client/documents/';

                // Vérifier si le répertoire existe, sinon le créer
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0777, true);

                // Verification de l'extension du fichier
                $dococFileExtension = pathinfo($dococFile['name'], PATHINFO_EXTENSION);
                if (strtolower($dococFileExtension) == "pdf") {

                    // Créer le nom du fichier a upload
                    $uploadDococFileName = md5(uniqid(rand(), true)) . "." . $dococFileExtension;

                    // Upload le fichier et vérifier
                    if (move_uploaded_file($dococFile['tmp_name'], $uploadDir . $uploadDococFileName)) {

                        // Insérer la photo dans la table photo_client
                        $stmtPhoto = $conn->prepare("UPDATE client SET cliDocOC = ? WHERE cliID = ?");
                        $stmtPhoto->bind_param("si", $uploadDococFileName, $insertedClientId);
                        $stmtPhoto->execute();

                        $stmtPhoto->close();

                    }

                }

            }

        }


        if (isset($_FILES['photos'])) {

            $photos = $_FILES['photos'];

            // Définir le chemin du dossier ou sera upload la photo
            $uploadDir = __DIR__ . '/client/photos/';

            // Vérifier si le répertoire existe, sinon le créer
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0777, true);

            // Verifie si il y a des photos
            if (count($photos) > 0) {

                $i = 0;

                // Parcour le tableau
                foreach ($photos['tmp_name'] as $key => $tmp_name) {
                    if ($i++ == 5)
                        break;
                    $photo_name = $photos['name'][$key];
                    $photo_tmp_name = $photos['tmp_name'][$key];

                    // Verification de l'extension du fichier
                    $fileExtension = pathinfo($photo_name, PATHINFO_EXTENSION);
                    $allowedTypes = array('png', 'jpg', 'jpeg');
                    if (in_array(strtolower($fileExtension), $allowedTypes)) {

                        // Créer le nom du fichier a upload
                        $uploadFileName = md5(uniqid(rand(), true)) . "." . $fileExtension;

                        // Upload le fichier et vérifier
                        if (move_uploaded_file($photo_tmp_name, $uploadDir . $uploadFileName)) {

                            // Insérer la photo dans la table photo_client
                            $stmtPhoto = $conn->prepare("INSERT INTO photo_client (phoNom, phoCliID) VALUES (?, ?)");
                            $stmtPhoto->bind_param("si", $uploadFileName, $insertedClientId);
                            $stmtPhoto->execute();

                            $stmtPhoto->close();

                        }

                    }

                }

            }

        }

        // Fin du script. Aucune erreur
        $conn->commit();
        $conn->close();

        die(
            json_encode(
                array(
                    'status' => 'success',
                    'data' => array(
                        'message' => "Votre message a été envoyé à Agami Light"
                    )
                )
            )
        );

    } else {

        die(
            json_encode(
                array(
                    'status' => 'fail',
                    'data' => array(
                        'message' => "Erreur de connexion à la base de données"
                    )
                )
            )
        );

    }

} else {

    die(
        json_encode(
            array(
                'status' => 'fail',
                'data' => array(
                    'message' => "Le formulaire n\'a pas été soumis correctement"
                )
            )
        )
    );

}