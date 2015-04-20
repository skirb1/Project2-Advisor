<?php
include 'init.php';
include_once 'includes/overallheader.php';

if(empty($_POST) === false){

    
    $advisorID = $_POST['advisorID'];
}

else {

    $_SESSION['userID'] = $login;
    header('Location: index.php');
    exit();
}


include_once 'includes/overallfooter.php';
?>