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
<p><strong>Name:</strong> <?= htmlspecialchars($lid['name']) ?></p>

<!-- Form for editing member details -->
<form action="index.php?page=update_member&id=<?= $lid['id'] ?>" method="POST">
    <!-- Input field for name -->
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($lid['name']) ?>" required>

    <!-- Input field for address -->
    <label for="address">Address:</label>
    <input type="text" name="address" id="address" value="<?= htmlspecialchars($lid['address']) ?>" required>

    <!-- Input field for date of birth -->
    <label for="dob">Date of birth:</label>
    <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($lid['dob']) ?>" required>

    <!-- Input field for username -->
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" value="<?= htmlspecialchars($lid['username']) ?>" required>

    <!-- Input field for password -->
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" value="<?= htmlspecialchars($lid['password']) ?>">

    <!-- Input field for family ID. -->
    <label for="family_id">Family ID:</label>
    <input type="text" name="family_id" id="family_id" value="<?= htmlspecialchars($lid['family_id']) ?>" required>

    <!-- Dropdown for role -->
    <label for="role">Role:</label>
    <select name="role" id="role">
        <option value="1" <?= $lid['role'] == 1 ? 'selected' : '' ?>>Admin</option>
        <option value="2" <?= $lid['role'] == 2 ? 'selected' : '' ?>>Secretary</option>
        <option value="3" <?= $lid['role'] == 3 ? 'selected' : '' ?>>Treasurer</option>
        <option value="4" <?= $lid['role'] == 4 ? 'selected' : '' ?>>Member</option>
    </select>

    <!-- Save button -->
    <button type="submit" name="bewerk_lid" value="bewerken">Save</button>

</form>
<?php include('footer.php'); ?>