<?php

class ContactsReceived
{
    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    public function sendContactMessage($studentId, $inquiry, $message)
    {
        $sql = "INSERT INTO contacts_received
                (student_id, inquiry, message)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $studentId,
            $inquiry,
            $message
        ]);
    }
}

?>