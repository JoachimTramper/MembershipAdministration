<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Membership Administration'; ?></title>
    <link rel="stylesheet" href="../css/global.css">
</head>
<body>
    <!-- Header section -->
    <header class="header">
        <nav>
            <div>
                <!-- Logo or title of the website -->
                <h1>Membership Administration of Billiards Club 'The Crooked Cue'</h1>
            </div>

            <!-- Navigation menu -->
            <ul>
                <!-- Link to 'Dashboard' -->
                <li><a href="../index.php?page=dashboard&id<?= $_SESSION['user_id']; ?>">Dashboard</a></li>

                <!-- Link to 'Members Overview' -->
                <li><a href="../index.php?page=members_overview">Members Overview</a></li>

                <!-- Link to 'Account Management' -->
                <li><a href="../index.php?page=account_management">Account Management</a></li>

                <!-- Link to 'Member Management' only for secretary or admin -->
                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'secretary' || $_SESSION['role'] == 'admin')): ?>
                <li><a href="../index.php?page=member_management&id=<?= $_SESSION['user_id']; ?>">Member Management</a></li>
                <?php endif; ?>         
                <!-- Link to 'Contributions' only for treasurer or admin. -->
                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'treasurer' || $_SESSION['role'] == 'admin')): ?>
                <li><a href="../index.php?page=contributions_overview&id<?= $_SESSION['user_id']; ?>">Contributions</a></li>
                <?php endif; ?> 

                <!-- Link to logout page-->
                <li><a href="../index.php?page=logout">Logout</a></li>
            </ul>
        </nav>
    </header>
    <!-- Main content of the page -->
<main>
