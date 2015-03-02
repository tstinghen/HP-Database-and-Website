<!DOCTYPE html> 
<html> 
<head> 
<meta charset="utf-8"/>
<title>Wizard Database</title> 
<link rel="stylesheet" type="text/css" href="/~stinghet/dbase.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#roster").validate();
	}); 
</script>
</head> 
<body> 
<?PHP
//ini_set('display_errors', 'On'); 
$dbhost = 'oniddb.cws.oregonstate.edu';
$dbname = 'stinghet-db';
$dbuser = 'stinghet-db';
$dbpass = 'XB6i0GgEjGn6lKU8';

$mysql_handle = mysql_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Error connecting to database server");

mysql_select_db($dbname, $mysql_handle)
    or die("Error selecting database: $dbname");

?>

<h2>Wizard Database</h2><br>
<div class="button"><a href='http://web.engr.oregonstate.edu/~stinghet/hpdata/dataHub.php'>Go Back</a></div>
<h4>Display Membership Information</h4>

<fieldset>
<br/>
<form method="post" id = "roster" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
Select a house or organiztion: <br/>
*Choosing both an organization and a house will result in seeing only members belonging to that house.*<br/><br/>

<label>House: <select name = "house">
<option id="house" name="house" value="None">-Select House-</option> 
<?php
	$orgQuery = mysql_query("SELECT house_name FROM house"); 

WHILE ($rows = mysql_fetch_array($orgQuery)) : 

	$house = $rows['house_name'];

	
	echo "<option id='house' name='house' value='$house'>$house</option> "; 
	
endwhile; 

?>

</select>
</label>
<br/><br/>
<label>Organization: <select name = "org">
<option id="org" name="org" value="None">-Select Organization-</option> 
<? //populates organizations into the form from the database
	$orgQuery = mysql_query("SELECT org_name FROM organization"); 

WHILE ($rows = mysql_fetch_array($orgQuery)) : 

	$org = $rows['org_name'];

	
	echo "<option id='org' name='org' value='$org'>$org</option> "; 
	
endwhile; 

?>
</select>
</label>
</fieldset> 
<br/>
<input type="submit" value ="Display"> <br/>
</form>


<?php


$house = test_input($_POST["house"]);
$org = test_input($_POST["org"]);


function test_input($data) {
	$data = trim($data); 
	$data = stripslashes($data); 
	$data = htmlspecialchars($data); 
	return $data; 
}



if ($_POST)
{
//if the user only selected an organization, get membership info for that organization
if($house == "None" && $org != "None")
{
$charQuery = mysql_query("SELECT f_name, l_name, title, house_name
FROM hp_character 
INNER JOIN char_org_role ON char_org_role.char_id = hp_character.char_id
INNER JOIN role  ON role.role_id = char_org_role.role_id 
INNER JOIN organization  ON organization.org_id = char_org_role.org_id 
INNER JOIN house on house.house_id = hp_character.house_id
WHERE org_name = '$org' ORDER BY title; "); 

echo "$org : <br/>";


WHILE ($rows = mysql_fetch_array($charQuery)) : 
	$first = $rows['f_name'] ;
	$last = $rows['l_name'];
	$title = $rows['title'];
	$house = $rows['house_name'];

	
	echo "$first $last of $house: $title <br/>"; 
	
endwhile; 
}

else if($house != "None" && $org != "None")
{
//if the user selected both a house and an organization, show members from that house
$charQuery = mysql_query("SELECT f_name, l_name, title, house_name
FROM hp_character 
INNER JOIN char_org_role ON char_org_role.char_id = hp_character.char_id
INNER JOIN role  ON role.role_id = char_org_role.role_id 
INNER JOIN organization  ON organization.org_id = char_org_role.org_id 
INNER JOIN house on house.house_id = hp_character.house_id
WHERE org_name = '$org' AND house_name = '$house' ORDER BY title; "); 

echo "$org from $house : <br/>";


WHILE ($rows = mysql_fetch_array($charQuery)) : 
	$first = $rows['f_name'] ;
	$last = $rows['l_name'];
	$title = $rows['title'];
	$house = $rows['house_name'];

	
	echo "$first $last: $title <br/>"; 
	
endwhile; 
}

else if($house != "None" && $org == "None")
{//if just a house is selected, print all characters in that house
$charQuery = mysql_query("SELECT f_name, l_name FROM hp_character 
INNER JOIN house ON house.house_id = hp_character.house_id
WHERE house_name = '$house';"); 

echo "Characters from $house : <br/>";


WHILE ($rows = mysql_fetch_array($charQuery)) : 
	$first = $rows['f_name'] ;
	$last = $rows['l_name'];

	echo "$first $last<br/>"; 
	
endwhile; 
}




$mysqli_close($mysql_handle);

}




?> 



</body>


</html>
