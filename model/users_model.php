<?php

/**
 * Fetch a user by their username.
 * 
 * This function retrieves a user's details from the database based on the provided username.
 * It prepares a SQL statement to search for a user where the username matches the specified parameter.
 * The function then executes the statement and returns the fetched user data as an associative array.
 *
 * @param string $username The username of the user to retrieve.
 * @return array Associative array containing the user's details if found, else false.
 */
function retrieve_users_by_username($username) {
    $db_connection = connection(); // Establish a database connection

    // Prepare a SQL statement to fetch the user by username
    $statment = $db_connection->prepare("SELECT * FROM users WHERE username LIKE :username LIMIT 1");
    // Execute the prepared statement with the username parameter
    $statment->execute([":username" => $username]);
    // Fetch and return the result as an associative array
    return $statment->fetch(PDO::FETCH_ASSOC);
}

/**
 * Fetch a user by their email address.
 * 
 * Similar to retrieve_users_by_username, but searches for a user based on their email address.
 * The function prepares and executes a SQL statement to fetch the user details where the email
 * matches the specified parameter.
 *
 * @param string $email The email address of the user to retrieve.
 * @return array Associative array containing the user's details if found, else false.
 */
function retrieve_users_by_email($email) {
    $db_connection = connection(); // Establish a database connection

    // Prepare a SQL statement to fetch the user by email
    $statment = $db_connection->prepare("SELECT * FROM users WHERE email LIKE :email LIMIT 1");
    // Execute the prepared statement with the email parameter
    $statment->execute([":email" => $email]);
    // Fetch and return the result as an associative array
    return $statment->fetch(PDO::FETCH_ASSOC);
}

/**
 * Register a new user.
 * 
 * This function adds a new user to the database with the provided username, password, and email address.
 * It prepares a SQL statement to insert a new record into the users table. The password should be securely
 * hashed before calling this function.
 *
 * @param string $username The username of the new user.
 * @param string $password The hashed password of the new user.
 * @param string $email The email address of the new user.
 */
function register_user($username, $password, $email) {
    $db_connection = connection(); // Establish a database connection

    // Prepare a SQL statement to insert the new user
    $statment = $db_connection->prepare("INSERT INTO users VALUES (NULL, :username, :user_password, :email)");
    // Execute the prepared statement with the provided parameters
    $statment->execute([
        ":username" => $username,
        ":user_password" => $password,
        ":email" => $email
    ]);
}