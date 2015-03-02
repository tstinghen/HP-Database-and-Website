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
		$("#disp_character").validate();
	}); 
</script>
</head> 
<body> 

<h2>Wizard Database</h2><br>
<div class="button"><a href='http://web.engr.oregonstate.edu/~stinghet/hpdata/dataHub.php'>Go Back</a></div>
<h4>Display Character Information</h4><br>

<fieldset>
<br/>
<form method="post" id = "disp_character" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<label>First Name:  <input class = "required"  type="text" id="fname" name="fname"> </label> <br/><br/>
<label>Last Name:    <input class = "required"  type="text" id="lname" name="lname"></label> <br/><br/>
</select>
</fieldset> 
<br/>
<input type="submit" value ="Display"> <br/>
</form>

<?php


//ini_set('display_errors', 'On'); 
$dbhost = 'oniddb.cws.oregonstate.edu';
$dbname = 'stinghet-db';
$dbuser = 'stinghet-db';
$dbpass = 'XB6i0GgEjGn6lKU8';

$mysql_handle = mysql_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Error connecting to database server");

mysql_select_db($dbname, $mysql_handle)
    or die("Error selecting database: $dbname");


$fname = test_input($_POST["fname"]);
$lname = test_input($_POST["lname"]);


function test_input($data) {
	$data = trim($data); 
	$data = stripslashes($data); 
	$data = htmlspecialchars($data); 
	return $data; 
}



if ($_POST)
{
//select a character and print their information
$charQuery = mysql_query("SELECT f_name, l_name, blood_stat, house_name, wood, core, length
FROM `hp_character` 
INNER JOIN house ON house.house_id = hp_character.house_id
INNER JOIN wand ON wand.char_id = hp_character.char_id 
WHERE f_name = '$fname'"); 
echo "<br><table>";
$counter = 0; 
WHILE ($rows = mysql_fetch_array($charQuery)) : 
	$first = $rows['f_name'] ;
	$last = $rows['l_name'];
	$blood = $rows['blood_stat'];
	$house = $rows['house_name'];
	$wood = $rows['wood'];
	$core = $rows['core'];
	$length = $rows['length'];
	
	echo "Name: $first $last, $blood <br/>Hogwarts House: $house<br/>Wand: $wood, $core,
	$length inches. <br/>"; 
	
endwhile; 
//select all the groups where that character is a member, and print the membership information
$charQuery = mysql_query("SELECT title, org_name, purpose 
FROM `hp_character` 
INNER JOIN char_org_role ON char_org_role.char_id = hp_character.char_id
INNER JOIN role ON role.role_id = char_org_role.role_id 
INNER JOIN organization ON organization.org_id = char_org_role.org_id 
WHERE f_name = '$fname'"); 
echo "<br><table>";
WHILE ($rows = mysql_fetch_array($charQuery)) : 

	$title = $rows['title'];
	$org_name = $rows['org_name'];
	$purpose = $rows['purpose'];
	
	echo "$title of $org_name <br/>Purpose: $purpose<br/>"; 
	
endwhile; 

echo "</table><br>";

$mysqli_close($mysql_handle);

}




?> 




</body>


</html>
