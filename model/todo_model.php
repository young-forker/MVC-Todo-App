<?php

// Establish a database connection using PDO
function connection() {
    // Retrieve database credentials
    $username = DB_USER;
    $password = DB_PASS;
    $host = DB_SERVER;
    $db_name = DB_NAME;
    // DSN (Data Source Name) for MySQL connection
    $dsn = "mysql:host=$host;dbname=$db_name";

    // Attempt to connect to the database and return the PDO object
    try {
        return new PDO($dsn, $username, $password);
    } catch (PDOException $err) {
        // On connection failure, output the error message and terminate the script
        echo $err->getMessage();
        die('Error in DB connection!');
    }
}

// Fetch all todo items for a specific user
function get_todos($username) {
    // Establish a database connection
    $db_connection = connection();

    // Prepare a SELECT SQL statement
    $statement = $db_connection->prepare("SELECT * FROM todo WHERE user_username LIKE :username ORDER BY id ASC");

    // Execute the statement with a parameter binding
    $statement->execute([":username" => $username]);

    // Return the fetched rows as an associative array
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Add a new todo item for a user
function ajouter_todo($todo, $username, $dueDate = null) {
    // Establish a database connection
    $db_connection = connection();

    // Check if due date is provided and not empty
    $dueDate = (($dueDate) AND ($dueDate != '')) ? $dueDate : null;

    // Insert the todo item into the database
    if ($dueDate === null) {
        // Prepare INSERT SQL statement without due date
        $statement = $db_connection->prepare("INSERT INTO todo (`todo`, `user_username`) VALUES(:todo, :username)");
        $statement->execute([":todo" => $todo, ":username" => $username]);
    } else {
        // Prepare INSERT SQL statement with due date
        $statement = $db_connection->prepare("INSERT INTO todo (`todo`, `user_username`, `due_date`) VALUES(:todo, :username, :dueDate)");
        $statement->execute([":todo" => $todo, ":username" => $username, ":dueDate" => $dueDate]);
    }
}

// Update an existing todo item
function update_todo($todo_id, $todo_content, $due_date, $status) {
    // Establish a database connection
    $db_connection = connection();

    // Prepare an UPDATE SQL statement
    $statement = $db_connection->prepare("UPDATE todo SET todo = :todo, due_date = :due_date, status = :status WHERE id = :id");

    // Execute the statement with parameter bindings
    $statement->execute([
        ":id" => $todo_id,
        ":todo" => $todo_content,
        ":due_date" => ($due_date) ? $due_date : null, // Handle nullable due date
        ":status" => $status
    ]);
}

// Delete a specific todo item by ID
function supprimer_todo($todo_id) {
    // Establish a database connection
    $db_connection = connection();

    // Prepare a DELETE SQL statement
    $statement = $db_connection->prepare("DELETE FROM todo WHERE id = :id");

    // Execute the statement with parameter binding
    $statement->execute([":id" => $todo_id]);
}

// Another function to delete a todo item; seems redundant and could potentially be refactored or removed
function drop_todo($id) {
    // Establish a database connection
    $db_connection = connection();

    // Prepare a DELETE SQL statement
    $statement = $db_connection->prepare("DELETE FROM todo WHERE id = :id");

    // Execute the statement with parameter binding
    $statement->execute([":id" => $id]);

    // Return the PDOStatement object
    return $statement;
}