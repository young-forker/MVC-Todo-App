<?php

/**
 * The User class provides methods for user registration and displaying the registration layout.
 */
class User {

    /**
     * Displays the registration layout.
     * 
     * This method includes the registration view, which contains the HTML form
     * for user registration.
     */
    public static function register_layout() {
        require "views/register.php"; // Path to the registration view
    }

    /**
     * Handles the registration process for a new user.
     * 
     * This method processes the POST request from the registration form. It performs
     * server-side validation of the submitted data (username, email, password, confirm password),
     * checks for the uniqueness of the username and email, hashes the password, and registers
     * the user if all conditions are met.
     */
    public static function register() {
        // Ensure the request is a POST request
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo "Forbidden"; // Only POST requests are allowed
            exit;
        }

        // Check if all required fields are set
        if (!(isset($_POST["username"]) and isset($_POST["email"]) and isset($_POST["password"]) and isset($_POST["confirm_password"]))) {
            echo "Forbidden"; // Missing required fields
            exit;
        }

        // Sanitize and validate input
        $username = trim($_POST["username"]);
        $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);

        $email = trim($_POST["email"]);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $password = filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm_password = filter_var($_POST["confirm_password"], FILTER_SANITIZE_SPECIAL_CHARS);

        // Check for empty fields
        if (empty($username) or empty($email) or empty($password) or empty($confirm_password)) {
            echo '{"status": "error", "message": "Fields Cannot Be Empty!!!"}';
            exit;
        }

        // Check for existing username
        if (retrieve_users_by_username($username)) {
            echo '{"status": "error", "message": "Username Already Exists!!!"}';
            exit;
        }

        // Check for existing email
        if (retrieve_users_by_email($email)) {
            echo '{"status": "error", "message": "Email Already Exists!!!"}';
            exit;
        }

        // Check if passwords match
        if ($password != $confirm_password) {
            echo '{"status": "error", "message": "Passwords Do Not Match!!!"}';
            exit;
        }

        // Hash the password and register the user
        $password = password_hash($password, PASSWORD_DEFAULT);
        register_user($username, $password, $email); // Register the user in the database
        $_SESSION["success"] = "Account Has Been Created!!!";
        echo '{"status": "success", "message": "Registration was successful."}';
        exit;
    }

}