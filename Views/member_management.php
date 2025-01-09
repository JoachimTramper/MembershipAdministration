<?php

//Start the session if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Check if the user is logged in with the correct role
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretary' && $_SESSION['role'] !== 'admin')) {
    header('Location: index.php?page=login');
    exit;
}
include('header.php');
?>

<link rel="stylesheet" href="../css/global.css">
<!-- Form to add a new member -->
<h1>Update Members</h1>
<h3>Add New Member</h3>
<form method="post" action="index.php?page=member_management&action=toevoegen">
    <label for="naam">Name:</label>
    <input type="text" name="naam" required>

    <label for="adres">Address:</label>
    <input type="text" name="adres" required>

    <label for="geboortedatum">Date of birth:</label>
    <input type="date" name="geboortedatum" required>

    <label for="gebruikersnaam">Username:</label>
    <input type="text" name="gebruikersnaam" required>

    <label for="wachtwoord">Password:</label>
    <input type="password" name="wachtwoord" required>

    <!-- Dropdown for existing family -->
    <label for="familie_id">Family:</label>
    <select name="familie_id">
        <option value="">Family</option>
        <?php
        //Retrieve families
        foreach ($families as $familie) {
            //Display name and address together in the dropdown
            echo "<option value='{$familie['id']}'>" . htmlspecialchars($familie['naam']) . " - " . htmlspecialchars($familie['adres']) . "</option>";
        }
        ?>
    </select>

    <label for="rol">Role:</label>
    <select name="rol" required>
        <?php
    //Retrieve the roles from the roles table
        foreach ($roles as $role) {
            echo "<option value='" . $role['id'] . "'>" . $role['rol_soort'] . "</option>";
        }
    ?>
    </select>
    <button type="submit">Add member</button>
</form>


<!-- Form to add a new family -->
<h3>Add Family</h3>
<form method="post" action="index.php?page=member_management&action=toevoegen_familie">
    <label for="familie_naam">Name:</label>
    <input type="text" name="familie_naam" placeholder="Naam" required>

    <label for="nieuwe_familie_adres">Address:</label>
    <input type="text" name="nieuwe_familie_adres" placeholder="Adres" required>

    <button type="submit">Add family</button>
</form>

<!-- Form to delete a family -->
<h3>Delete Family</h3>
<form method="post" action="index.php?page=member_management&action=verwijder_familie">
    <input type="hidden" name="familie_id" value="<?= $familie['id'] ?>">
    <label for="familie_id">Select a family:</label>
    <select name="familie_id" required>
        <option value="">Choose a family</option>
        <?php
        foreach ($families as $familie) {
            echo "<option value='{$familie['id']}'>" . htmlspecialchars($familie['naam']) . " - " . htmlspecialchars($familie['adres']) . "</option>";
        }
        ?>
    </select>
    <button type="submit" onclick="return confirm('First, remove all family members before deleting a family. Click \'Cancel\' if you haven\'t done this yet, click \'OK\' to delete.')">Delete family</button>
</form>
<hr>
<!-- Table view of members -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Date Of Birth</th>
            <th>Username</th>
            <th>Password</th>
            <th>Family ID</th>
            <th>Member Type</th>
            <th>Role</th>
            <th>Update</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <!-- Check if there is member data -->
        <?php if (isset($leden) && !empty($leden)): ?>
        <?php foreach ($leden as $lid): ?>
            <tr>
                <td><?= htmlspecialchars($lid['id']) ?></td>
                <td><?= htmlspecialchars($lid['naam']) ?></td>
                <td><?= htmlspecialchars($lid['adres']) ?></td> 
                <td><?= htmlspecialchars($lid['geboortedatum']) ?></td>
                <td><?= htmlspecialchars($lid['gebruikersnaam']) ?></td>
                <td>******</td> <!-- The password is not displayed in plaintext -->
                <td><?= htmlspecialchars($lid['familie_id']) ?></td>
                <td><?= htmlspecialchars($lid['soort_lid']) ?></td> 
                <td><?= htmlspecialchars($lid['rol_soort']) ?></td>
                <td><a href="index.php?page=update_member&action=bewerk&id=<?= htmlspecialchars($lid['id']) ?>">Update</a></td>
                <td><a href="index.php?page=member_management&action=verwijder&id=<?= $lid['id'] ?>" 
                onclick="return confirm('First, adjust the person\'s contributions, remove the name and place it in the \'note\'. Click \'Cancel\' if you haven not done this yet, click \'OK\' to delete.')">Delete</a></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <!-- Display a message if no member details are available -->
            <tr>
                <td colspan="3">No member data available.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>
