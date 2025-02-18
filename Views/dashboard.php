<?php
//Start the session if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//Check if the user is logged in and if the session is valid
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: ../index.php");
    exit;
}

include 'header.php';
?>

<link rel="stylesheet" href="../css/global.css">
<h1>Welcome, <?php echo htmlspecialchars($user); ?>! You are logged in as <?php echo htmlspecialchars($role); ?>.</h1>
<h2>Overview of payments per family</h2>
<?php if (empty($members)): ?>
    <!-- Display this message if no family members or payments are found -->
    <p>No family members or payments found</p>
<?php else: ?>
    <!-- Table with family members and their outstanding payments -->
    <table>
        <thead>
            <tr>
                <th>Family Member</th>
                <th>Outstanding Payments</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member): ?>
                <tr>
                    <td><?= htmlspecialchars($member['name']) ?></td>
                    <td>
                        <?php if ($member['amount'] !== null): ?>
                            <!-- If there is an outstanding amount, display it with a euro symbol and format it as currency -->
                            € <?= number_format($member['amount'], 2, '.', ',') ?>
                        <?php else: ?>
                            <!-- If there is no outstanding amount, display an alternative message -->
                            No outstanding payment
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<br>
    <!-- Display the total of all outstanding payments -->
    <h3>Total Outstanding Payments: € <?= number_format($total_outstanding_payments, 2, '.', ',') ?></h3>
</body>
<?php include 'footer.php'; ?>
