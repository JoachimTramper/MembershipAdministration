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
<p><strong>Family member:</strong> <?= htmlspecialchars($contributie['familielid_naam'] ?? 'No family member') ?>
<p><strong>Amount:</strong> <?= htmlspecialchars($contributie['bedrag']) ?></p>
<p><strong>Type:</strong> <?= htmlspecialchars($contributie['type']) ?></p>
<p><strong>Payment date:</strong> <?= htmlspecialchars($contributie['betaaldatum'] ?? 'Not paid yet') ?></p>

<!-- Form for editing the contribution -->
<form action="index.php?page=update_contribution&id=<?= $contributie['id']; ?>" method="POST">
    <!-- Hidden fields for id and family_member_id -->
    <input type="hidden" name="id" value="<?= $contributie['id']; ?>">
    <input type="hidden" name="familielid_id" value="<?= $contributie['familielid_id']; ?>">

    <!-- Field for the contribution amount -->
    <label for="bedrag">Amount:</label>
    <input type="number" step="0.01" name="bedrag" id="bedrag" value="<?= htmlspecialchars($contributie['bedrag']) ?>" required>

    <!-- Selection for the contribution type -->
    <label for="type">Type:</label>
    <select name="type" id="type">
        <option value="income" <?= $contributie['type'] == 'income' ? 'selected' : '' ?>>Income</option>
        <option value="expenses" <?= $contributie['type'] == 'expenses' ? 'selected' : '' ?>>Expenses</option>
        <option value="taxes" <?= $contributie['type'] == 'taxes' ? 'selected' : '' ?>>Taxes</option>
        <option value="else" <?= $contributie['type'] == 'else' ? 'selected' : '' ?>>Else</option>
    </select>

    <!-- Field for the payment date -->
    <label for="betaaldatum">Payment date:</label>
    <input type="date" name="betaaldatum" id="betaaldatum">

    <!-- Dropdown for fiscal year -->
    <label for="boekjaar_id">Fiscal year:</label>
    <select name="boekjaar_id" required>
        <option value="">Select a fiscal year</option>
        <?php foreach ($boekjaren as $boekjaar): ?>
            <option value="<?= htmlspecialchars($boekjaar['id']) ?>" <?= $contributie['boekjaar_id'] == $boekjaar['id'] ? 'selected' : '' ?>><?= htmlspecialchars($boekjaar['jaar']) ?></option>
        <?php endforeach; ?>
    </select>
    
    <!-- Field for a note on the contribution -->
    <label for="aantekening">Note:</label>
    <textarea name="aantekening" id="aantekening"><?= htmlspecialchars($contributie['aantekening']) ?></textarea>
    
    <!-- Save button -->
    <button type="submit" name="bewerk_contributie" value="bewerken">Save</button>
</form>
<!-- Load the footer -->
<?php include('footer.php'); ?>
