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
        <?php if (isset($members) && !empty($members)): ?>
            <?php foreach ($members as $member): ?>
                <!-- Display each member in a new row -->
                <tr>
                    <td><?php echo htmlspecialchars($member['id']); ?></td>
                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                    <td><?php echo htmlspecialchars($member['address']); ?></td>
                    <td><?php echo htmlspecialchars($member['member_type']); ?></td>
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
