<?php
    session_start();
    include('cardstorage.php');
    $cardStorage = new CardStorage();

    function urlExists($url) {
        $headers = @get_headers($url);
        // If $headers is not false and the first element contains "200 OK", then the URL exists
        return $headers && strpos($headers[0], '200 OK') !== false;
    }
    function validateNums($value,$max) {
        if (!(1 <= $value && $value <= $max))
        {
            return "The value must be between 1 and ".$max." !";
        } 
        return true;
    }
    
    $name = isset($_POST) && isset($_POST["name"]) ? $_POST["name"] : "";
    $hp = isset($_POST) && isset($_POST["hp"]) ? $_POST["hp"] : "";
    $attack = isset($_POST) && isset($_POST["attack"]) ? $_POST["attack"] : "";
    $defense = isset($_POST) && isset($_POST["defense"]) ? $_POST["defense"] : "";
    $price = isset($_POST) && isset($_POST["price"]) ? $_POST["price"] : "";
    $description = isset($_POST) && isset($_POST["description"]) ? $_POST["description"] : "";
    $image = isset($_POST) && isset($_POST["image"]) ? $_POST["image"] : "";
    
    $errorMessages=[];
    if(!empty($_POST['name']) && !empty($_POST['type']) && !empty($_POST['hp']) && !empty($_POST['attack']) && !empty($_POST['defense']) && !empty($_POST['price']) && !empty($_POST['description']) && !empty($_POST['image']) )
    {
        if(!urlExists($_POST['image']))
        {
            $errorMessages['image'] = "The URL does not exist";
        }
        if (($result = validateNums($_POST['hp'],200)) !== true) $errorMessages["hp"] = $result;
        if (($result = validateNums($_POST['attack'],200)) !== true) $errorMessages["attack"] = $result;
        if (($result = validateNums($_POST['defense'],200)) !== true) $errorMessages["defense"] = $result;
        if (($result = validateNums($_POST['price'],1000)) !== true) $errorMessages["price"] = $result;


        //$data=$_POST;
        //&& isset($_POST['name']) && isset($_POST['type']) && isset($_POST['hp']) && isset($_POST['attack']) && isset($_POST['defense']) && isset($_POST['price']) && isset($_POST['description']) && isset($_POST['image'])1
        if (count($errorMessages) == 0)
        {
            $card=[
                "name"=> $_POST['name'],
                "type"=> $_POST['type'],
                "hp"=> $_POST['hp'],
                "attack"=> $_POST['attack'],
                "defense"=> $_POST['defense'],
                "price"=> $_POST['price'],
                "description"=> $_POST['description'],
                "image"=> $_POST['image'],
                "owner"=> "admin"
            ];

            $cardStorage->add($card);

            $message = "Card succesfully added!";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    }
    else if(isset($_POST['add'])){
        $errorMessages['fields'] = "All fields must be filled in";
    }
    
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Add</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/form.css">

</head>
<body>
    <header class="bg-blur p-3">
        <h1><a href="index.php">IKémon</a> > New Card </h1>
    </header>
    
    <div class="col-11 col-xl-6 col-xxl-4 mt-4 p-5 bg-blur text-white rounded mx-auto">
        <form method="POST" novalidate>
            <h1 class="mb-5">Add new Card</h1>
            <?php if (isset($errorMessages["fields"])) echo "<p style=\"color: orange\">{$errorMessages["fields"]}</p>" ?>
            <label>Name</label>
            <input type="text" name="name" placeholder="Charizard" class="w-100" value="<?= $name ?>" required>
            <label>Type</label>
            <select id="pokemonType" name="type" class="form-select form-select-lg mb-3">
                <?php $cardStorage->makeopts() ?>
            </select>
            <?php if (isset($errorMessages["hp"])) echo "<p style=\"color: orange\">{$errorMessages["hp"]}</p>" ?>
            <label>Health points</label>
            <input type="number" min="1" max="200" name="hp" placeholder="78" class="w-100" value="<?= $hp ?>" required>
            <?php if (isset($errorMessages["attack"])) echo "<p style=\"color: orange\">{$errorMessages["attack"]}</p>" ?>
            <label>Attack</label>
            <input type="number" min="1" max="200" name="attack" placeholder="84" class="w-100" value="<?= $attack ?>" required>
            <?php if (isset($errorMessages["defense"])) echo "<p style=\"color: orange\">{$errorMessages["defense"]}</p>" ?>
            <label>Defense</label>
            <input type="number" min="1" max="200" name="defense" placeholder="78" class="w-100" value="<?= $defense ?>" required>
            <?php if (isset($errorMessages["price"])) echo "<p style=\"color: orange\">{$errorMessages["price"]}</p>" ?>
            <label>Price</label>
            <input type="number" min="1" max="1000" name="price" placeholder="534" class="w-100" value="<?= $price ?>" required>
            <label>Description</label>
            <textarea name="description" placeholder="description" class="w-100" required><?= $description ?></textarea>
            <?php if (isset($errorMessages["image"])) echo "<p style=\"color: orange\">{$errorMessages["image"]}</p>" ?>
            <label>Image source (link only)</label>
            <input type="text" name="image" placeholder="https://assets.pokemon.com/assets/cms2/img/pokedex/full/006.png" class="w-100" value="<?= $image ?>" required>
            
            
            <button type="submit" name="add" class="btn btn-light btn-lg w-100">Add</button>

        </form>
    </div>

</body>
</html>