<?php
require('includes/mylibrary.php');
require('config/connection.inc.php');
require('config/application.php');
$app = new User();
$register_error_user = "";
$message_send = "";
if (isset($_POST['btnsend'])) { 
if (!$app->isUsername($_POST['username'],$pdo))
    $register_error_user = 'Username not existe!';
else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $row = $stmt->fetch();
    $destinataire = $row['email'];
    $sujet = "Changer votre password" ;
    $entete = "From: mdichkou@camagru.com" ;

    $message = 'Forget Your Password,
 
Pour changer votre password, veuillez cliquer sur le lien ci dessous
ou copier/coller dans votre navigateur internet.
 
'.$APP_URL.'/resetpw.php?log='.urlencode($_POST['username']).'&cle='.urlencode($row['cle']).'
 
 
---------------
Ceci est un mail automatique, Merci de ne pas y répondre.';
if(mail($destinataire, $sujet, $message, $entete))
{
    $message_send = "A Reset password email were sent to Your email !";
}
}
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" type="text/css" media="screen" href="css/camagru.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php include 'includes/header.inc.php';?>
<main>
<div class="row signup_row">
    <div class="bg col-sm-4 col-sm-offset-4">
            <p class="txt_signup">
                <b>Write Your username</b>
            </p>
            <?php
            if ($register_error_user != "") {
                echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $register_error_user . '</div>';
            }
            if ($message_send != "")
                echo '<div class="alert alert-success"><strong>Success: </strong> ' . $message_send . '</div>';
            ?>
             <form  action="forgotpw.php" method="post">
                <input type="text" name="username" placeholder="Username" required class="btn_max form-control"><br>
                <button type="submit" name="btnsend" class="btn center-block">Send</button><br>
            </form>
    </div>
</div>
</main>
<?php include 'footer.php';?>
<body>
</html>
