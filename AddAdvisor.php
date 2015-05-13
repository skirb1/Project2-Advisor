<?php
include 'init.php';
include 'includes/overallheader.php';
?>
<h2>Add Advisor</h2>
<?php

    if(array_key_exists('submitAdvisor', $_POST) &&
       $_POST['firstName'] != NULL &&
       $_POST['lastName'] != NULL &&
       $_POST['email'] != NULL &&
       $_POST['room'] != NULL &&
       $_POST['phone'] != NULL ){

        if(strpos($_POST['email'], "@umbc.edu") !== false){
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $room = $_POST['room'];
            $phone = $_POST['phone'];
            $userID;
            $advisorID;
            $advisorResult = true;

            $sql = "INSERT INTO Advisors (`advisorID`, `firstName`,";
            $sql .= " `lastName`, `email`, `room`, `phone`) Values ( NULL, ";
            $sql .= "'$firstName', '$lastName', '$email', '$room', '$phone');";
            $result = $COMMON->executeQuery($sql, $_SERVER["AddAdvisor.php"]);
            if($result === false){
                echo "<div id=\"error\">Error adding to Advisors</div>";
                $advisorResult = false;
            }

            if($advisorResult === true){
                echo "<div id=\"error\">Advisor added successfully</div>";
            }
            else {
                echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
                echo "Error: Advisor not added</div>";
            }

      }//end if(email valid)
      else {
	       echo "<div id=\"error\"><img src=\"includes/error.png\" id=\"errorImg\">";
            echo "Email must contain '@umbc.edu'</div>";
      }
}//end if(all fields are entered)
else{
?>
<div id="list">
<form action="AddAdvisor.php" method="post">
<table id="advisorFormTable">
    <tr>
        <td>First Name:</td>
        <td><input type="text" name="firstName" minlength ="2" maxlength="15"></td>
    </tr><tr>
        <td>Last Name:</td>
        <td><input type="text" name="lastName" minlength="2" maxlength="15"></td>
    </tr><tr>
        <td>UMBC Email Address:</td>
        <td><input type="text" name="email" ></td>
    </tr><tr>
        <td>Room Number:</td>
        <td><input type="int" name="room" minlength="3" maxlength="4"></td>    
    </tr><tr>
        <td>Phone Number:</td>
        <td><input type="text" name="phone" min="0" max="9" maxlength="10"></td>
    </tr><tr>
        <td></td><td><input type="submit" name="submitAdvisor"></td>
<?php
    if (array_key_exists('submitAdvisor', $_POST)){
        echo "<td><img src=\"includes/error.png\" id=\"errorImg\">";
        echo "Please enter all fields</td>";
    }
    ?>
</tr>
</table>
</form>
<?php

    echo "</div>";
}
include 'includes/overallfooter.php';
?>