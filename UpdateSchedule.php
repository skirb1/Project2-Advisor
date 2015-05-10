<?php
include 'init.php';
include 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
    $advisorID = $_SESSION['advisorID'];
    if(count($_POST) > 1){
      $submit = array_pop($_POST);
      foreach($_POST as $name => $value ){
            $name = explode("_", $name, 2);
            $date = $name[0];
            $time = db_time($name[1]);
          
          //check to see if date is already in table
          $sql = "SELECT COUNT(*) FROM Individual_Schedule WHERE ";
          $sql .= "advisorID = '$advisorID' AND date = '$date'";
          $record = $COMMON->executeQuery($sql, $_SERVER["UpdateSchedule.php"]);
          //if record doesnt exist, add new row
          if(mysql_result($record, 0) == 0){
              $sql = "INSERT INTO `Individual_Schedule` (`advisorID`, `date` )";
              $sql .= "VALUES ('$advisorID', '$date');";
              $record = $COMMON->executeQuery($sql, $_SERVER["UpdateSchedule.php"]);
          }
          //then update row
          $sql = "UPDATE Individual_Schedule SET `$time` = '$value'";
          $sql .= " WHERE `advisorID` = '$advisorID' AND `date` = '$date'";
          $record = $COMMON->executeQuery($sql, $_SERVER["UpdateSchedule.php"]);
          if($record === false){
            echo "Error updating table<br>";
          }
          else {
              if(update_group($advisorID, $date, $time)){
               header('Location: index.php');
              }
          }
      }//end foreach
    }
}//end if(logged in)
else {
  echo "<br>You are not logged in";
}
include 'includes/overallfooter.php';
?>