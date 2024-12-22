<?php
//Start de sessie indien deze nog niet gestart is.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Controleer of de gebruiker ingelogd is.
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // Als de gebruiker niet ingelogd is, doorverwijzen naar loginpagina.
    header("Location: login.php");
    exit;
}
include('header.php'); ?>

<link rel="stylesheet" href="../css/global.css">
<h1>Leden overzicht</h1>
<!-- Tabel voor het weergeven van ledeninformatie. -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Adres</th>
            <th>Soort Lid</th>
        </tr>
    </thead>
    <tbody>
        <!-- Controleer of er ledengegevens zijn. -->
        <?php if (isset($leden) && !empty($leden)): ?>
            <?php foreach ($leden as $lid): ?>
                <!-- Weergeef elk lid in een nieuwe rij. -->
                <tr>
                    <td><?php echo htmlspecialchars($lid['id']); ?></td>
                    <td><?php echo htmlspecialchars($lid['naam']); ?></td>
                    <td><?php echo htmlspecialchars($lid['adres']); ?></td>
                    <td><?php echo htmlspecialchars($lid['soort_lid']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Toon een bericht als er geen ledengegevens beschikbaar zijn. -->
            <tr>
                <td colspan="3">Geen ledengegevens beschikbaar.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>
