<?php
include_once 'init.php';
include_once 'includes/overallheader.php';

if(array_key_exists('advisorID', $_SESSION)){
  $advisorID = $_SESSION['advisorID'];
?>
<h2>Search Appointments</h2>
<?php
/*
Search appointments for a student ID
return date/time/student info if appt exists
print info (include 'includes/printButton.php')
*/
    
    

}
include_once 'includes/overallfooter.php';
?>