<?php
require("lib_login.php");
logout_user();
header("Location: /");
die();
