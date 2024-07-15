<?php
require("forced.php");

$UPLOADED = 0;
$ERROR_MSG = "";

if (isset($_FILES["filecontent"]) && isset($_REQUEST["description"])) {
    $allowed_types = array("image/jpeg", "image/png", "application/pdf");
    $max_size = 20 * 1024 * 1024; // 20MB
    $file_type = $_FILES["filecontent"]["type"];
    $file_size = $_FILES["filecontent"]["size"];
    $description = $_REQUEST["description"];

    if (!ctype_alnum($description)) {
        $ERROR_MSG = "La description ne doit contenir que des caractères alphanumériques.";
    } elseif (!in_array($file_type, $allowed_types)) {
        $ERROR_MSG = "Le type de format n'est pas valide. Seuls les formats suivants sont supportés: JPEG, PNG, et PDF.";
    } elseif ($file_size > $max_size) {
        $ERROR_MSG = "La taille du fichier dépasse la limite de 20MB.";
    } else {
        $unique_id = uniqid();
        $file_extension = pathinfo($_FILES["filecontent"]["name"], PATHINFO_EXTENSION);
        $new_filename = pathinfo($_FILES["filecontent"]["name"], PATHINFO_FILENAME) . $unique_id . "." . $file_extension;
        $target_dir = "/var/www/html/files/" . $__connected["USERNAME"] . "/";
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["filecontent"]["tmp_name"], $target_file)) {
            file_put_contents($target_file . ".alexdescfile", $description);
            $UPLOADED = 1;
        } else {
            $ERROR_MSG = "Une erreur est survenue.";
        }
    }
}
?>
<html>

<head>
    <title>Accueil</title>
</head>

<body>
    <div id="menu">
        <img src="/alexcloud.png" />
        <div class="menu_entries"><a href="/logout.php">Disconnect</a></div>
        <?php if ($__connected["ADMIN"] == 1) printf('<div class="menu_entries"><a href="/admin.php">Admin Page</a></div>'); ?>
        <div class="menu_entries"><a href="/p/<?php printf("%s", $__connected["USERNAME"]); ?>.php">My Profile</a></div>
    </div>
    <div id="joli">__________</div>
    <div id="app">
        <?php
        if ($UPLOADED == 1) {
            printf('<div id="uploaded">Fichier upload avec succès</div>');
        } elseif ($ERROR_MSG != "") {
            printf('<div id="error">%s</div>', $ERROR_MSG);
        }
        ?>
        <div id="app-form">
            <form action="#" method="POST" enctype="multipart/form-data">
                <input type="text" name="description" placeholder="File Description" />
                <input type="file" name="filecontent" />
                <input type="submit" value="Upload File" />
            </form>
        </div>
        <div id="app-files">
            <?php
            $files = scandir("/var/www/html/files/" . $__connected["USERNAME"] . "/");
            foreach ($files as $file) {
                if ($file != "." && $file != ".." && !str_contains($file, ".alexdescfile")) {
                    printf("<div class='oui' fname='%s'>%s (%s)<button onclick='delete_file(this);'>Delete</button><button onclick='download_file(this);'>Download</button></div>", $file, $file, file_get_contents("/var/www/html/files/" . $__connected["USERNAME"] . "/" . $file . ".alexdescfile"));
                }
            }
            ?>
        </div>
    </div>
</body>
<style>
    html {
        background: rgb(2, 0, 36);
        background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 35%, rgba(0, 212, 255, 1) 100%);
    }

    html,
    body,
    div {
        margin: 0;
        padding: 0;
    }

    * {
        position: relative;
        transition: all 1s;
        text-decoration: none;
        list-style: none;
    }

    #menu {
        height: 16%;
        width: 80%;
        background: rgba(255, 255, 255, 0.6);
        margin-left: 10%;
    }

    #menu img {
        height: 70%;
        top: 15%;
    }

    #menu div {
        height: 100%;
        background: rgba(0, 0, 0, 0.1);
        float: right;
        padding: 0 2%;
        margin-left: 2%;
    }

    #menu div a {
        top: 45%;
    }

    #joli {
        width: 80%;
        background: #449;
        left: 10%;
    }

    #app {
        width: 76%;
        background: rgba(255, 255, 255, 0.6);
        margin-left: 10%;
        padding: 2%;
    }

    form {
        padding: 1%;
        background: rgba(255, 255, 255, 0.1);
        width: 98%;
    }

    input {
        padding: 1% 2%;
    }

    input[type="text"] {
        width: 45%;
    }

    #uploaded {
        width: 98%;
        padding: 1%;
        background: rgba(100, 255, 100, 0.1);
        margin: 2% 0%;
    }

    #error {
        width: 98%;
        padding: 1%;
        background: rgba(255, 100, 100, 0.1);
        margin: 2% 0%;
        color: red;
    }

    #app-files {
        width: 100%;
        background: rgba(255, 255, 255, 0.6);
    }

    #app-files .oui {
        width: 96%;
        margin-bottom: 1%;
        background: rgba(50, 50, 255, 0.3);
        padding: 2%;
    }

    #app-files button {
        float: right;
        padding: 10px 2%;
        transform: translateY(-10px);
        margin-left: 2%;
    }
</style>
<script>
    function delete_file(e) {
        window.location.href = "/delete_file.php?file=" + e.parentElement.getAttribute("fname");
    }

    function download_file(e) {
        window.location.href = "/download_file.php?file=" + e.parentElement.getAttribute("fname");
    }
</script>

</html>