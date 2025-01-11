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

//Load the header
include('header.php');
?>

<link rel="stylesheet" href="../css/global.css">
<h2>Edit Contribution Details</h2>

<!-- Display the contribution details -->
<p><strong>ID:</strong> <?= htmlspecialchars($contributie['id']) ?></p>
<p><strong>Family member:</strong> <?= htmlspecialchars($contributie['family_member_name'] ?? 'No family member') ?>
<p><strong>Amount:</strong> <?= htmlspecialchars($contributie['amount']) ?></p>
<p><strong>Type:</strong> <?= htmlspecialchars($contributie['type']) ?></p>
<p><strong>Payment date:</strong> <?= htmlspecialchars($contributie['payment_date'] ?? 'Not paid yet') ?></p>

<!-- Form for editing the contribution -->
<form action="index.php?page=update_contribution&id=<?= $contributie['id']; ?>" method="POST">
    <!-- Hidden fields for id and family_member_id -->
    <input type="hidden" name="id" value="<?= $contributie['id']; ?>">
    <input type="hidden" name="family_member_id" value="<?= $contributie['family_member_id']; ?>">

    <!-- Field for the contribution amount -->
    <label for="amount">Amount:</label>
    <input type="number" step="0.01" name="amount" id="amount" value="<?= htmlspecialchars($contributie['amount']) ?>" required>

    <!-- Selection for the contribution type -->
    <label for="type">Type:</label>
    <select name="type" id="type">
        <option value="income" <?= $contributie['type'] == 'income' ? 'selected' : '' ?>>Income</option>
        <option value="expenses" <?= $contributie['type'] == 'expenses' ? 'selected' : '' ?>>Expenses</option>
        <option value="taxes" <?= $contributie['type'] == 'taxes' ? 'selected' : '' ?>>Taxes</option>
        <option value="else" <?= $contributie['type'] == 'else' ? 'selected' : '' ?>>Else</option>
    </select>

    <!-- Field for the payment date -->
    <label for="payment_date">Payment date:</label>
    <input type="date" name="payment_date" id="payment_date">

    <!-- Dropdown for fiscal year -->
    <label for="fiscal_year_id">Fiscal year:</label>
    <select name="fiscal_year_id" required>
        <option value="">Select a fiscal year</option>
        <?php foreach ($boekjaren as $boekjaar): ?>
            <option value="<?= htmlspecialchars($boekjaar['id']) ?>" <?= $contributie['fiscal_year_id'] == $boekjaar['id'] ? 'selected' : '' ?>><?= htmlspecialchars($boekjaar['year']) ?></option>
        <?php endforeach; ?>
    </select>
    
    <!-- Field for a note on the contribution -->
    <label for="note">Note:</label>
    <textarea name="note" id="note"><?= htmlspecialchars($contributie['note']) ?></textarea>
    
    <!-- Save button -->
    <button type="submit" name="bewerk_contributie" value="bewerken">Save</button>
</form>
<!-- Load the footer -->
<?php include('footer.php'); ?>
