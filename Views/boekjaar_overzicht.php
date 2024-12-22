<?php
//Start de sessie als deze nog niet is gestart.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Controleer of de gebruiker is ingelogd met de juiste rol. 
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'penningmeester' && $_SESSION['role'] !== 'admin')) {
    //Gebruiker is niet ingelogd of heeft geen toegang.
    header('Location: index.php?page=login');
    exit;
}

?>
<?php include('header.php'); ?>
<link rel="stylesheet" href="../css/global.css">

<h1>Overzicht van contributies voor boekjaar: <?= htmlspecialchars($boekjaar['jaar']) ?></h1>

<!-- Formulier voor het selecteren van een boekjaar. -->
<form method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="dropdown-form">
    <input type="hidden" name="page" value="boekjaar_overzicht"> 
    <label for="boekjaar_id">Selecteer een boekjaar:</label>
    <select name="boekjaar_id" id="boekjaar_id" onchange="this.form.submit()">
        <option value="">Selecteer een boekjaar</option>
        <?php foreach ($boekjaren as $boekjaar_optie): ?>
            <option value="<?= htmlspecialchars($boekjaar_optie['id']) ?>" <?= isset($_GET['boekjaar_id']) && $_GET['boekjaar_id'] == $boekjaar_optie['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($boekjaar_optie['jaar']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

        <!-- Toon de financiële gegevens: inkomsten, uitgaven, belastingen en totaal. -->
        <p>Inkomsten: € <?= number_format(htmlspecialchars($inkomsten_totaal), 2, ',', '.') ?></p>
        <p>Uitgaven: € <?= number_format(htmlspecialchars($uitgaven_totaal), 2, ',', '.') ?></p>
        <p>Belastingen: € <?= number_format(htmlspecialchars($belastingen_totaal), 2, ',', '.') ?></p>
        <p>Totaal: € <?= number_format(htmlspecialchars($totaal), 2, ',', '.') ?></p>
          
<?php if ($contributies): ?>
    <!-- Toon de contributies in een tabel als er contributies zijn. -->
    <hr>
    <table>
        <thead>
            <tr>
                <th>Contributie ID</th>
                <th>Familielid</th>
                <th>Bedrag</th>
                <th>Type</th>
                <th>Betaaldatum</th>
                <th>Aantekening</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contributies as $contributie): ?>
                <!-- Toon elke contributie in een rij. -->
                <tr>
                    <td><?= htmlspecialchars($contributie['id']) ?></td>
                    <td><?= htmlspecialchars($contributie['naam']) ?></td>
                    <td>€ <?= number_format(htmlspecialchars($contributie['bedrag']), 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($contributie['type']) ?></td>
                    <td><?= htmlspecialchars($contributie['betaaldatum']) ?></td>
                    <td><?= htmlspecialchars($contributie['aantekening']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- Als er geen contributies zijn, toon dan een melding. -->
    <p>Geen contributies gevonden voor dit boekjaar.</p>
<?php endif; ?>
<?php include('footer.php'); ?>