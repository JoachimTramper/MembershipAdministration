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

<h1>Overview of contributions for fiscal year: <?= htmlspecialchars($boekjaar['jaar']) ?></h1>

<!-- Form for selecting a fiscal year -->
<form method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="dropdown-form">
    <input type="hidden" name="page" value="year_overview"> 
    <label for="boekjaar_id">Select a fiscal year:</label>
    <select name="boekjaar_id" id="boekjaar_id" onchange="this.form.submit()">
        <option value="">Select a fiscal year</option>
        <?php foreach ($boekjaren as $boekjaar_optie): ?>
            <option value="<?= htmlspecialchars($boekjaar_optie['id']) ?>" <?= isset($_GET['boekjaar_id']) && $_GET['boekjaar_id'] == $boekjaar_optie['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($boekjaar_optie['jaar']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

        <!-- Display the financial data: income, expenses, taxes, and total -->
        <p>Income: € <?= number_format(htmlspecialchars($inkomsten_totaal), 2, ',', '.') ?></p>
        <p>Expenses: € <?= number_format(htmlspecialchars($uitgaven_totaal), 2, ',', '.') ?></p>
        <p>Taxes: € <?= number_format(htmlspecialchars($belastingen_totaal), 2, ',', '.') ?></p>
        <p>Total: € <?= number_format(htmlspecialchars($totaal), 2, ',', '.') ?></p>
          
<?php if ($contributies): ?>
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
            <?php foreach ($contributies as $contributie): ?>
                <!-- Display each contribution in a row -->
                <tr>
                    <td><?= htmlspecialchars($contributie['id']) ?></td>
                    <td><?= htmlspecialchars($contributie['naam']) ?></td>
                    <td>€ <?= number_format(htmlspecialchars($contributie['bedrag']), 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($contributie['type']) ?></td>
                    <td><?= htmlspecialchars($contributie['betaaldatum']) ?></td>
                    <td><?= htmlspecialchars($contributie['aantekening']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- If there are no contributions, display a message -->
    <p>No contributions found for this fiscal year.</p>
<?php endif; ?>
<?php include('footer.php'); ?>