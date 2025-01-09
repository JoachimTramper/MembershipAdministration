<?php
//Start the session if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Check if the user is logged in with the correct role
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretary' && $_SESSION['role'] !== 'admin')) {
    //User is not logged in or does not have access
    header('Location: index.php?page=login');
    exit;
}

?>
<?php include('header.php'); ?>
<link rel="stylesheet" href="../css/global.css">

<h2>Edit details</h2>

<!-- Display the details of the member -->
<p><strong>ID:</strong> <?= htmlspecialchars($lid['id']) ?></p>
<p><strong>Name:</strong> <?= htmlspecialchars($lid['naam']) ?></p>

<!-- Form for editing member details -->
<form action="index.php?page=update_member&id=<?= $lid['id'] ?>" method="POST">
    <!-- Input field for name -->
    <label for="naam">Name:</label>
    <input type="text" name="naam" id="naam" value="<?= htmlspecialchars($lid['naam']) ?>" required>

    <!-- Input field for address -->
    <label for="adres">Address:</label>
    <input type="text" name="adres" id="adres" value="<?= htmlspecialchars($lid['adres']) ?>" required>

    <!-- Input field for date of birth -->
    <label for="geboortedatum">Date of birth:</label>
    <input type="date" name="geboortedatum" id="geboortedatum" value="<?= htmlspecialchars($lid['geboortedatum']) ?>" required>

    <!-- Input field for username -->
    <label for="gebruikersnaam">Username:</label>
    <input type="text" name="gebruikersnaam" id="gebruikersnaam" value="<?= htmlspecialchars($lid['gebruikersnaam']) ?>" required>

    <!-- Input field for password -->
    <label for="wachtwoord">Password:</label>
    <input type="password" name="wachtwoord" id="wachtwoord" value="<?= htmlspecialchars($lid['wachtwoord']) ?>">

    <!-- Input field for family ID. -->
    <label for="familie_id">Family ID:</label>
    <input type="text" name="familie_id" id="familie_id" value="<?= htmlspecialchars($lid['familie_id']) ?>" required>

    <!-- Dropdown for role -->
    <label for="rol">Role:</label>
    <select name="rol" id="rol">
        <option value="1" <?= $lid['rol'] == 1 ? 'selected' : '' ?>>Admin</option>
        <option value="2" <?= $lid['rol'] == 2 ? 'selected' : '' ?>>Secretary</option>
        <option value="3" <?= $lid['rol'] == 3 ? 'selected' : '' ?>>Treasurer</option>
        <option value="4" <?= $lid['rol'] == 4 ? 'selected' : '' ?>>Member</option>
    </select>

    <!-- Save button -->
    <button type="submit" name="bewerk_lid" value="bewerken">Save</button>

</form>
<?php include('footer.php'); ?>