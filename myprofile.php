<?php
require("forced.php");
$title = "Page de profil";
include "templates/header.php";
include "templates/nav.php";
require_once 'lib_login.php';

$cookie_data = connect_infos();
$user_data = search_user(strtolower($cookie_data["USERNAME"]));
?>

<div id="joli">__________</div>
<div class="container">
    <h1>Bienvenue sur la page de profil de <?php echo htmlspecialchars($user_data["USERNAME"]); ?>
    </h1>
    <h3><?php if ($user_data["admin"] == 1) printf("L'utilisateur est administrateur");
        else printf("L'utilisateur n'est pas administrateur"); ?></h3>
    <br><br>
    <br><br>
    <h2>Description de l'utilisateur :</h2>
    <div id="desc">
        <?php echo htmlspecialchars($user_data["DESCRIPTION"]); ?>
    </div>
    <br><br>
    <h2>Envoyer un message à l'utilisateur :</h2>
    <textarea class="textarea">Message to send</textarea>
    <br>
    <button>Envoyer</button>
</div>

<?php include "templates/footer.php";
