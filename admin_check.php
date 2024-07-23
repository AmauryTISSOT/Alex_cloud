<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['admin'] != 1) {
    header('Location: login.php');
    exit();
}
