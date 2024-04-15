<?php
    include('cardstorage.php');
    include('Auth.php');

    session_start();
    $cardStorage = new CardStorage();
    $userStorage = new Storage(new JsonIO('users.json'));
    $auth=new Auth($userStorage);
    
    

    if(isset($_POST['logout'])){
        $auth->logout();
    }
    if(isset($_POST['buy']) && isset($_POST['id'])){

        $cid = $_POST['id'];
        $uid = $_SESSION['user']['id'];

        $card = $cardStorage->findById($cid);
        $user = $userStorage->findById($uid);

       if( count($cardStorage -> findByOwner($uid)) === 5){
            $message = "User reached max card limit! (5 cards)";
            echo "<script type='text/javascript'>alert('$message');</script>";
       }
       else if($user['balance']-$card['price']<0){
            $message = "Insufficient funds!";
            echo "<script type='text/javascript'>alert('$message');</script>";
       }
       else{

            //find card
            //set owner from admin to userid
            $card['owner']=$uid;
            $cardStorage->update($cid,$card);
            
            $user['balance'] -= $card['price'];
            $userStorage->update($uid,$user);
            $_SESSION["user"]=$user;
            //money wont decrease on refreshing
            header('Location: index.php');
       }
        
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    
</head>

<body>
    <header class="bg-blur p-3" >
        <h1><a href="index.php">IKémon</a> > Home </h1>
        <div style="float:right;">
            <?php 
                if(isset($_SESSION["user"])){
                    ?>
                        <form method="POST">
                            <?php if($_SESSION['user']['username']!=="admin"){ ?>
                                <span>Balance: <?= $_SESSION["user"]['balance'] ?>💰</span>
                            <?php } ?>
                            <a href="profile.php" style="font-size:20px;margin:10px 10px"> <?= $_SESSION["user"]['username'] ?></a>
                            <button class="btn btn-light me-3" name="logout">Logout</button>
                        </form>
                    <?php
                    
                }
                else{
            ?>
                <a href="login.php">Login</a> | <a href="register.php">Register</a>
            <?php
                }
            ?>
        </div>
    </header>

    <div class="container">
        <div class="jumbotron mt-4 p-5 bg-blur text-white rounded">
            <h2 class="text-white">
                Welcome to IKémon!
            </h2>
            <p>
                This is your go-to hub for exploring the digital evolution of <b>Pokémon card trading</b>!
                </p>
            <p>
                Whether you're a seasoned collector, a casual enthusiast, or a curious newcomer, our platform provides a user-friendly and secure space for trading Pokémon cards. Immerse yourself in a virtual marketplace where you can discover, trade, and connect with a diverse community of fellow trainers.
            </p>
            <p>
                IKémon is your gateway to the exciting world of Pokémon card trading in the digital realm. Join us to share, trade, and celebrate their love for Pokémon cards!
            </p>
            <h3 class="text-white">
                If we've sparked your interest, register and dive into card collecting!
            </h3>
        </div>
    </div>

    <div id="content">
        <form method="POST" class="input-group">
            <label class="input-group-text" for="filterSelect">Filter by types:</label> 
            <select class="custom-select form-select" name="filter" id="filter">
                <option value="none">None</option>
                <?= $cardStorage->makeopts() ?>
            </select>
            <button type="submit" class="btn btn-secondary" type="button" name="filterButton">Filter</button>
        </form>

        

        <div id="card-list">
            <?php
                $cards = $cardStorage->findAll();
                foreach($cards as $card){
                    if( !isset($_POST['filter']) ||  isset($_POST['filter']) && ( $card['type'] === $_POST['filter'] || $_POST['filter'] === "none") )
                    {
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
                            if((isset($_SESSION['user']) && $_SESSION['user']['username']!=="admin") && $card['owner']==="admin"){
                        ?>
                            <form method="POST">
                                <button class="buy w-100" name="buy">
                                    <span class="card-price"><span class="icon">💰</span> <?= $card['price'] ?></span>
                                    <input type="hidden" name="id" value="<?= $card['id'] ?>">
                                </button>
                            </form>
                        <?php
                            }
                        ?>
                    </div>
                    <?php
                    }
                }
                if(isset($_SESSION['user']) && $_SESSION['user']['username']==="admin"){
                    ?>
                        <form action="add.php" method="POST" class="mt-5">
                            <button class="btn btn-lg btn-primary">Add new card</button>
                        </form>
                    <?php
                }
            ?>
            
        </div>
    </div>
    <footer class="bg-blur p-5">
        <p>Puskás Márk | IKémon | ELTE IK Webprogramozás</p>
    </footer>
    
    
</body>

</html>