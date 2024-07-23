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
            // Génère un 16-byte aléatoire
            $salt = bin2hex(random_bytes(16));
            $combinedPassword = $password . $salt;
            // Utilisation d'un algorithme de hachage
            $hashedpassword = password_hash($combinedPassword, PASSWORD_BCRYPT);

            // Préparer la requête SQL pour insérer un nouvel utilisateur
            $sql = "INSERT INTO users (USERNAME, PASSWORD, DESCRIPTION, admin, salt) VALUES (:username, :password, :description, 0, :salt)";
            $stmt = $pdo->prepare($sql);
            // Lier les paramètres
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedpassword, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':salt', $salt, PDO::PARAM_STR);
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
    // Obtenir les informations de l'utilisateur depuis la base de données
    $user_data = search_user($username);

    // Démarrage de la session
    session_start();
    // Vérifie si l'utilisateur existe
    if ($user_data) {
        // Récupère le mot de passe haché stocké et le sel
        $storedHashedPassword = $user_data["PASSWORD"];
        $storedSalt = $user_data["salt"];

        // Combine le mot de passe fourni avec le sel stocké pour la vérification
        $combinedPassword = $password . $storedSalt;

        // Vérifie le mot de passe fourni avec le mot de passe haché stocké
        if (password_verify($combinedPassword, $storedHashedPassword)) {
            // Définir un cookie de session si les informations de connexion sont correctes
            setcookie("ALEXSESSID", base64_encode($user_data["USERNAME"] . "#" . $user_data["admin"]));
            $_SESSION['username'] = $user_data['USERNAME'];
            $_SESSION['admin'] = $user_data['admin'];
            return true;
        }
    }
    return false;
}

// Fonction pour déconnecter un utilisateur
function logout_user()
{
    // Définir un cookie de session expiré
    setcookie("ALEXSESSID", "", time() - 3600);
    session_start();
    session_unset();
    session_destroy();
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
