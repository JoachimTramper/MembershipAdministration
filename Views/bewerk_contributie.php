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

//Laad de header voor de pagina.
include('header.php');
?>

<link rel="stylesheet" href="../css/global.css">
<h2>Contributie Gegevens Bewerken</h2>

<!-- Toon de gegevens van de contributie. -->
<p><strong>ID:</strong> <?= htmlspecialchars($contributie['id']) ?></p>
<p><strong>Familielid:</strong> <?= htmlspecialchars($contributie['familielid_naam'] ?? 'Geen familielid') ?>
<p><strong>Bedrag:</strong> <?= htmlspecialchars($contributie['bedrag']) ?></p>
<p><strong>Type:</strong> <?= htmlspecialchars($contributie['type']) ?></p>
<p><strong>Betaaldatum:</strong> <?= htmlspecialchars($contributie['betaaldatum'] ?? 'Nog niet betaald') ?></p>

<!-- Formulier voor het bewerken van de contributie. -->
<form action="index.php?page=bewerk_contributie&id=<?= $contributie['id']; ?>" method="POST">
    <!-- Verborgen velden voor id en familielid_id. -->
    <input type="hidden" name="id" value="<?= $contributie['id']; ?>">
    <input type="hidden" name="familielid_id" value="<?= $contributie['familielid_id']; ?>">

    <!-- Veld voor het bedrag van de contributie. -->
    <label for="bedrag">Bedrag:</label>
    <input type="number" step="0.01" name="bedrag" id="bedrag" value="<?= htmlspecialchars($contributie['bedrag']) ?>" required>

    <!-- Selectie voor het type contributie. -->
    <label for="type">Type:</label>
    <select name="type" id="type">
        <option value="inkomsten" <?= $contributie['type'] == 'inkomsten' ? 'selected' : '' ?>>Inkomsten</option>
        <option value="uitgaven" <?= $contributie['type'] == 'uitgaven' ? 'selected' : '' ?>>Uitgaven</option>
        <option value="belastingen" <?= $contributie['type'] == 'belastingen' ? 'selected' : '' ?>>Belastingen</option>
        <option value="anders" <?= $contributie['type'] == 'anders' ? 'selected' : '' ?>>Anders</option>
    </select>

    <!-- Veld voor de betaaldatum. -->
    <label for="betaaldatum">Betaaldatum:</label>
    <input type="date" name="betaaldatum" id="betaaldatum">

    <!-- Dropdown voor boekjaar. -->
    <label for="boekjaar_id">Boekjaar:</label>
    <select name="boekjaar_id" required>
        <option value="">Selecteer een boekjaar</option>
        <?php foreach ($boekjaren as $boekjaar): ?>
            <option value="<?= htmlspecialchars($boekjaar['id']) ?>" <?= $contributie['boekjaar_id'] == $boekjaar['id'] ? 'selected' : '' ?>><?= htmlspecialchars($boekjaar['jaar']) ?></option>
        <?php endforeach; ?>
    </select>
    
    <!-- Veld voor een aantekening bij de contributie. -->
    <label for="aantekening">Aantekening:</label>
    <textarea name="aantekening" id="aantekening"><?= htmlspecialchars($contributie['aantekening']) ?></textarea>
    
    <!-- Opslaan knop. -->
    <button type="submit" name="bewerk_contributie" value="bewerken">Opslaan</button>
</form>
<!-- Laad de footer voor de pagina. -->
<?php include('footer.php'); ?>
