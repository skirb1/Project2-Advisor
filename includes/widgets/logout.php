<div class="widget">
<p>Logged in as 
<?php
   if(logged_in() === true){
     echo username_from_userID($_SESSION['userID']);
   }
?>
</p>
  <div class="inner">
  <input type="button" value="Log Out" onclick="parent.location='logout.php'">
  </div>
</div>
