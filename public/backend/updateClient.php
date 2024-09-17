<?php
// Autoriser les requêtes depuis n'importe quel domaine
header("Access-Control-Allow-Origin: *");
// Autoriser les requêtes avec les méthodes GET, POST, PUT, DELETE, OPTIONS
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Autoriser les en-têtes personnalisés et l'envoi de cookies
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Affiche les données reçues pour le débogage
var_dump($_POST);

// Vérifier si les données du client à éditer sont fournies dans la requête
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliID']) && isset($_POST['cliNom']) && isset($_POST['cliPrenom']) && isset($_POST['cliMail']) && isset($_POST['cliNumero']) && isset($_POST['cliTypID']) && isset($_POST["cliDescription"]) && isset($_POST["cliSexe"]) && isset($_POST["cliRue"]) && isset($_POST["cliVille"]) && isset($_POST['cliSouID']) && isset($_POST['cliValide']) && isset($_POST['userName']) && isset($_POST['userPassword'])) {
    // Récupérer les données du client depuis la requête
    $cliID = $_POST['cliID'];
    $cliNom = $_POST['cliNom'];
    $cliPrenom = $_POST['cliPrenom'];
    $cliMail = $_POST['cliMail'];
    $cliNumero = $_POST['cliNumero'];
    $cliTypID = intval(trim($_POST['cliTypID']));
    $cliDescription = trim($_POST["cliDescription"]);
    $cliSexe = trim($_POST["cliSexe"]);
    $cliRue = trim($_POST["cliRue"]);
    $cliVille = trim($_POST["cliVille"]);
    $cliSouID = intval(trim($_POST["cliSouID"]));
    $cliValide = $_POST['cliValide'];
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


    // Verification du sous-type
    if ($cliTypID == 1)
        $cliSouID = 0;


    $stmt = $conn->prepare('SELECT * FROM user WHERE userName = ? AND userPassword = ?');
    $stmt->bind_param("ss", $userName, $userPassword);
    $stmt->execute();

    $result = $stmt->get_result();

    // Check if user is found
    if ($result->num_rows == 1) {

        $stmt->close();


        // Préparer et exécuter la requête de mise à jour du client
        $stmt = $conn->prepare("UPDATE client SET cliNom = ?, cliPrenom = ?, cliMail = ?, cliNumero = ?, cliTypID = ?, cliValide = ?, cliDescription = ?, cliSexe = ?, cliRue = ?, cliVille = ?, cliSouID = ? WHERE cliID = ?");
        $stmt->bind_param("ssssiissssii", $cliNom, $cliPrenom, $cliMail, $cliNumero, $cliTypID, $cliValide, $cliDescription, $cliSexe, $cliRue, $cliVille, $cliSouID, $cliID);
        $stmt->execute();

        // Vérifier si la mise à jour a réussi
        if ($stmt->affected_rows > 0) {
            echo "Le client a été mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du client.";
        }

    } else {

        $stmt->close();
        $conn->close();

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

} else {
    echo "Données du client non fournies ou méthode de requête incorrecte.";
}
?>