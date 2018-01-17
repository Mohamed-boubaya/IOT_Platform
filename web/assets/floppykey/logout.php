<?php
session_start();
if(isset($_SESSION['ctf_task_10_user_id'])){
    unset($_SESSION['ctf_task_10_user_id']);
}
header('location:login.php');