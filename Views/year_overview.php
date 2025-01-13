<?php
//Start the session if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Check if the user is logged in with the correct role
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'treasurer' && $_SESSION['role'] !== 'admin')) {
    //User is not logged in or does not have access
    header('Location: index.php?page=login');
    exit;
}

?>
<?php include('header.php'); ?>
<link rel="stylesheet" href="../css/global.css">

<h1>Overview of contributions for fiscal year: <?= htmlspecialchars($fiscal_year['year']) ?></h1>

<!-- Form for selecting a fiscal year -->
<form method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="dropdown-form">
    <input type="hidden" name="page" value="year_overview"> 
    <label for="fiscal_year_id">Select a fiscal year:</label>
    <select name="fiscal_year_id" id="fiscal_year_id" onchange="this.form.submit()">
        <option value="">Select a fiscal year</option>
        <?php foreach ($fiscal_years as $fiscal_year_option): ?>
            <option value="<?= htmlspecialchars($fiscal_year_option['id']) ?>" <?= isset($_GET['fiscal_year_id']) && $_GET['fiscal_year_id'] == $fiscal_year_option['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($fiscal_year_option['year']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

        <!-- Display the financial data: income, expenses, taxes, and total -->
        <p>Income: € <?= number_format(htmlspecialchars($income_total), 2, '.', ',') ?></p>
        <p>Expenses: € <?= number_format(htmlspecialchars($expenses_total), 2, '.', ',') ?></p>
        <p>Taxes: € <?= number_format(htmlspecialchars($taxes_total), 2, '.', ',') ?></p>
        <p>Total: € <?= number_format(htmlspecialchars($total), 2, '.', ',') ?></p>
          
<?php if ($contributions): ?>
    <!-- Display the contributions in a table if there are any contributions -->
    <hr>
    <table>
        <thead>
            <tr>
                <th>Contribution ID</th>
                <th>Family Member</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Payment Date</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contributions as $contribution): ?>
                <!-- Display each contribution in a row -->
                <tr>
                    <td><?= htmlspecialchars($contribution['id']) ?></td>
                    <td><?= htmlspecialchars($contribution['name']) ?></td>
                    <td>€ <?= number_format(htmlspecialchars($contribution['amount']), 2, '.', ',') ?></td>
                    <td><?= htmlspecialchars($contribution['type']) ?></td>
                    <td><?= htmlspecialchars($contribution['payment_date'] ?? 'Pending payment') ?></td>
                    <td><?= htmlspecialchars($contribution['note']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- If there are no contributions, display a message -->
    <p>No contributions are recorded for the selected fiscal year.</p>
<?php endif; ?>
<?php include('footer.php'); ?>