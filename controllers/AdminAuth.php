<?php

session_start();

/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
| Ensures that:
| 1. The user is logged in.
| 2. The logged-in user has the Admin role.
*/

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SESSION["role"] !== "Admin") {
    session_destroy();
    header("Location: ../../index.php");
    exit();
}

?>