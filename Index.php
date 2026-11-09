<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Calcolatore di sconto</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>

<?php
$prodotto = "";
$prezzo = "";
$sconto = "";
$Eprodotto = "";
$Eprezzo = "";
$Esconto = "";
$calcolato = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prodotto = $_POST['prodotto'];
    $prezzo = $_POST['prezzo'];
    $sconto = $_POST['sconto'];

    if ($prodotto == "") {
        $Eprodotto = "Il prodotto è obbligatorio";
    } elseif (isset($prodotto[30])) {
        $$Eprodotto = "Massimo 30 caratteri";
    }

    if ($prezzo == "" || $prezzo <= 0) {
        $Eprezzo = "Il prezzo deve essere maggiore di 0";
    }

    if ($sconto == "" || $sconto < 1 || $sconto > 90) {
        $Esconto = "Lo sconto deve essere da 1 a 90";
    }

    if ($Eprodotto == "" && $Eprezzo == "" && $Esconto == "") {
        $calcolato = true;

        $EuroScontati = ($prezzo * $sconto) / 100;
        $EuroScontati = round($EuroScontati, 2);
        $prezzosconto = $prezzo - $EuroScontati;
        $prezzosconto = round($prezzosconto, 2);

        if ($sconto < 20) {
            $classe = "piccolo";
            $testo = "PICCOLO";
        } elseif ($sconto <= 49) {
            $classe = "buono";
            $testo = "BUONO";
        } else {
            $classe = "super";
            $testo = "SUPER";
        }

        if ($myfile = @fopen("log.csv", "a")) {
            $record = implode("; ", [
                $prodotto,
                $prezzo,
                $sconto,
                $EuroScontati,
                $prezzosconto,
                $testo
            ]) . ";\n";
            fwrite($myfile, $record);
            fclose($myfile);
        }
    }
}
?>

    <h1>Calcolatore di sconto</h1>

    <form method="post" action="index.php">

        <label for="prodotto">Prodotto</label>
        <input type="text" id="prodotto" name="prodotto" value="<?php echo $prodotto; ?>" <?php if ($Eprodotto != '') echo 'class="errore"'; ?>>
        <?php if ($Eprodotto != ''): ?>
            <p class="msg-errore"><?php echo $Eprodotto; ?></p>
        <?php endif; ?>

        <label for="prezzo">Prezzo (€)</label>
        <input type="text" id="prezzo" name="prezzo" value="<?php echo $prezzo; ?>" <?php if ($Eprezzo != '') echo 'class="errore"'; ?>>
        <?php if ($Eprezzo != ''): ?>
            <p class="msg-errore"><?php echo $Eprezzo; ?></p>
        <?php endif; ?>

        <label for="sconto">Sconto (%)</label>
        <input type="text" id="sconto" name="sconto" value="<?php echo $sconto; ?>" <?php if ($Esconto != '') echo 'class="errore"'; ?>>
        <?php if ($Esconto != ''): ?>
            <p class="msg-errore"><?php echo $Esconto; ?></p>
        <?php endif; ?>

        <button type="submit">Calcola</button>
    </form>

    <?php if ($calcolato): ?>
        <div class="card <?php echo $classe; ?>">
            <h2><?php echo $prodotto; ?></h2>
            <p>Prezzo: <?php echo $prezzo; ?> € &middot; Sconto: <?php echo $sconto; ?> %</p>
            <p>Prezzo finale: <?php echo $prezzosconto; ?> €</p>
            <p>Risparmio: <?php echo $EuroScontati; ?> €</p>
            <p><?php echo $testo; ?></p>
        </div>
    <?php endif; ?>

    <?php if ($myfile = @fopen("log.csv", "r")): ?>
        <h2>Storico</h2>
        <?php while ($riga = fgets($myfile)): ?>
            <p><?php echo $riga; ?></p>
        <?php endwhile; ?>
        <?php fclose($myfile); ?>
    <?php endif; ?>

</body>
</html>