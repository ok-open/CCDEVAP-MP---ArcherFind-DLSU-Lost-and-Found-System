<?php

session_start();

require_once "../db.php";
require_once "../models/ContactsReceived.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/student/student_contact.php");
    exit();
}

$inquiry = trim($_POST["inquiry_type"] ?? "");
$message = trim($_POST["message"] ?? "");

if ($inquiry === "" || $message === "") {
    header("Location: ../pages/student/student_contact.php?error=empty_fields");
    exit();
}

// Create ContactsReceived model
$contactModel = new ContactsReceived($conn);

// Save message
$success = $contactModel->sendContactMessage(
    $_SESSION["user_id"],
    $inquiry,
    $message
);

if ($success) {
    header("Location: ../pages/student/student_contact.php?success=message_sent");
    exit();
}

header("Location: ../pages/student/student_contact.php?error=send_failed");
exit();

?>