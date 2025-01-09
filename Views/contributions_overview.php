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
    <label for="boekjaar_id"><b>Overview per fiscal year:</b></label>
    <input type="hidden" name="page" value="year_overview">
    <select name="boekjaar_id" id="boekjaar_id" onchange="this.form.submit()">
        <option value="">Select a fiscal year</option>
        <?php foreach ($boekjaren as $boekjaar): ?>
            <option value="<?= htmlspecialchars($boekjaar['id']) ?>" <?= isset($_GET['boekjaar_id']) && $_GET['boekjaar_id'] == $boekjaar['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($boekjaar['jaar']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<!-- Form to add a new contribution -->
<h2>Add a new contribution</h2>
<form action="index.php?page=contributions_overview" method="POST">
    <label for="familielid_id">Family member:</label>
    <select name="familielid_id" id="familielid_id">
        <option value="">Select a family member (optional)</option>
<?php foreach ($familieleden as $familielid) {echo "<option value='{$familielid['id']}'>" . htmlspecialchars($familielid['naam']) . "</option>";}?>
    </select>

    <label for="bedrag">Amount:</label>
    <input type="number" step="0.01" name="bedrag" id="bedrag" required>

    <label for="type">Type:</label>
    <select name="type" id="type">
        <option value="inkomsten">Income</option>
        <option value="uitgaven">Expenses</option>
        <option value="belastingen">Taxes</option>
    </select>

    <label for="betaaldatum">Payment date:</label>
    <input type="date" name="betaaldatum" id="betaaldatum">

        <!-- Dropdown for fiscal year -->
        <label for="boekjaar_id">Fiscal year:</label>
        <select name="boekjaar_id" required>
            <option value="">Select a fiscal year</option>
<?php foreach ($boekjaren as $boekjaar) {echo "<option value='{$boekjaar['id']}'>" . htmlspecialchars($boekjaar['jaar']) . "</option>";}?>
        </select>

        <label for="aantekening">Note:</label>
        <textarea name="aantekening" id="aantekening"></textarea>

    <button type="submit" name="add_contributie">Add contribution</button>
</form>
<br>
<br>

<!-- Form to add a new fiscal year -->
<form method="post" action="index.php?page=contributions_overview">
    <button type="submit" name="add_boekjaar" onclick="return confirmAddBookjaar()">Add new fiscal year</button>
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
                <td><?= htmlspecialchars($contributie['familielid_naam']) ?></td>
                <td>€ <?= number_format(htmlspecialchars($contributie['bedrag']), 2, ',', '.') ?></td></td>
                <td><?= htmlspecialchars($contributie['type']) ?></td>
                <td><?= htmlspecialchars($contributie['betaaldatum'] ?? 'Not paid yet.') ?></td> <!-- Default text for when the payment has not been made yet -->
                <td><?= htmlspecialchars($contributie['boekjaar_jaar']) ?></td>
                <td><?= htmlspecialchars($contributie['aantekening']) ?></td>
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
