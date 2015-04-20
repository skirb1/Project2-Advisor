<form id="SelectAdvisorForm" action="index.php" method="post">
<div id="selectWeek">Select Advisor:</div>
<select name="advisor" id="advisor">
<?php

    $sql = "SELECT * FROM Advisors";
    $record = $COMMON->executeQuery($sql, $_SERVER["Advisor.php"]);
    while($advisor = mysql_fetch_row($record))
    {
        $advisorID = $advisor[0];
        $advisorName = $advisor[1]." ".$advisor[2];
        echo "<option value=\"".$advisorID."\">".$advisorName."</option>";
    }
    echo "<option value=\"".'add'."\">"."Add New Advisor"."</option>";

?>
</select>
<input type="submit" name="Select Advisor">
