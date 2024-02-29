<?php
$title = "Sign In";

ob_start();

$content = ob_get_contents();
ob_get_clean();
require_once("views/master.php");
?>
<div class="login-container">
    <!-- Bold "Login" text at the top of the login box -->
    <h2 class="text-center" style="color: #333;">Login</h2>

    <form action="index.php?action=authentification" method="post" class="pb-4">
        <div class="alert alert-danger invisible" id="danger-alert"></div>

        <div class="alert alert-info">
            You can use the following credentials for to login: <br>
            <strong>Login:</strong> 'admin' and <strong>Password:</strong> 'admin'
        </div>

        <div>
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" class="form-control" name="username">
        </div>

        <div class="my-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" class="form-control" name="password">
        </div>

        <input type="submit" value="Sign In" class="btn btn-primary btn-lg">
    </form>
</div>



<script src="files/resources/js/main.js"></script>


<?php
include_once("footer.php");
?>