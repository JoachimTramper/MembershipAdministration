<?php
//Start the session if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    //If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit;
}
include('header.php'); ?>

<link rel="stylesheet" href="../css/global.css">
<h1>Members Overview</h1>
<!-- Table for displaying member information -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Member Type</th>
        </tr>
    </thead>
    <tbody>
        <!-- Check if there is member data -->
        <?php if (isset($leden) && !empty($leden)): ?>
            <?php foreach ($leden as $lid): ?>
                <!-- Display each member in a new row -->
                <tr>
                    <td><?php echo htmlspecialchars($lid['id']); ?></td>
                    <td><?php echo htmlspecialchars($lid['naam']); ?></td>
                    <td><?php echo htmlspecialchars($lid['adres']); ?></td>
                    <td><?php echo htmlspecialchars($lid['soort_lid']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Display a message if no member data is available -->
            <tr>
                <td colspan="3">No member data available.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>
