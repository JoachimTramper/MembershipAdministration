<?php
//Start sessie indien deze nog niet gestart is.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//Controleer of de gebruiker is ingelogd en of de sessie geldig is.
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: ../index.php");
    exit;
}

include 'header.php';
?>

<link rel="stylesheet" href="../css/global.css">
<h1>Welkom, <?php echo htmlspecialchars($gebruiker); ?>! U bent ingelogd als <?php echo htmlspecialchars($rol); ?>.</h1>
<h2>Overzicht van betalingen per familie</h2>
<?php if (empty($leden)): ?>
    <!-- Toon dit bericht als er geen familieleden zijn of als er geen betalingen zijn gevonden. -->
    <p>Er zijn geen familieleden of betalingen gevonden.</p>
<?php else: ?>
    <!-- Tabel met familieleden en hun openstaande betalingen. -->
    <table>
        <thead>
            <tr>
                <th>Familielid</th>
                <th>Openstaande Betalingen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leden as $lid): ?>
                <tr>
                    <td><?= htmlspecialchars($lid['naam']) ?></td>
                    <td>
                        <?php if ($lid['bedrag'] !== null): ?>
                            <!-- Als er een openstaand bedrag is, toon het met een euroteken en formateer het als valuta. -->
                            € <?= number_format($lid['bedrag'], 2, ',', '.') ?>
                        <?php else: ?>
                            <!-- Als er geen openstaand bedrag is, geef een alternatieve boodschap. -->
                            Geen openstaande betaling
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<br>
    <!-- Totaal van alle openstaande betalingen weergeven. -->
    <h3>Totaal Openstaande Betalingen: € <?= number_format($totaal_openstaande_betalingen, 2, ',', '.') ?></h3>
</body>
<?php include 'footer.php'; ?>
