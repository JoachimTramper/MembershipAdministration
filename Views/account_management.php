<?php

//Start the session if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Check if the user is logged in
if (!isset($_SESSION['role'])) {
    //If the user is not logged in, redirect to the login page
    header('Location: index.php?page=login');
    exit;
}

include('header.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
    <link rel="stylesheet" href="../css/account_management.css">

</head>
<body>
<main>
    <h1>Update Account</h1>
    <!-- Form container -->
    <div class="form-container">
        <form method="post" action="index.php?page=account_management">
            <label for="username">New username:&emsp;</label>
            <input type="text" id="username" name="username" required>
            <br><br>
            <label for="password">New password:&emsp;</label>
            <input type="password" id="password" name="password" required>
            <br><br>
            <button type="submit" name="update_account">Save</button>
        </form>
    </div>
</main>
</body>
</html>
<?php include('footer.php'); ?>
