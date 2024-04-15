<?php
    include('Auth.php');
    include('userstorage.php');

    session_start();

    $user_storage = new UserStorage();
    $auth = new Auth($user_storage);
    $errorMessages = [];
    
    function redirect($page) {
        header("Location: {$page}");
        exit();
    }
    
    function validateUserName($username,$auth) {
        if (!(5 <= strlen($username) && strlen($username) < 16)) return "The username must be between 5 and 15 characters long!";
        if ($auth->user_exists($username)) return "User already exists";
        return true;
    }

    function validatePassword($password) {
        if (!(8 <= strlen($password) && strlen($password) < 16)) return "The password must be between 5 and 15 characters long!";
        //if (str_contains($_POST["username"], $password)) return "A jelszó nem tartalmazhatja a felhasználónevet!";
        return true;
    }

    function validateEmailAddress($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Wrong email format!";
        return true;
    }

    $username = isset($_POST) && isset($_POST["username"]) ? $_POST["username"] : "";
    $email = isset($_POST) && isset($_POST["email"]) ? $_POST["email"] : "";
    $password = isset($_POST) && isset($_POST["password"]) ? $_POST["password"] : "";
    $password2 = isset($_POST) && isset($_POST["password2"]) ? $_POST["password2"] : "";

    
    if (count($_POST) > 0) {
        
        if($_POST['password'] !== $_POST['password2']) $errorMessages["match"] = "Passwords are not the same";
        if (($result = validateUserName($_POST["username"],$auth)) !== true) $errorMessages["username"] = $result;
        if (($result = validatePassword($_POST["password"])) !== true) $errorMessages["password"] = $result;
        if (($result = validateEmailAddress($_POST["email"])) !== true) $errorMessages["email"] = $result;

        if(count($errorMessages) == 0) {
            $auth->register($_POST);
            $auth_user = $auth->authenticate($_POST['username'], $_POST['password']);
            $auth->login($auth_user);
            redirect('index.php');
        } 
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/form.css">
</head>
<body>


<header class="bg-blur p-3" >
        <h1><a href="index.php">IKémon</a> > Register </h1>
        <div style="float:right;">
            <a href="login.php">Login</a>
        </div>
    </header>
    <div class="container center">
        <div class="row">
            <div class="col-lg-6 d-none d-lg-block">
                <img src="https://assets.pokemon.com/assets/cms2/img/pokedex/full/018.png" alt="Pidgeot">
            </div>
            <div class="col-11 mx-auto col-lg-6 mt-4 p-5 bg-blur text-white rounded">
                <form method="POST" novalidate>
                    <h1>Enter your information to Register</h1>
                    <?php if (isset($errorMessages["username"])) echo "<span style=\"color: orange\">{$errorMessages["username"]}</span>" ?>
                    <input type="text" name="username" placeholder="Enter username" class="w-100" value="<?= $username ?>" required><br>

                    <?php if (isset($errorMessages["email"])) echo "<span style=\"color: orange\">{$errorMessages["email"]}</span>" ?>
                    <input type="email" name="email" placeholder="Enter email address" class="w-100" value="<?= $email ?>" required><br>

                    <?php if (isset($errorMessages["match"])) echo "<span style=\"color: orange\">{$errorMessages["match"]}</span>" ?>
                    <?php if (isset($errorMessages["password"])) echo "<span style=\"color: orange\">{$errorMessages["password"]}</span>" ?>
                    <input type="password" name="password" placeholder="Enter password" class="w-100" value="<?= $password ?>" required><br>

                    <input type="password" name="password2" placeholder="Enter password again" class="w-100" value="<?= $password2 ?>" required><br>

                    <button type="submit" name="login" class="btn btn-light btn-lg w-100">Register</button>

                </form>
            </div>
        </div>
    </div>
    
</body>
</html>