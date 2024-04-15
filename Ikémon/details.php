<?php
    include('storage.php');
    $cardStorage = new Storage(new JsonIO('cards.json'));

    $id = $_GET['id'];
    $card = $cardStorage->findById($id);
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Pikachu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
    <header class="bg-blur p-3">
        <h1><a href="index.php">IKémon</a> > <?= $card['name'] ?> </h1>
    </header>
    <div class="mx-auto col-10 col-md-5 col-xxl-3 my-5">
        <div class="card p-4">
        <h5 class="card-title"><?= $card['name'] ?></h5>
            <img class="card-img-top clr-<?= $card['type'] ?>" src="<?= $card['image'] ?>" alt="Card image cap">
            <div class="card-body">
                <div class="card-text mb-3">
                        <?= $card['description'] ?> 
                </div>
                <span class="card-type"><span class="icon">🏷</span> Type: <?= $card['type'] ?> </span>
                <div class="card-hp"><span class="icon">❤</span> Health: <?= $card['hp'] ?> </div>
                <div class="card-attack"><span class="icon">⚔</span> Attack: <?= $card['attack'] ?> </div>
                <div class="card-defense"><span class="icon">🛡</span> Defense: <?= $card['defense'] ?> </div>
            </div>
        </div>
    </div>

    <footer class="bg-blur p-5">
        <p>Puskás Márk | IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>