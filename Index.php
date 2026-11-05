<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Calcolatore di sconto</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <h1>Calcolatore di sconto</h1>
    <form method="post" action="index.php">
        <label for="prodotto">Prodotto</label>
        <input type="text" id="prodotto" name="prodotto">
        <label for="prezzo">Prezzo (€)</label>
        <input type="text" id="prezzo" name="prezzo" class="errore">
        <p class="msg-errore">Il prezzo deve essere maggiore di 0</p>
        <label for="sconto">Sconto (%)</label>
        <input type="text" id="sconto" name="sconto">
        <button type="submit">Calcola</button>
    </form>

    <?php

        $errori = [];
        $calcolo_sconto = false;




        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $prodotto = isset($_POST['prodotto']) ? htmlspecialchars(trim($_POST['prodotto'])) : "";
            $prezzo = isset($_POST['prezzo']) ? (float)$_POST['prezzo'] : 0;
            $sconto = isset($_POST['sconto']) ? (float)$_POST['sconto'] : 0;

            if (empty($prodotto)){
                $errori['prodotto'] = "Valore non valido";
            }

            if (empty($prezzo < 0)){
                $errori['prezzo'] = "Valore non valido";
            }

            if (empty($sconto < 0)){
                $errori['sconto'] = "Valore non valido";
            }
        

            if(empty($errori)){
                $calcolo_sconto = true;
                $prezzoS = $prodotto * ($sconto/100);

            }

        }



        $log = fopen("log.csv", "a") or die("non puoi entrare");

        if ($log == TRUE){
            $record = "";
            $record .= $prodotto . ";";
            $record = "";
            $record .= $prezzo . ";";
            $record = "";
            $record .= $sconto . ";";
            $record = "";
            $record .= $prezzoS . ";";

            fwrite($log, $record);
            fclose($log);

        }
    ?>
</body>
</html>




