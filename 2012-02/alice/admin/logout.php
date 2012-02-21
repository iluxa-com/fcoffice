<?php
require_once '../config.php';
unset($_SESSION['ALICE_ADMIN_ACL']);
header('Location: login.php');
?>