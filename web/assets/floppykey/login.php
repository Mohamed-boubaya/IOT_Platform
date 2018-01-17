<?php
session_start();
error_reporting(0);
//FONCTIONS

$secret = "linux";

function crypt_g2foss($string, $secret)
{
    $secret = str_pad($secret, strlen($string), $secret);
    $pass = "";
    for ($i = 0; $i < strlen($string); $i++)
        $pass .= chr((((ord($string[$i]) + (ord($secret[$i]) - ord('a'))) - ord('a')) % 26) + ord('a'));
    return strrev($pass);
}

function decrypt_g2foss($string, $secret)
{
    $string = strrev($string);
    $secret = str_pad($secret, strlen($string), $secret);
    $pass = "";
    for ($i = 0; $i < strlen($string); $i++)
        $pass .= chr((((ord($string[$i]) - (ord($secret[$i]) - ord('a')) + 26) - ord('a')) % 26) + ord('a'));
    return $pass;
}

//FIN FONCTIONS

$verif = true;
if (!empty($_GET) ||  !empty($_POST)) {
$username="";
$password=""; 
$submit = "Login";


if(isset($_POST['username'])){
$username = $_POST['username'];
}
if(isset($_POST['password'])){
$password = $_POST['password'];
}
if(isset($_POST['submit'])){
$submit = $_POST['submit'];
}


if(isset($_GET['username'])){
$username = $_GET['username'];
}
if(isset($_GET['password'])){
$password = $_GET['password'];
}
if(isset($_GET['submit'])){
$submit = $_GET['submit'];
}

    $verif = $verif && preg_match('/^.{1,100}$/', $username);
    $verif = $verif && preg_match('/^[a-z]{5,100}$/', $password);

   if($submit == 'Login') {
        $pdo = new PDO('mysql://host=localhost;dbname=gfossien_ctf_task_sql', 'gfossien_ctf_10', 'z%]e]$@7d]QK');
        $req = $pdo->query("SELECT * FROM `user` WHERE username = '" . $username . "' AND password = '" . crypt_g2foss($password, $secret) . "'");
        $user = $req->fetch();
        if (!$user) {
            $message = "The username or password don't match !";
        } else {
            $_SESSION['ctf_task_10_user_id'] = $user['id'];
            header('location:index.php');
        }
    }
    
     //    CONNEXION
     
    elseif (!$verif) {
        $message = "The username and password are not valids";
    }
     //    INSCRIPTION

    elseif ($submit == 'Register') {
        $pdo = new PDO('mysql://host=localhost;dbname=gfossien_ctf_task_sql', 'gfossien_ctf_11', 'DIz),Fy9Acz@');
        $req = $pdo->prepare('SELECT * FROM user WHERE username = ?');
        $req->execute(array($username));
        $user = $req->fetch();
        if (!$user) {
            $req = $pdo->prepare('INSERT INTO `user`(`username`, `password`, `createdAt`) VALUES (?,?, NOW())');
            $req->execute(array($username, crypt_g2foss($password, $secret)));
            $message = "Account created !";
            $success = true;
        } else {
            $message = "The username \"" . htmlspecialchars($username) . "\" is already used !";
        }
    }
}

?>


<!DOCTYPE html><html><head> <meta charset="utf-8"> <link rel="stylesheet" href="http://ctf.g2foss.org/css/app.css"> <title>Login | CTFOSS</title> <link rel="icon" type="image/png" href="http://ctf.g2foss.org/img/favicon.png"/> <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'></head><body><header class="header header_colored header_flat shaddow"> <div class="header_title"><a href="#"> <img src="http://ctf.g2foss.org/img/favicon.png" alt="" class="header_title_img"> <h1 class="header_title_text">Capture the <span class="typed colored">FOSS</span></h1></a></div></header><div class="container containerLogin" align="center"> <?php if (isset($message)): ?> <p> <?php if(isset($success)) echo '<div style="width: 80%; margin: 10px; padding: 10px; text-align: center; border-radius: 3px; border: 1px solid green; background-color: #338064; color: rgba(255,255,255,0.6);">' .$message.'</div>'; else echo '<div style="width: 80%; margin: 10px; padding: 10px; text-align: center; border-radius: 3px; border: 1px solid red; background-color: #ff859b; color: rgba(255,255,255,0.6);">'.$message.'</div>'; ?> </p><?php endif; ?> <div class="sign_in_container"> <div class="sign_in_menu row"><a href="#" class="sign_in_menu_item sign_in_menu_item_selected l12 m12 s12"> Log In </a> </div><form action="" method="post"><p class="sign_in_form_item"> <input type="text" class="sign_in_form_item_input input" name="username" value="" placeholder="Team Name"/></p><p class="sign_in_form_item"><input type="password" name="password" placeholder="Password" class="sign_in_form_item_input input"/></p><input type="submit" name="submit" value="Login" class="button button-sm sign_in_action"/></form> </div><div class="sign_in_container" style="margin-left: 50px;"> <div class="sign_in_menu row"><a href="#" class="sign_in_menu_item sign_in_menu_item_selected l12 m12 s12">Sign Up </a></div><form action="" method="post"><p class="sign_in_form_item"> <input type="text" class="sign_in_form_item_input input" name="username" value="" required pattern=".{1,100}" placeholder="Team Name"/></p><p class="sign_in_form_item"><input type="password" name="password" required pattern="[a-z]{5,100}" placeholder="Password" class="sign_in_form_item_input input"/></p><input type="submit" name="submit" value="Register" class="button button-sm sign_in_action"/></form> </div></div><footer class="footer row"> <div class="footer_title l4 m4 s12"><a href="#"> <img src="http://ctf.g2foss.org/img/favicon.png" alt="" class="footer_title_img"> <h1 class="footer_title_text">Capture the <span class="colored">FOSS</span></h1></a> <p class="footer_title_copyright">© 2015 <a href="http://www.g2foss.org" target="_blank">G²FOSS ENIT</a></p></div><div class="footer_g2foss l4 m4 s12"><a href="#" target="_blank"> <img src="http://ctf.g2foss.org/img/g2foss.png" alt="" class="footer_g2foss_img"> </a> <h1 class="footer_g2foss_text">Made by <a href="#" target="_blank" class="coloredG2foss">G²FOSS ENIT</a></h1> </div></footer></body></html>