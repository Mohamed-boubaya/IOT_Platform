<?php
session_start();
if(!isset($_SESSION['ctf_task_10_user_id'])){
    header('location:login.php');
}

$pdo = new PDO('mysql://host=localhost;dbname=gfossien_ctf_task_sql', 'gfossien_ctf_11', 'DIz),Fy9Acz@');
$req = $pdo->query("SELECT * FROM `user` WHERE id = ".$_SESSION['ctf_task_10_user_id']);
$user = $req->fetch();
if(!$user){
    header('location:logout.php');
}

?>
<!DOCTYPE html><html><head> <meta charset="utf-8"> <title>Login | Capture The FOSS | <?php echo strrev($user['password']); ?> : drowssap</title> <link rel="icon" type="image/png" href="http://ctf.g2foss.org/img/favicon.png"/></head><body><h1>Welcome <?php echo htmlspecialchars($user['username']); ?></h1><a href="logout.php">Logout</a></body></html>