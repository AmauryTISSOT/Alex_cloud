<?php
require("forced.php");
$title = "Accueil";
include "templates/header.php";
include "templates/nav.php";

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
<script>
    function delete_file(e) {
        window.location.href = "/delete_file.php?file=" + e.parentElement.getAttribute("fname");
    }

    function download_file(e) {
        window.location.href = "/download_file.php?file=" + e.parentElement.getAttribute("fname");
    }
</script>

<?php include 'templates/footer.php'; ?>