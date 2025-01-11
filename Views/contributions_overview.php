<?php
//Start the session if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Check if the user is logged in with the correct role
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'treasurer' && $_SESSION['role'] !== 'admin')) {
    header('Location: index.php?page=login');
    exit;
}

include('header.php');
?>

<link rel="stylesheet" href="../css/global.css">
<h1>Contribution overview</h1>
<!-- Dropdown form for filtering by fiscal year -->
<form method="GET" action="index.php?page=year_overview" class="dropdown-form dropdown-boekjaar-overzicht">
    <label for="fiscal_year_id"><b>Overview per fiscal year:</b></label>
    <input type="hidden" name="page" value="year_overview">
    <select name="fiscal_year_id" id="fiscal_year_id" onchange="this.form.submit()">
        <option value="">Select a fiscal year</option>
        <?php foreach ($boekjaren as $boekjaar): ?>
            <option value="<?= htmlspecialchars($boekjaar['id']) ?>" <?= isset($_GET['fiscal_year_id']) && $_GET['fiscal_year_id'] == $boekjaar['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($boekjaar['year']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<!-- Form to add a new contribution -->
<h2>Add a new contribution</h2>
<form action="index.php?page=contributions_overview" method="POST">
    <label for="family_member_id">Family member:</label>
    <select name="family_member_id" id="family_member_id">
        <option value="">Select a family member (optional)</option>
<?php foreach ($familieleden as $familielid) {echo "<option value='{$familielid['id']}'>" . htmlspecialchars($familielid['name']) . "</option>";}?>
    </select>

    <label for="amount">Amount:</label>
    <input type="number" step="0.01" name="amount" id="amount" required>

    <label for="type">Type:</label>
    <select name="type" id="type">
        <option value="income">Income</option>
        <option value="expenses">Expenses</option>
        <option value="taxes">Taxes</option>
    </select>

    <label for="payment_date">Payment date:</label>
    <input type="date" name="payment_date" id="payment_date">

        <!-- Dropdown for fiscal year -->
        <label for="fiscal_year_id">Fiscal year:</label>
        <select name="fiscal_year_id" required>
            <option value="">Select a fiscal year</option>
<?php foreach ($boekjaren as $boekjaar) {echo "<option value='{$boekjaar['id']}'>" . htmlspecialchars($boekjaar['year']) . "</option>";}?>
        </select>

        <label for="note">Note:</label>
        <textarea name="note" id="note"></textarea>

    <button type="submit" name="add_contributie">Add contribution</button>
</form>
<br>
<br>

<!-- Form to add a new fiscal year -->
<form method="post" action="index.php?page=contributions_overview">
    <button type="submit" name="add_fiscal_year" onclick="return confirmAddBookjaar()">Add new fiscal year</button>
</form>

<script>
    //Confirmation when adding a new fiscal year
    function confirmAddBookjaar() {
        return confirm("Are you sure you want to add the next fiscal year to the membership administration?");
    }
</script>
<hr>

<!-- Table with existing contributions -->
<h2>Contributions</h2>
<table>
    <thead>
        <tr>
            <th>Contribution ID</th>
            <th>Family Member</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Payment Date</th>
            <th>Fiscal Year</th>
            <th>Note</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contributies as $contributie): ?>
                <td><?= htmlspecialchars($contributie['id']) ?></td>
                <td><?= htmlspecialchars($contributie['family_member_name']) ?></td>
                <td>€ <?= number_format(htmlspecialchars($contributie['amount']), 2, ',', '.') ?></td></td>
                <td><?= htmlspecialchars($contributie['type']) ?></td>
                <td><?= htmlspecialchars($contributie['payment_date'] ?? 'Not paid yet.') ?></td> <!-- Default text for when the payment has not been made yet -->
                <td><?= htmlspecialchars($contributie['fiscal_year']) ?></td>
                <td><?= htmlspecialchars($contributie['note']) ?></td>
                <td style="text-align: center;">
                <!-- Update -->
                <a href="index.php?page=update_contribution&action=bewerk&id=<?= $contributie['id'] ?>">Update</a> |
                <!-- Delete -->
                <a href="index.php?page=contributions_overview&action=verwijder&id=<?= $contributie['id'] ?>"
                onclick="return confirm('Are you sure you want to delete this contribution?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>
