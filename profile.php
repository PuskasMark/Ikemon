<?php
    include('cardstorage.php');
    session_start();
    $cardStorage = new CardStorage();
    $userStorage = new Storage(new JsonIO('users.json'));
    
    if(isset($_POST['sell']) && isset($_POST['id'])){

        $cid = $_POST['id'];
        $uid = $_SESSION['user']['id'];

        $card = $cardStorage->findById($cid);
        $user = $userStorage->findById($uid);

        //find card
        //set owner from userid to admin
        $card['owner']="admin";
        $cardStorage->update($cid,$card);
        
        $user['balance'] += round($card['price']*0.9);
        $userStorage->update($uid,$user);
        $_SESSION["user"]=$user;
        //money wont increase on refreshing
        header('Location: profile.php');
       
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
   

</head>
<body>
    <header class="bg-blur p-3">
        <h1><a href="index.php">IKémon</a> > <?= $_SESSION['user']['username'] ?> </h1>
        <div style="float:right">
            <?php if($_SESSION['user']['username']!=="admin"){ ?>
                <span>Balance: <?= $_SESSION["user"]['balance'] ?>💰</span>
            <?php } ?>
            <span>emai-address: <?= $_SESSION['user']['email'] ?> </span>
        </div>
    </header>

    <div id="content" class="text-center">
        <h2>User's cards:</h2>
        <?php
            if(isset($_SESSION['user']) && $_SESSION['user']['username']==="admin"){
        ?>
                <form action="add.php" method="POST" class="mt-5">
                    <button class="btn btn-lg btn-primary">Add new card</button>
                </form>
        <?php
            }
        ?>

    <div id="card-list">
        <?php
            //find all cards with userid owner
            $uid = $_SESSION['user']['id'];

            $cards = $cardStorage -> findByOwner($uid);

            

            foreach($cards as $card){
        ?>
                <div class="pokemon-card">
                    <div class="image clr-<?= $card['type'] ?>">
                        <img src="<?= $card['image'] ?>" alt="">
                    </div>
                    <div class="details">
                        <h2><a href="details.php?id=<?= $card['id'] ?>"><?= $card['name'] ?></a></h2>
                        <span class="card-type"><span class="icon">🏷</span> <?= $card['type'] ?></span>
                        <span class="attributes">
                            <span class="card-hp"><span class="icon">❤</span> <?= $card['hp'] ?></span>
                            <span class="card-attack"><span class="icon">⚔</span> <?= $card['attack'] ?></span>
                            <span class="card-defense"><span class="icon">🛡</span> <?= $card['defense'] ?></span>
                        </span>
                    </div>
                    <?php
                    if(isset($_SESSION['user']) && $_SESSION['user']['username']!=="admin"){
                    ?>
                        <form method="POST">
                            <button name="sell" class="btn btn-outline-success w-100">Sell (<?= round($card['price']*0.9)?>💰)</button>
                            <input type="hidden" name="id" value="<?= $card['id'] ?>">
                        </form>
                    <?php
                        }
                    ?>
                </div>
        <?php
            }
            
        ?>
    </div>
    </div>

</body>
</html>