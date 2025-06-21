<?php
require_once './config.php';

class User
{
    /**
     * Get all users from the database
     * 
     * @return array
     */
    public static function getAllUsers(): array
    {
        global $conn;
        $sql = "SELECT * FROM users";

        if ($result = $conn->query($sql)) {
            $users = [];

            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            return $users;
        } else {
            // Handle error
            return []; // Return an empty array on error
        }
    }
}
?>