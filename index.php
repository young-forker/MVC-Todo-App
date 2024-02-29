<?php
// This file is the main file of the project and acts like a url router that handles requests throughout the application
 
// Start the session to manage user sessions throughout the application
session_start();

// Include configuration and model/controller files for database and business logic
require_once "config.php"; // Database and application configuration settings
require "model/todo_model.php"; // Model for todo-related database operations
require_once "model/users_model.php"; // Model for user-related database operations
require "controller/todo_controller.php"; // Controller for todo-related actions
require "controller/user_controller.php"; // Controller for user-related actions

// Check if an action is specified in the request (e.g., via GET or POST)
if (isset($_REQUEST["action"])) {
    $route = $_REQUEST["action"]; // Retrieve the specified action

    // Determine the action to take based on the route parameter
    switch ($route) {
        case "main":
            // Display the main page showing todos
            show_todos();
            break;

        case "login":
            // Handle the sign-in process
            sign_in();
            break;

        case "ajouter":
            // Add a new todo item
            new_todo();
            break;

        case "update":
            // Update an existing todo item
            update();
            break;
        
        case "delete":
            // Delete a specified todo item
            deletetask();
            break;

        case "logout":
            // Log out the current user
            deconnection();
            break;

        case "authentification":
            // Authenticate a user's login credentials
            authentification();
            break;

        case "register":
            // Register a new user account
            User::register();
            break;

        case "sign_up":
            // Display the registration form
            User::register_layout();
            break;

        case "401":
            // Handle unauthorized access attempts
            unauthorized();
            break;

        case "403":
            // Handle forbidden access attempts
            forbidden();
            break;

        default:
            // Handle unknown actions by displaying a 404 not found error or redirecting to a default page
            not_found();
    }
} else {
    // Default action if no specific action is requested
    sign_in();
}