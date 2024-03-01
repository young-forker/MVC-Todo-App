<?php
// Setting the title for the login page
$title = "Sign In";

// Start output buffering
ob_start();

// Capture the buffer content (though it seems unused here)
$content = ob_get_contents();

// Clean (erase) the output buffer and turn off output buffering
ob_get_clean();

// Include the master layout view
require_once("views/master.php");
?>

<!-- Login form container -->
<div class="login-container">
    <!-- Title for the login form -->
    <h2 class="text-center" style="color: #333;">Login</h2>

    <!-- Login form with action pointing to authentication logic -->
    <form action="index.php?action=authentification" method="post" class="pb-4">
        <!-- Invisible alert for displaying errors dynamically with JavaScript -->
        <div class="alert alert-danger invisible" id="danger-alert"></div>

        <!-- Information alert with demo credentials -->
        <div class="alert alert-info">
            You can use the following credentials to login: <br>
            <strong>Login:</strong> 'admin' and <strong>Password:</strong> 'admin'
        </div>

        <!-- Username input field -->
        <div>
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" class="form-control" name="username">
        </div>

        <!-- Password input field -->
        <div class="my-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" class="form-control" name="password">
        </div>

        <!-- Submit button for the form -->
        <input type="submit" value="Sign In" class="btn btn-primary btn-lg">
    </form>
</div>

<!-- Including the main JavaScript file for the login logic -->
<script src="files/resources/js/main.js"></script>

<?php
// Include the footer part of the layout
include_once("footer.php");
?>