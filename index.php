<?php
session_start();
if(!empty($_SESSION['id']))
{
    header("Location: home.php");
}
require('includes/mylibrary.php');
require('config/connection.inc.php');
require('config/application.php');
$app = new User();
$register_error_message = '';
if (isset($_POST['btnsignup'])) { 
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $register_error_message = 'Invalid email address!';
    } else if ($app->isEmail($_POST['email'],$pdo)) {
        $register_error_message = 'Email is already in use!';
    } else if ($app->isUsername($_POST['username'],$pdo)) {
        $register_error_message = 'Username is already in use!';
    } else if (!$app->checkpassword($_POST['pwd']))
    {
        $register_error_message = "your password are weak ,  <= 8 charachter , at leaste one number , at least one lettre";
    } else if((($_POST['captcha']!=$_SESSION['code'] && !empty($_SESSION['code']))|| !isset($_POST['captcha']))){
    } else if (!$app->checkUsername($_POST['username']))
    {
        $register_error_message = "your username not validate ,  <= 5 charachter , at least one lettre";
    } else if((($_POST['captcha']!=$_SESSION['code'] && !empty($_SESSION['code']))|| !isset($_POST['captcha']))){
        $register_error_message = "Captcha incorrect";
    }
    else {
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $verificationCode = str_shuffle($permitted_chars);
            $destinataire = $_POST['email'];
            $sujet = "Activer votre compte" ;
            $entete = "From: mdichkou@camagru.com" ;

            $message = 'Bienvenue sur Camagru,
             
            Pour activer votre compte, veuillez cliquer sur le lien ci dessous
            ou copier/coller dans votre navigateur internet.
             
            '.$APP_URL.'/activation.php?log='.urlencode($_POST['username']).'&cle='.urlencode($verificationCode).'
             
             
            ---------------
            Ceci est un mail automatique, Merci de ne pas y répondre.';
            if(mail($destinataire, $sujet, $message, $entete))
            {
                $userid = $app->Registre($_POST['username'], $_POST['pwd'], $_POST['email'], $_POST['name'], $verificationCode,$pdo);
                $_SESSION = $app->find_user($userid,$pdo);
                header("Location: includes/login.inc.php");
        }
        else{
            echo 'false';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/camagru.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php include 'includes/header.inc.php';?>
<main class="container">
<div class="row signup_row">
    <div class="bg col-sm-4 col-sm-offset-4">
            <p class="txt_signup">
                <b>Sign up</b> to see photos <br> from your friends
            </p>
            <?php
            if ($register_error_message != "") {
                echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $register_error_message . '</div>';
            }
            ?>
            <form action="index.php" method="post">
                <input type="text" name="email" placeholder=" Email" required  class="btn_max form-control"><br>
                <input type="text" name="name" placeholder="Full Name" required class="btn_max form-control"><br>
                <input type="text" name="username" placeholder="Username" required class="btn_max form-control"><br>
                <input type="password" name="pwd" placeholder="Password" required class="btn_max form-control"><br>
                <input type="text" required  name="captcha"/>
                <img src="image.php" onclick="this.src='image.php?' + Math.random();" alt="captcha" style="cursor:pointer;">
                <button type="submit" name="btnsignup" style="margin-top: 10px;"class="btn center-block">Sign up</button><br>
            </form>
    </div>
</div>
</main>
<?php include 'footer.php';?>
<body>
</html>
