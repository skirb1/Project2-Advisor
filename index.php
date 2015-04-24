<?php
include_once 'init.php';
include_once 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
    include 'includes/widgets/logout.php';
    echo "<h2>Your Schedule</h2>";
    ?>
<form id="weekForm" action="index.php" method="post">
<?php include 'includes/selectWeek.php'; ?>
</form>
<?php
    if(array_key_exists('week', $_POST)){
         display_week($_SESSION['advisorID'], (int)$_POST['week']);
    }

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