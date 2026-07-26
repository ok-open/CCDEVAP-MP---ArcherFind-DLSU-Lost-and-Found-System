<?php

session_start();

require_once "../db.php";
require_once "../models/Users.php";

/*
|--------------------------------------------------------------------------
| Only allow POST requests
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Create Users model
|--------------------------------------------------------------------------
*/

$userModel = new Users($conn);

/*
|--------------------------------------------------------------------------
| Determine action
|--------------------------------------------------------------------------
*/

$action = $_GET["action"] ?? "";

/*
|--------------------------------------------------------------------------
| LOGIN (Default Action)
|--------------------------------------------------------------------------
*/

if ($action === "") {

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($email) || empty($password)) {
        header("Location: ../index.php?error=empty_fields");
        exit();
    }

    $email = strtolower($email);

    if (!str_ends_with($email, "@dlsu.edu.ph")) {
        header("Location: ../index.php?error=invalid_email");
        exit();
    }

    $user = $userModel->findByEmail($email);

    if (!$user) {
        header("Location: ../index.php?error=account_not_found");
        exit();
    }

    if (!password_verify($password, $user["password_hash"])) {
        header("Location: ../index.php?error=wrong_password");
        exit();
    }

    $_SESSION["user_id"] = $user["user_id"];
    $_SESSION["first_name"] = $user["first_name"];
    $_SESSION["last_name"] = $user["last_name"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["role"] = $user["role"];

    switch ($user["role"]) {

        case "Student":
            header("Location: ../pages/student/student_home.php");
            break;

        case "Staff":
            header("Location: ../pages/staff/staff_dashboard.php");
            break;

        case "Admin":
            header("Location: ../pages/admin/admin_dashboard.php");
            break;

        default:
            session_destroy();
            header("Location: ../index.php?error=unauthorized");
            break;
    }

    exit();
}

/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

if ($action === "register") {

    $firstName = trim($_POST["first_name"] ?? "");
    $lastName = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirmPassword = trim($_POST["confirm_password"] ?? "");

    if (
        empty($firstName) ||
        empty($lastName) ||
        empty($email) ||
        empty($password) ||
        empty($confirmPassword)
    ) {
        header("Location: ../pages/auth/register.php?error=empty_fields");
        exit();
    }

    $email = strtolower($email);

    if (!str_ends_with($email, "@dlsu.edu.ph")) {
        header("Location: ../pages/auth/register.php?error=invalid_email");
        exit();
    }

    if ($password !== $confirmPassword) {
        header("Location: ../pages/auth/register.php?error=password_mismatch");
        exit();
    }

    if ($userModel->emailExists($email)) {
        header("Location: ../pages/auth/register.php?error=email_exists");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $created = $userModel->createUser(
        $firstName,
        $lastName,
        $email,
        $hashedPassword
    );

    if (!$created) {
        header("Location: ../pages/auth/register.php?error=registration_failed");
        exit();
    }

    header("Location: ../index.php?success=registered");
    exit();
}

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

if ($action === "logout") {

    $_SESSION = [];
    session_destroy();

    header("Location: ../index.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| UPDATE PASSWORD
|--------------------------------------------------------------------------
*/

if ($action === "updatePassword") {

    if (!isset($_SESSION["user_id"])) {
        header("Location: ../index.php");
        exit();
    }

    $currentPassword = trim($_POST["current_password"] ?? "");
    $newPassword = trim($_POST["new_password"] ?? "");
    $confirmPassword = trim($_POST["confirm_password"] ?? "");

    if (
        empty($currentPassword) ||
        empty($newPassword) ||
        empty($confirmPassword)
    ) {
        header("Location: ../pages/student/student_manage-account.php?error=empty_fields");
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        header("Location: ../pages/student/student_manage-account.php?error=password_mismatch");
        exit();
    }

    $user = $userModel->findById($_SESSION["user_id"]);

    if (!$user) {
        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    if (!password_verify($currentPassword, $user["password_hash"])) {
        header("Location: ../pages/student/student_manage-account.php?error=wrong_password");
        exit();
    }

    if (password_verify($newPassword, $user["password_hash"])) {
        header("Location: ../pages/student/student_manage-account.php?error=same_password");
        exit();
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $userModel->updatePassword($_SESSION["user_id"], $hashedPassword);

    header("Location: ../pages/student/student_manage-account.php?success=password_updated");
    exit();
}

/*
|--------------------------------------------------------------------------
| DISABLE ACCOUNT
|--------------------------------------------------------------------------
*/

if ($action === "disableAccount") {

    if (!isset($_SESSION["user_id"])) {
        header("Location: ../index.php");
        exit();
    }

    if ($userModel->disableAccount($_SESSION["user_id"])) {

        session_unset();
        session_destroy();

        header("Location: ../index.php?success=account_disabled");
        exit();
    }

    header("Location: ../pages/student/student_manage-account.php?error=disable_failed");
    exit();
}

/*
|--------------------------------------------------------------------------
| Invalid action
|--------------------------------------------------------------------------
*/

header("Location: ../index.php");
exit();