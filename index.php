<?php
include_once 'init.php';
include_once 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
    include 'includes/widgets/logout.php';
    echo "<h2>Your Schedule</h2>";
    echo "<br>Display schedule here";

}
else if(array_key_exists('advisor', $_POST)){
    if($_POST['advisor'] == 'add'){
        header('Location: AddAdvisor.php');
        //include form to add advisor
        //send back to index to choose advisor
    }
    else{
        $_SESSION['advisorID'] = $_POST['advisor'];
        header('Location: index.php');
    }
}
else {
    include 'SelectAdvisorForm.php';
}

include_once 'includes/overallfooter.php';
?>