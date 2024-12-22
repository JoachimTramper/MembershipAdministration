<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Ledenadministratie'; ?></title>
    <link rel="stylesheet" href="../css/global.css">
</head>
<body>
    <!-- Header sectie. -->
    <header class="header">
        <nav>
            <div>
                <!-- Logo of titel van de website. -->
                <h1>Ledenadministratie Biljartvereniging 'De Kromme Keu'</h1>
            </div>

            <!-- Navigatiemenu. -->
            <ul>
                <!-- Link naar het dashboard. -->
                <li><a href="../index.php?page=dashboard&id<?= $_SESSION['user_id']; ?>">Dashboard</a></li>

                <!-- Link naar het ledenoverzicht. -->
                <li><a href="../index.php?page=ledenoverzicht">Leden Overzicht</a></li>

                <!-- Link naar accountbeheer. -->
                <li><a href="../index.php?page=account">Account Beheer</a></li>

                <!-- Link naar 'Leden Bewerken' alleen voor secretaris of admin. -->
                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'secretaris' || $_SESSION['role'] == 'admin')): ?>
                <li><a href="../index.php?page=leden_bewerken&id=<?= $_SESSION['user_id']; ?>">Leden Bewerken</a></li>
                <?php endif; ?>         
                <!-- Link naar 'Contributies' alleen voor penningmeester of admin. -->
                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'penningmeester' || $_SESSION['role'] == 'admin')): ?>
                <li><a href="../index.php?page=contributies_overzicht&id<?= $_SESSION['user_id']; ?>">Contributies</a></li>
                <?php endif; ?> 

                <!-- Link naar de logoutpagina. -->
                <li><a href="../index.php?page=logout">Logout</a></li>
            </ul>
        </nav>
    </header>
    <!-- Hoofdinhoud van de pagina. -->
<main>
