<?php

session_start();

header('Content-Type: application/json');

require_once "../db.php";
require_once "../models/User.php";

/*
|--------------------------------------------------------------------------
| Admin Authorization
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "Admin"
) {
    http_response_code(403);

    echo json_encode([
        "success" => false,
        "message" => "Unauthorized."
    ]);

    exit;
}

$userModel = new User($conn);

/*
|--------------------------------------------------------------------------
| Get Action
|--------------------------------------------------------------------------
*/

$action = $_GET["action"] ?? "";

/*
|--------------------------------------------------------------------------
| Route Request
|--------------------------------------------------------------------------
*/

switch ($action) {

    case "list":
        getUsers($userModel);
        break;

    case "add":
        addUser($userModel);
        break;

    case "update":
        updateUser($userModel);
        break;

    case "delete":
        deleteUser($userModel);
        break;

    default:
        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Invalid action."
        ]);

        break;
}

/*
|--------------------------------------------------------------------------
| List Users
|--------------------------------------------------------------------------
*/

function getUsers($userModel)
{
    $users = $userModel->getAllUsers();

    echo json_encode([
        "success" => true,
        "users" => $users
    ]);
}

/*
|--------------------------------------------------------------------------
| Add User
|--------------------------------------------------------------------------
*/

function addUser($userModel)
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);

        echo json_encode([
            "success" => false,
            "message" => "Invalid request method."
        ]);

        return;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    $firstName = trim($data["first_name"] ?? "");
    $lastName = trim($data["last_name"] ?? "");
    $email = strtolower(trim($data["email"] ?? ""));
    $password = $data["password"] ?? "";
    $role = $data["role"] ?? "";

    if (
        empty($firstName) ||
        empty($lastName) ||
        empty($email) ||
        empty($password) ||
        empty($role)
    ) {
        echo json_encode([
            "success" => false,
            "message" => "All fields are required."
        ]);

        return;
    }

    if (!str_ends_with($email, "@dlsu.edu.ph")) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid DLSU email."
        ]);

        return;
    }

    if (!in_array($role, ["Student", "Staff", "Admin"])) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid role."
        ]);

        return;
    }

    if ($userModel->emailExists($email)) {
        echo json_encode([
            "success" => false,
            "message" => "Email already exists."
        ]);

        return;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $success = $userModel->createManagedUser(
        $firstName,
        $lastName,
        $email,
        $passwordHash,
        $role
    );

    echo json_encode([
        "success" => $success,
        "user_id" => $success
            ? (int)$userModel->getLastInsertId()
            : null,
        "message" => $success
            ? "User added successfully."
            : "Failed to add user."
    ]);
}

/*
|--------------------------------------------------------------------------
| Update User
|--------------------------------------------------------------------------
*/

function updateUser($userModel)
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);

        echo json_encode([
            "success" => false,
            "message" => "Invalid request method."
        ]);

        return;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    $userId = (int)($data["user_id"] ?? 0);
    $firstName = trim($data["first_name"] ?? "");
    $lastName = trim($data["last_name"] ?? "");
    $email = strtolower(trim($data["email"] ?? ""));
    $role = $data["role"] ?? "";

    if (
        $userId <= 0 ||
        empty($firstName) ||
        empty($lastName) ||
        empty($email) ||
        empty($role)
    ) {
        echo json_encode([
            "success" => false,
            "message" => "All fields are required."
        ]);

        return;
    }

    if (!str_ends_with($email, "@dlsu.edu.ph")) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid DLSU email."
        ]);

        return;
    }

    if (!in_array($role, ["Student", "Staff", "Admin"])) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid role."
        ]);

        return;
    }

    $success = $userModel->updateManagedUser(
        $userId,
        $firstName,
        $lastName,
        $email,
        $role
    );

    echo json_encode([
        "success" => $success,
        "message" => $success
            ? "Account updated successfully."
            : "Error updating account."
    ]);
}

/*
|--------------------------------------------------------------------------
| Delete User
|--------------------------------------------------------------------------
*/

function deleteUser($userModel)
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);

        echo json_encode([
            "success" => false,
            "message" => "Invalid request method."
        ]);

        return;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    $userId = (int)($data["user_id"] ?? 0);

    if ($userId <= 0) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid user ID."
        ]);

        return;
    }

    $success = $userModel->disableAccount($userId);

    echo json_encode([
        "success" => $success,
        "message" => $success
            ? "Account deleted successfully."
            : "Error deleting account."
    ]);
}