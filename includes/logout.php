<aside>
<p>Logged in as
<?php
   if(array_key_exists('advisorID', $_SESSION)){
     echo name_from_advisorID($_SESSION['advisorID']);
   }
?>
</p>
  <div class="inner">
  <input type="button" value="Log Out" onclick="parent.location='logout.php'">
  </div>
</aside>