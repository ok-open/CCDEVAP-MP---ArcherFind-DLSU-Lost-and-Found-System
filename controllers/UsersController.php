<?php

session_start();

require_once "../db.php";
require_once "../models/Users.php";

/*
|--------------------------------------------------------------------------
| Create Users Model
|--------------------------------------------------------------------------
*/

$userModel = new Users($conn);

/*
|--------------------------------------------------------------------------
| Manage Account Redirect by Role
|--------------------------------------------------------------------------
*/

function getManageAccountPageByRole(): string
{
    $role = $_SESSION["role"] ?? "";

    if ($role === "Admin") {
        return "../pages/admin/admin_manage-account.php";
    }

    if ($role === "Staff") {
        return "../pages/staff/staff_manage-account.php";
    }

    return "../pages/student/student_manage-account.php";
}

/*
|--------------------------------------------------------------------------
| Determine Action
|--------------------------------------------------------------------------
*/

$action = $_GET["action"] ?? "";

/*
|--------------------------------------------------------------------------
| ADMIN MANAGE USERS ACTIONS
|--------------------------------------------------------------------------
|
| These actions return JSON because they are called using fetch()
| from the Admin Manage Users page.
|
*/

if (in_array($action, ["list", "add", "update", "delete", "resetPassword"])) {

    header("Content-Type: application/json");

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

    /*
    |--------------------------------------------------------------------------
    | LIST USERS
    |--------------------------------------------------------------------------
    */

    if ($action === "list") {

        $users = $userModel->getAllUsers();

        echo json_encode([
            "success" => true,
            "users" => $users
        ]);

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | ADD USER
    |--------------------------------------------------------------------------
    */

    if ($action === "add") {

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {

            http_response_code(405);

            echo json_encode([
                "success" => false,
                "message" => "Invalid request method."
            ]);

            exit;
        }

        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $firstName = trim($data["first_name"] ?? "");
        $lastName = trim($data["last_name"] ?? "");
        $email = strtolower(trim($data["email"] ?? ""));
        $password = $data["password"] ?? "";
        $role = $data["role"] ?? "";

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

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

            exit;
        }

        if (!str_ends_with($email, "@dlsu.edu.ph")) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid DLSU email."
            ]);

            exit;
        }

        if (!in_array($role, ["Student", "Staff", "Admin"])) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid role."
            ]);

            exit;
        }

        if ($userModel->emailExists($email)) {

            echo json_encode([
                "success" => false,
                "message" => "Email already exists."
            ]);

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

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

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    if ($action === "update") {

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {

            http_response_code(405);

            echo json_encode([
                "success" => false,
                "message" => "Invalid request method."
            ]);

            exit;
        }

        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $userId = (int)($data["user_id"] ?? 0);

        $firstName = trim(
            $data["first_name"] ?? ""
        );

        $lastName = trim(
            $data["last_name"] ?? ""
        );

        $email = strtolower(
            trim($data["email"] ?? "")
        );

        $role = $data["role"] ?? "";

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

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

            exit;
        }

        if (!str_ends_with($email, "@dlsu.edu.ph")) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid DLSU email."
            ]);

            exit;
        }

        if (!in_array($role, ["Student", "Staff", "Admin"])) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid role."
            ]);

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | Update User
        |--------------------------------------------------------------------------
        */

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

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE / DISABLE USER
    |--------------------------------------------------------------------------
    */

    if ($action === "delete") {

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {

            http_response_code(405);

            echo json_encode([
                "success" => false,
                "message" => "Invalid request method."
            ]);

            exit;
        }

        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $userId = (int)($data["user_id"] ?? 0);

        if ($userId <= 0) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid user ID."
            ]);

            exit;
        }

        $success = $userModel->disableAccount(
            $userId
        );

        echo json_encode([
            "success" => $success,

            "message" => $success
                ? "Account deleted successfully."
                : "Error deleting account."
        ]);

        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Only Allow POST For Remaining Actions
|--------------------------------------------------------------------------
|
| Login, registration, password changes, etc.
|
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../index.php");

    exit;
}

/*
|--------------------------------------------------------------------------
| RESET USER PASSWORD
|--------------------------------------------------------------------------
*/

