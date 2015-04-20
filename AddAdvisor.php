<?php
include 'init.php';
include 'includes/overallheader.php';
?>
<h2>Add Advisor</h2>
<?php

    if(array_key_exists('submitAdvisor', $_POST) && 
       array_key_exists('firstName', $_POST) &&
       array_key_exists('lastName', $_POST) &&
       array_key_exists('email', $_POST) &&
       array_key_exists('room', $_POST) ){

      if(strpos($_POST['email'], "@") !== false){
	$emailArray = explode('@', $_POST['email'], 2);
	$username = $emailArray[0];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$room = $_POST['room'];
	$userID;
	$advisorID;
	$advisorResult = true;

	  $sql = "INSERT INTO Advisors (`advisorID`, `userID`, `firstName`,";
	  $sql .= " `lastName`, `email`, `room`) Values ( NULL, '$userID', ";
	  $sql .= "'$firstName', '$lastName', '$email', '$room' );";
	  $result = $COMMON->executeQuery($sql, $_SERVER["AddAdvisor.php"]);
	  if($result === false){
	    echo "<div id=\"error\">Error adding to Advisors</div>";
	    $advisorResult = false;
	  }

	//Get advisorID
        $sql = "SELECT * FROM Advisors WHERE `userID` = '$userID'";
        $advisor = $COMMON->executeQuery($sql, $_SERVER["AddAdvisor.php"]);
        if($advisor !== false && mysql_num_rows($advisor) == 1){
          $advisorID = mysql_result($advisor, 0, 'advisorID');
          if($result === false){
            echo "<div id=\"error\">Error accessing Advisors</div>";
	    $advisorResult = false;
          }
        }

	if($advisorResult === true){
	  echo "Advisor added successfully<br>";
	}

      }//end if(email valid)
      else {
	echo "<div id=\"error\">Email must contain '@umbc.edu'</div>";
      }
    }//end if(all fields are entered)
    else if (array_key_exists('submitAdvisor', $_POST)){
      echo "<div id=\"error\">Missing fields</div>";
    }
else{
?>
<div id="list">
<form action="AddAdvisor.php" method="post">
<table id="advisorFormTable">
    <tr>
        <td>First Name:</td>
        <td><input type="text" name="firstName"></td>
    </tr><tr>
        <td>Last Name:</td>
        <td><input type="text" name="lastName"></td>
    </tr><tr>
        <td>UMBC Email Address:</td>
        <td><input type="text" name="email"></td>
    </tr><tr>
        <td>Room Number:</td>
        <td><input type="int" name="room"></td>    
    </tr><tr>
        <td>Phone Number:</td>
        <td><input type="text" name="phone"></td>
    </tr><tr>
        <td>Majors (select all that apply):</td>
        <td><input type="checkbox" name="major"></td>
    </tr><tr>
        <td></td><td><input type="submit" name="submitAdvisor"></td>
    </tr>
</table>
</form></div>
<?php
}
include 'includes/overallfooter.php';
?>