<?php
include 'init.php';
include 'includes/overallheader.php';
?>
<h2>Advisors</h2>
<?php
advisor_list();
if(logged_in() === true){
  if(userType_from_userID($_SESSION['userID']) == "Admin"){
?>
<div id="list">
<form action="AddAdvisor.php" method="post">
Add new advisor: <input type="submit" name="submit">
</form></div>
<?php
}
}

include 'includes/overallfooter.php';
?>
