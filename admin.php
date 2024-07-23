<?php
require("forced.php");
$title = "Page d'administration";
include "templates/header.php";
include "templates/nav.php";
?>
<div id="joli">__________</div>
<div class="container">
    <h1>Liste des Emails</h1>
    <?php
    require_once 'singleton/Database.php';
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    try {
        // Requête SQL pour sélectionner tous les emails
        $sql = "SELECT email FROM newsletter";
        $stmt = $pdo->query($sql);

        // Vérification  et affichage des résultats
        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr><th>Email</th></tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>" . htmlspecialchars($row['email']) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Aucun résultat trouvé.</p>";
        }
    } catch (PDOException $e) {
        // Gestion des erreurs de connexion
        echo "Erreur de connexion : " . $e->getMessage();
    }
    ?>
</div>
<?php include 'templates/footer.php'; ?>