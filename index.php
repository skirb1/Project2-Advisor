<?php
include_once 'init.php';
include_once 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
    echo "<h2>Your Schedule</h2>";
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
    echo "Please select an advisor<br>";
    include 'SelectAdvisorForm.php';
}

/*if(logged_in() === true){
  $userType = userType_from_userID($_SESSION['userID']);
  if($userType == "Student"){}
  else if ($userType == "Advisor"){
    echo "<h2>Your Schedule</h2>";
    echo "<form id=\"weekForm\"";
    echo " action=\"index.php\" method=\"post\">";
    include 'includes/selectWeek.php';
    echo "</form>";
    display_schedule(advisorID_from_userID($_SESSION['userID']));
  }
  else if ($userType == "Admin"){
    echo "<br><div id=\"error\">Welcome, Admin.</div>";
  }
}
*/
include_once 'includes/overallfooter.php';
?>