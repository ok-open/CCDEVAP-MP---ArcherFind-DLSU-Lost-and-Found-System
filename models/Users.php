<?php

class Users
{
    private $conn;

    public function __construct($database)
    {
        $this->conn = $database;
    }

    /**
     * Find a user by email.
     */
    public function findByEmail($email)
    {
        $sql = "SELECT *
                FROM users
                WHERE email = :email
                AND deleted = '0'
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find a user by ID.
     */
    public function findById($userId)
    {
        $sql = "SELECT *
                FROM users
                WHERE user_id = :user_id
                AND deleted = '0'
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update a user's password.
     */
    public function updatePassword($userId, $newPassword)
    {
        $sql = "UPDATE users
                SET password_hash = :password
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":password", $newPassword);
        $stmt->bindParam(":user_id", $userId);

        return $stmt->execute();
    }

    /**
     * Disable (soft delete) a user account.
     */
    public function disableAccount($userId)
    {
        $sql = "UPDATE users
                SET deleted = '1'
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $userId);

        return $stmt->execute();
    }

    /**
     * Check if an email already exists.
     */
    public function emailExists($email)
    {
        $sql = "SELECT user_id
                FROM users
                WHERE email = :email
                AND deleted = '0'
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new student account.
     */
    public function createUser(
        $firstName,
        $lastName,
        $email,
        $passwordHash
    )
    {
        $sql = "INSERT INTO users
                (
                    first_name,
                    last_name,
                    email,
                    password_hash
                )
                VALUES
                (
                    :first_name,
                    :last_name,
                    :email,
                    :password_hash
                )";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":first_name", $firstName);
        $stmt->bindParam(":last_name", $lastName);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password_hash", $passwordHash);

        return $stmt->execute();
    }

     /**
     * Get all active users.
     */
public function getAllUsers()
    {
        $sql = "SELECT
                    user_id,
                    first_name,
                    last_name,
                    email,
                    role
                FROM users
                WHERE deleted = '0'
                ORDER BY last_name ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
 * Create a user with a specified role.
 */
public function createManagedUser(
    $firstName,
    $lastName,
    $email,
    $passwordHash,
    $role
) {
    $sql = "INSERT INTO users
            (
                first_name,
                last_name,
                email,
                password_hash,
                role
            )
            VALUES
            (
                :first_name,
                :last_name,
                :email,
                :password_hash,
                :role
            )";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ":first_name" => $firstName,
        ":last_name" => $lastName,
        ":email" => $email,
        ":password_hash" => $passwordHash,
        ":role" => $role
    ]);
}

 /**
 * Update a managed user's account information.
 */
public function updateManagedUser(
    $userId,
    $firstName,
    $lastName,
    $email,
    $role
) {
    $sql = "UPDATE users
            SET first_name = :first_name,
                last_name = :last_name,
                email = :email,
                role = :role
            WHERE user_id = :user_id
            AND deleted = '0'";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ":first_name" => $firstName,
        ":last_name" => $lastName,
        ":email" => $email,
        ":role" => $role,
        ":user_id" => $userId
    ]);
}   

/**
 * Reset another user's password (Admin only).
 */
public function resetManagedUserPassword($userId, $passwordHash)
{
    $sql = "UPDATE users
            SET password_hash = :password_hash
            WHERE user_id = :user_id
            AND deleted = '0'";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ":password_hash" => $passwordHash,
        ":user_id" => $userId
    ]);
}

/**
 * Admin updates another user's password.
 */
public function updateManagedUserPassword($userId, $passwordHash)
{
    $sql = "UPDATE users
            SET password_hash = :password_hash
            WHERE user_id = :user_id
            AND deleted = '0'";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ":password_hash" => $passwordHash,
        ":user_id" => $userId
    ]);
}

    /**
     * Get the ID of the last inserted user.
     */
public function getLastInsertId()
    {
        return $this->conn->lastInsertId();
    }
}
