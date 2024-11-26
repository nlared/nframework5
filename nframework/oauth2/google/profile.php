<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
?>
<h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
<p>Email: <?php echo $_SESSION['email']; ?></p>
<a href="logout.php">Logout</a>