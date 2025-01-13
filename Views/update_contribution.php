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
<p><strong>ID:</strong> <?= htmlspecialchars($contribution['id']) ?></p>
<p><strong>Family member:</strong> <?= htmlspecialchars($contribution['family_member_name'] ?? 'No family member') ?>
<p><strong>Amount:</strong> <?= htmlspecialchars($contribution['amount']) ?></p>
<p><strong>Type:</strong> <?= htmlspecialchars($contribution['type']) ?></p>
<p><strong>Payment date:</strong> <?= htmlspecialchars($contribution['payment_date'] ?? 'Pending payment') ?></p>

<!-- Form for editing the contribution -->
<form action="index.php?page=update_contribution&id=<?= $contribution['id']; ?>" method="POST">
    <!-- Hidden fields for id and family_member_id -->
    <input type="hidden" name="id" value="<?= $contribution['id']; ?>">
    <input type="hidden" name="family_member_id" value="<?= $contribution['family_member_id']; ?>">

    <!-- Field for the contribution amount -->
    <label for="amount">Amount:</label>
    <input type="number" step="0.01" name="amount" id="amount" value="<?= htmlspecialchars($contribution['amount']) ?>" required>

    <!-- Selection for the contribution type -->
    <label for="type">Type:</label>
    <select name="type" id="type">
        <option value="income" <?= $contribution['type'] == 'income' ? 'selected' : '' ?>>Income</option>
        <option value="expenses" <?= $contribution['type'] == 'expenses' ? 'selected' : '' ?>>Expenses</option>
        <option value="taxes" <?= $contribution['type'] == 'taxes' ? 'selected' : '' ?>>Taxes</option>
        <option value="else" <?= $contribution['type'] == 'else' ? 'selected' : '' ?>>Else</option>
    </select>

    <!-- Field for the payment date -->
    <label for="payment_date">Payment date:</label>
    <input type="date" name="payment_date" id="payment_date">

    <!-- Dropdown for fiscal year -->
    <label for="fiscal_year_id">Fiscal year:</label>
    <select name="fiscal_year_id" required>
        <option value="">Select a fiscal year</option>
        <?php foreach ($fiscal_years as $fiscal_year): ?>
            <option value="<?= htmlspecialchars($fiscal_year['id']) ?>" <?= $contribution['fiscal_year_id'] == $fiscal_year['id'] ? 'selected' : '' ?>><?= htmlspecialchars($fiscal_year['year']) ?></option>
        <?php endforeach; ?>
    </select>
    
    <!-- Field for a note on the contribution -->
    <label for="note">Note:</label>
    <textarea name="note" id="note"><?= htmlspecialchars($contribution['note']) ?></textarea>
    
    <!-- Save button -->
    <button type="submit" name="update_contribution" value="update">Save</button>
</form>
<!-- Load the footer -->
<?php include('footer.php'); ?>
