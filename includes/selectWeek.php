<div id="selectTitle">Select week:</div>
<select name="week" id="week">
<?php
   for($i = 0; $i < count($CALENDAR->weeks); $i++){
     $first = $CALENDAR->weeks[$i]->dates[0];
     $last = $CALENDAR->weeks[$i]->dates[4];
     $weekString = short_string($first)." - ".short_string($last);
     echo "<option value=\"".$i."\">".$weekString."</option>";
   }
?>
</select>
<input type="submit" name="Select Week">