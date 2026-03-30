<?php
session_start();

if ($_POST) {
    if ($_POST['email'] == "admin" && $_POST['password'] == "1234") {
        $_SESSION['user'] = "admin";
        header("Location: dashboard.php");
    }
}
?>

<form method="POST">
  <input type="text" name="email" placeholder="Email">
  <input type="password" name="password">
  <button>Login</button>
</form>