if ($action === "resetPassword") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {

        http_response_code(405);

        echo json_encode([
            "success" => false,
            "message" => "Invalid request method."
        ]);

        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    $userId = (int)($data["user_id"] ?? 0);
    $password = trim($data["password"] ?? "");

    if ($userId <= 0 || empty($password)) {

        echo json_encode([
            "success" => false,
            "message" => "Password is required."
        ]);

        exit;
    }

    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $success = $userModel->resetManagedUserPassword(
        $userId,
        $hashedPassword
    );

    echo json_encode([
        "success" => $success,
        "message" => $success
            ? "Password updated successfully."
            : "Failed to update password."
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
|
| Default action.
|
*/

if ($action === "") {

    $email = trim(
        $_POST["email"] ?? ""
    );

    $password = trim(
        $_POST["password"] ?? ""
    );

    if (
        empty($email) ||
        empty($password)
    ) {

        header(
            "Location: ../index.php?error=empty_fields"
        );

        exit;
    }

    $email = strtolower($email);

    if (!str_ends_with(
        $email,
        "@dlsu.edu.ph"
    )) {

        header(
            "Location: ../index.php?error=invalid_email"
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Find User
    |--------------------------------------------------------------------------
    */

    $user = $userModel->findByEmail(
        $email
    );

    if (!$user) {

        header(
            "Location: ../index.php?error=account_not_found"
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Verify Password
    |--------------------------------------------------------------------------
    */

    if (!password_verify(
        $password,
        $user["password_hash"]
    )) {

        header(
            "Location: ../index.php?error=wrong_password"
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Session
    |--------------------------------------------------------------------------
    */

    $_SESSION["user_id"] =
        $user["user_id"];

    $_SESSION["first_name"] =
        $user["first_name"];

    $_SESSION["last_name"] =
        $user["last_name"];

    $_SESSION["email"] =
        $user["email"];

    $_SESSION["role"] =
        $user["role"];

    /*
    |--------------------------------------------------------------------------
    | Redirect Based On Role
    |--------------------------------------------------------------------------
    */

    switch ($user["role"]) {

        case "Student":

            header(
                "Location: ../pages/student/student_home.php"
            );

            break;

        case "Staff":

            header(
                "Location: ../pages/staff/staff_dashboard.php"
            );

            break;

        case "Admin":

            header(
                "Location: ../pages/admin/admin_dashboard.php"
            );

            break;

        default:

            session_destroy();

            header(
                "Location: ../index.php?error=unauthorized"
            );

            break;
    }

    exit;
}

/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

if ($action === "register") {

    $firstName =
        trim($_POST["first_name"] ?? "");

    $lastName =
        trim($_POST["last_name"] ?? "");

    $email =
        trim($_POST["email"] ?? "");

    $password =
        trim($_POST["password"] ?? "");

    $confirmPassword =
        trim($_POST["confirm_password"] ?? "");

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if (
        empty($firstName) ||
        empty($lastName) ||
        empty($email) ||
        empty($password) ||
        empty($confirmPassword)
    ) {

        header(
            "Location: ../pages/auth/register.php?error=empty_fields"
        );

        exit;
    }

    $email = strtolower($email);

    if (!str_ends_with(
        $email,
        "@dlsu.edu.ph"
    )) {

        header(
            "Location: ../pages/auth/register.php?error=invalid_email"
        );

        exit;
    }

    if ($password !== $confirmPassword) {

        header(
            "Location: ../pages/auth/register.php?error=password_mismatch"
        );

        exit;
    }

    if ($userModel->emailExists($email)) {

        header(
            "Location: ../pages/auth/register.php?error=email_exists"
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Account
    |--------------------------------------------------------------------------
    */

    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $created = $userModel->createUser(
        $firstName,
        $lastName,
        $email,
        $hashedPassword
    );

    if (!$created) {

        header(
            "Location: ../pages/auth/register.php?error=registration_failed"
        );

        exit;
    }

    header(
        "Location: ../index.php?success=registered"
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

if ($action === "logout") {

    $_SESSION = [];

    session_destroy();

    header(
        "Location: ../index.php"
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| UPDATE PASSWORD
|--------------------------------------------------------------------------
*/

if ($action === "updatePassword") {

    $manageAccountPage = getManageAccountPageByRole();

    if (!isset($_SESSION["user_id"])) {

        header(
            "Location: ../index.php"
        );

        exit;
    }

    $currentPassword =
        trim($_POST["current_password"] ?? "");

    $newPassword =
        trim($_POST["new_password"] ?? "");

    $confirmPassword =
        trim($_POST["confirm_password"] ?? "");

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if (
        empty($currentPassword) ||
        empty($newPassword) ||
        empty($confirmPassword)
    ) {

        header(
            "Location: {$manageAccountPage}?error=empty_fields"
        );

        exit;
    }

    if ($newPassword !== $confirmPassword) {

        header(
            "Location: {$manageAccountPage}?error=password_mismatch"
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Find Current User
    |--------------------------------------------------------------------------
    */

    $user = $userModel->findById(
        $_SESSION["user_id"]
    );

    if (!$user) {

        session_destroy();

        header(
            "Location: ../index.php"
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Verify Current Password
    |--------------------------------------------------------------------------
    */

    if (!password_verify(
        $currentPassword,
        $user["password_hash"]
    )) {

        header(
            "Location: {$manageAccountPage}?error=wrong_password"
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Prevent Same Password
    |--------------------------------------------------------------------------
    */

    if (password_verify(
        $newPassword,
        $user["password_hash"]
    )) {

        header(
            "Location: {$manageAccountPage}?error=same_password"
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Update Password
    |--------------------------------------------------------------------------
    */

    $hashedPassword = password_hash(
        $newPassword,
        PASSWORD_DEFAULT
    );

    $userModel->updatePassword(
        $_SESSION["user_id"],
        $hashedPassword
    );

    header(
        "Location: {$manageAccountPage}?success=password_updated"
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| DISABLE OWN ACCOUNT
|--------------------------------------------------------------------------
*/

if ($action === "disableAccount") {

    $manageAccountPage = getManageAccountPageByRole();

    if (!isset($_SESSION["user_id"])) {

        header(
            "Location: ../index.php"
        );

        exit;
    }

    if (
        $userModel->disableAccount(
            $_SESSION["user_id"]
        )
    ) {

        session_unset();

        session_destroy();

        header(
            "Location: ../index.php?success=account_disabled"
        );

        exit;
    }

    header(
        "Location: {$manageAccountPage}?error=disable_failed"
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| Invalid Action
|--------------------------------------------------------------------------
*/

header(
    "Location: ../index.php"
);

exit();