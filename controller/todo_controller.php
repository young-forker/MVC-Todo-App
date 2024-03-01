<?php

// Displays the sign-in page or redirects to the main page if already logged in
function sign_in() {
    // Check if the user is not already logged in
    if (!(isset($_SESSION["logged"]) or isset($_SESSION["username"]))) {
        // Display the login view
        require "views/login.php";
    } else {
        // Redirect to the main page if already logged in
        header("location: index.php?action=main");
        exit();
    }
}

// Displays all todos for the logged-in user
function show_todos() {
    // Fetch todos for the logged-in user
    $user_todos = user_todos();

    // Retrieve and format the username for display
    $username = retrieve_users_by_username($_SESSION["username"]);
    $username = $username["username"];
    $username = strtoupper($username);

    // Display the todos view
    require "views/todos.php";
}

// Fetches todos for the current session's username
function user_todos() {
    // Check if a user is logged in
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        // Fetch and return todos for the username
        return get_todos($username);
    } else {
        // Redirect to the unauthorized error page if not logged in
        header("location: index.php?action=401");
        exit();
    }
}

// Adds a new todo for the logged-in user
function new_todo() {
    // Check if todo content is posted
    if (isset($_POST["todo"])) {
        // Sanitize and format the todo content
        $todo = filter_var($_POST["todo"], FILTER_SANITIZE_SPECIAL_CHARS);
        $todo = htmlspecialchars($todo);
        $todo = trim($todo);
        $todo = ucfirst($todo);

        // Proceed only if todo is not empty
        if (!empty($todo)) {
            // Get the username from the session
            $username = $_SESSION["username"];

            // Check for an optional due date and add the todo
            if (isset($_POST["due_date"])) {
                $dueDate = filter_var($_POST["due_date"], FILTER_SANITIZE_SPECIAL_CHARS);
                $dueDate = htmlspecialchars($dueDate);
                $dueDate = trim($dueDate);
                $dueDate = strtotime($dueDate); // Convert to timestamp
                ajouter_todo($todo, $username, $dueDate);
            } else {
                ajouter_todo($todo, $username);
            }

            // Respond with a success message
            echo json_encode(['status' => 'success', 'message' => 'Todo added successfully.']);
        }
    }
    exit();
}

// Handles user authentication
function authentification() {
    // Check for posted username and password
    if (isset($_POST["username"]) and isset($_POST["password"])) {
        // Sanitize and format input values
        $username = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
        $username = htmlspecialchars($username);
        $username = trim($username);
        $password = htmlspecialchars($_POST["password"]);

        // Proceed only if both inputs are filled
        if ($username != "" and $password != "") {
            // Attempt to retrieve the user from the database
            $user = retrieve_users_by_username($username);

            // Check if user exists and password matches
            if (!empty($user)) {
                if ($user["username"] == $username and password_verify($password, $user["password"])) {
                    // Set session variables to indicate successful login
                    $_SESSION["logged"] = "true";
                    $_SESSION["username"] = $username;

                    // Respond with a success message
                    echo '{"stat": "success", "message": "You Are Logged in"}';
                } else {
                    // Respond with an error message for incorrect credentials
                    echo '{"stat": "error", "message": "Wrong Email or Password!"}';
                    exit();
                }
            } else {
                // Respond with an error message if the username does not exist
                // The same message as the above condition to prevent the potential hacker find out if the given username exists in Database or not
                echo '{"stat": "error", "message": "Wrong Email or Password!"}';
                exit();
            }
        } else {
            // Respond with an error message if any field is empty
            echo '{"stat": "error", "message": "Please fill out all the fields!"}';
            exit();
        }
    } else {
        // Redirect to unauthorized error page if request is malformed
        header("location: index.php?action=401");
        exit();
    }
}

// Updates an existing todo
function update() {
    if (isset($_SESSION["logged"]) AND isset($_SESSION["username"])) {
        if (isset($_POST["todo"]) AND isset($_POST["id"]) AND isset($_POST["due_date"]) AND isset($_POST["status"])) {
            $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
            $id = htmlspecialchars($id);
            $id = trim($id);
            $todo = filter_var($_POST["todo"], FILTER_SANITIZE_SPECIAL_CHARS);
            $todo = htmlspecialchars($todo);
            $todo = trim($todo);
            $due_date = filter_var($_POST["due_date"], FILTER_SANITIZE_SPECIAL_CHARS);
            $due_date = htmlspecialchars($due_date);
            $due_date = trim($due_date);
            if (!is_null($due_date)) {
                $due_date = strtotime($due_date);
            }
            $status = filter_var($_POST["status"], FILTER_SANITIZE_NUMBER_INT);
            $status = htmlspecialchars($status);
            $status = trim($status);
            $status = intval($status);
            $status = strval($status);

            if ($id && $todo) {
                update_todo($id, $todo, $due_date, $status);
                echo json_encode(['status' => 'success', 'message' => 'Todo updated successfully.']);
            } else
                echo json_encode(['status' => 'error', 'message' => "Fields Required"]);
        } else
            echo json_encode(['status' => 'error', 'message' => "Not Allowed for these fields"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Forbidden"]);
    }
}

// Deletes a specific todo
function deletetask() {
    // Check if the user is logged in
    if (isset($_SESSION["logged"]) and isset($_SESSION["username"])) {
        // Check if the action is to delete and an ID is provided
        if (isset($_POST["action"]) and isset($_POST["id"])) {
            $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
            $id = htmlspecialchars(trim($id));
            if ($_POST["action"] == "delete") {
                // Attempt to delete the todo and respond accordingly
                $stmt = drop_todo($id);
                if ($stmt) {
                    echo json_encode(['status' => 'success', 'message' => 'Todo deleted successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to delete todo.']);
                }
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No ID provided.']);
        }
    } else {
        // Redirect to forbidden error page if not logged in
        header("location: index.php?action=403");
        exit();
    }
}

// Handles user logout
function deconnection() {
    // Check if the user is logged in
    if (isset($_SESSION["logged"]) and isset($_SESSION["username"])) {
        // Clear session data and destroy the session
        session_unset();
        session_destroy();
    } else {
        // Redirect to forbidden error page if not logged in
        header("location: index.php?action=403");
        exit();
    }

    // Redirect to the login page
    header("location: index.php?action=login");
    exit();
}

// Displays a 403 Forbidden error page
function forbidden() {
    require "views/errors/403.php";
}

// Displays a 404 Not Found error page
function not_found() {
    require "views/errors/404.php";
}

// Displays a 401 Unauthorized error page
function unauthorized() {
    require "views/errors/401.php";
}
