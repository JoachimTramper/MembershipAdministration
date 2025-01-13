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
<h1>Member Management</h1>
<h3>Add New Member</h3>
<form method="post" action="index.php?page=member_management&action=add">
    <label for="name">Name:</label>
    <input type="text" name="name" required>

    <label for="address">Address:</label>
    <input type="text" name="address" required>

    <label for="dob">Date of birth:</label>
    <input type="date" name="dob" required>

    <label for="username">Username:</label>
    <input type="text" name="username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <!-- Dropdown for existing family -->
    <label for="family_id">Family:</label>
    <select name="family_id">
        <option value="">Family</option>
        <?php
        //Retrieve families
        foreach ($families as $family) {
            //Display name and address together in the dropdown
            echo "<option value='{$family['id']}'>" . htmlspecialchars($family['name']) . " - " . htmlspecialchars($family['address']) . "</option>";
        }
        ?>
    </select>

    <label for="role">Role:</label>
    <select name="role" required>
        <?php
    //Retrieve the roles from the roles table
        foreach ($roles as $role) {
            echo "<option value='" . $role['id'] . "'>" . $role['role_type'] . "</option>";
        }
    ?>
    </select>
    <button type="submit">Add member</button>
</form>


<!-- Form to add a new family -->
<h3>Add Family</h3>
<form method="post" action="index.php?page=member_management&action=add_family">
    <label for="family_name">Name:</label>
    <input type="text" name="family_name" placeholder="Name" required>

    <label for="new_family_address">Address:</label>
    <input type="text" name="new_family_address" placeholder="Address" required>

    <button type="submit">Add family</button>
</form>

<!-- Form to delete a family -->
<h3>Delete Family</h3>
<form method="post" action="index.php?page=member_management&action=delete_family">
    <input type="hidden" name="family_id" value="<?= $family['id'] ?>">
    <label for="family_id">Select a family:</label>
    <select name="family_id" required>
        <option value="">Choose a family</option>
        <?php
        foreach ($families as $family) {
            echo "<option value='{$family['id']}'>" . htmlspecialchars($family['name']) . " - " . htmlspecialchars($family['address']) . "</option>";
        }
        ?>
    </select>
    <button type="submit" onclick="return confirm('Please remove all family members before deleting a family. Click \'Cancel\' if this hasn\'t been done yet, or click \'OK\' to proceed with deletion.')">Delete family</button>
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
        <?php if (isset($members) && !empty($members)): ?>
        <?php foreach ($members as $member): ?>
            <tr>
                <td><?= htmlspecialchars($member['id']) ?></td>
                <td><?= htmlspecialchars($member['name']) ?></td>
                <td><?= htmlspecialchars($member['address']) ?></td> 
                <td><?= htmlspecialchars($member['dob']) ?></td>
                <td><?= htmlspecialchars($member['username']) ?></td>
                <td>******</td> <!-- The password is not displayed in plaintext -->
                <td><?= htmlspecialchars($member['family_id']) ?></td>
                <td><?= htmlspecialchars($member['member_type']) ?></td> 
                <td><?= htmlspecialchars($member['role_type']) ?></td>
                <td><a href="index.php?page=update_member&action=update&id=<?= htmlspecialchars($member['id']) ?>">Update</a></td>
                <td><a href="index.php?page=member_management&action=delete&id=<?= $member['id'] ?>" 
                onclick="return confirm('Please adjust the person\'s contributions, remove the name and add it to the \'note\' section. Click \'Cancel\' if this hasn\'t been done yet, click \'OK\' to proceed with deletion.')">Delete</a></td>
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
