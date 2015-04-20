<?php
include 'init.php';
include 'includes/overallheader.php';

if(logged_in() === true ){
  if(userType_from_userID($_SESSION['userID']) == "Advisor"){
    $advisorID = advisorID_from_userID($_SESSION['userID']);
    if(count($_POST) > 1){
      $submit = array_pop($_POST);
      foreach($_POST as $name => $value ){
	echo $name;
	$name = explode("-", $name, 2);
	$date = $name[0];
	$time = $name[1];
	$sql = "UPDATE `$date` SET `$time` = '$value' WHERE `advisorID` = '$advisorID'";
	$record = $COMMON->executeQuery($sql, $_SERVER["user.php"]);
	if($record === false){
	  echo "Error updating indiv table<br>";
	}
	else {
	  if(update_group_tables($advisorID, $date, $time)){
	    header('Location: index.php');
	  }
	}
      }//end foreach
    }
  }
}//end if(logged in)
else {
  echo "<br>You are not logged in";
}
include 'includes/overallfooter.php';
?>