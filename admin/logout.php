<?php
require_once __DIR__ . '/includes/auth.php';
de_logout();
header('Location: login.php');
exit;
