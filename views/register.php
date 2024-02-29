<?php
$title = "Sign Up";

ob_start();

$content = ob_get_contents();
ob_get_clean();
require_once("views/master.php");
?>
<div class="login-container">
    <h2 class="text-center" style="color: #333;">Sign Up</h2>

    <form action="index.php?action=register" method="post" class="pb-4">
        <div class="alert alert-danger invisible" id="danger-alert"></div>

        <div>
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" class="form-control" name="username">
        </div>

        <div class="my-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" class="form-control" name="email">
        </div>

        <div class="my-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" class="form-control" name="password">
        </div>

        <div class="my-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" id="confirm_password" class="form-control" name="confirm_password">
        </div>

        <input type="submit" value="Sign Up" class="btn btn-primary btn-lg my-3">
    </form>
</div>


<script src="files/resources/js/sign_up.js" defer></script>


<?php
include_once("footer.php");
?>