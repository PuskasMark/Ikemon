<?php

    include('Auth.php');
    include('userstorage.php');

    // functions
    function validate($post, &$data, &$errors) {
        // username, password not empty
        if(!empty($post['username'] && !empty($post['password']))){
            $data = $post;
        }
        else{
            $errors['global'] = "Empty field(s)!";
        }

        return count($errors) === 0;
    }
    function redirect($page) {
        header("Location: {$page}");
        exit();
    }

    // main

    session_start();
    $user_storage = new UserStorage();
    $auth = new Auth($user_storage);
    $data = [];
    $errors = [];
    
    $username ="";
    $password ="";

    if ($_POST) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (validate($_POST, $data, $errors)) {
            $auth_user = $auth->authenticate($data['username'], $data['password']);
            
            if (!$auth_user) {
                $errors['global'] = "Username or password is incorrect!";
            } 
            else {
                
                $auth->login($auth_user);
                redirect('index.php');
            }
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/form.css">

    <style>
     
    </style>
</head>
<body>
    <header class="bg-blur p-3" >
        <h1><a href="index.php">IKémon</a> > Login </h1>
        <div style="float:right;">
            <a href="register.php">Register</a>
        </div>
    </header>
    <div class="container center">
        <div class="row">
            <div class="col-lg-6 d-none d-lg-block">
                <img src="https://assets.pokemon.com/assets/cms2/img/pokedex/full/009.png" alt="Blastoise">
            </div>
            <div class="col-11 mx-auto col-lg-6 mt-4 p-5 bg-blur text-white rounded">
                <form method="POST" novalidate>
                    <h1 class="mb-5">Enter your Login information</h1>
                    <span style="color:orange;">
                        <?=(isset($errors['global'])) ? $errors['global'] : "" ?>
                    </span>
                    <input type="text" name="username" placeholder="Enter username" class="w-100" value='<?= $username ?>'><br>
                    <input type="password" name="password" placeholder="Enter password" class="w-100" value='<?= $password ?>'><br>
                    <button type="submit" name="login" class="btn btn-light btn-lg w-100">Login</button>

                </form>
            </div>
        </div>
    </div>
</body>
</html>