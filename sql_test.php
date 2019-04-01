<html>
<body>
<?php
require_once('connection.php');
if(isset($_POST["sql"]))
{
	echo $_POST["sql"]."<BR>";
	$result=pg_query ($db,$_POST["sql"]);	
	echo "erro: ".pg_result_error ($result)."<BR>";
	echo "<BR>output<BR>";
	while($row=pg_fetch_row($result))
	{
		for($i=0;$i<sizeof($row);$i++)
		{
			echo $row[$i]." | ";
			
		}
		
		echo "<BR>";
	}
}
?>




<FORM method='post' action='sql_test.php'>
<textarea name='sql' id='sql' rows="5" cols="100">
</textarea>
<br>
<input type='submit'>
</form> 

</body>
</html>
