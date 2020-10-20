<html>
<head>
	<title>SQL passenger_food</title>
<link rel="stylesheet" href="./main.css">
<link rel="icon" href="./logo.png">

</head>

<body>
<?php

//connection data
$servername="localhost";
$username="root";
$password="";
$dbname="passenger_food";

//create new sql connection to mysql local server
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error)
{
	print("connection failed: ".$conn->connect_error);
}
else{
	//create query textbox and button
	echo '<form action="?query="+document.getelementbyid(query).text>
		<textarea type="text" placeholder="Enter your query here" id="query" name="query"></textarea><br><br>
		<input type="submit" value="See query results:" class="btn">
	</form>';
	//collect all tables names to view options
	$sql="show tables";
	$res=$conn->query($sql);
	$tables=array();
	if($res instanceof mysqli_result and $res->num_rows > 0)
	{
		//remember to print the first line
	 	$row=$res->fetch_assoc();
	 	echo "<table class=\"navbar\"><tr>";
		echo "<td><a href=\"?\">view all tables</a></td>";
		while($row)
		{
			//echo "<tr>";
			   foreach ($row as $key=>$value) {
				   $tables[]=$value;
				   echo "<td><a href=\"?table=$value\">$value</a></td>";
			   }
			   //echo "</tr>";
			$row=$res->fetch_assoc();
		}

		echo "</table><br>";
		echo '';

		echo "<br><br>";
	}
	else
	{
		print("error");
	}


//if query appears, ignore table and show results of the query
$show_limited=false;
if (array_key_exists("query",$_GET))
{
	$sql=$_GET["query"];
	if (strlen($sql)<1)
	{
		echo '<b class="alert-text">
		Query must be longer than 1</b>';
		echo "<br><br><br><br>";
	}
	else{
	$res=$conn->query($sql);
	
	if(substr(strtoupper($sql),0,6)==='SELECT')
	{
		//show only the results of the 'select' query
		$show_limited=true;
		if($res instanceof mysqli_result and $res->num_rows > 0)
		{
			echo "<b class=\"alert-text\">Query results:</b><br>";
		//remember to print the first line
			$row=$res->fetch_assoc();
			echo "<table class=\"styled-table\"><tr>";
			foreach ($row as $key=>$value) {
				echo "<th>$key</th>";
			}
			echo "</tr>";


			while($row)
			{
				echo "<tr>";
			foreach ($row as $key=>$value) {
				echo "<td>$value</td>";
			}
			echo "</tr>";
			$row=$res->fetch_assoc();
			}

			echo "</table>";
		}
		else
		{
			print("nothin' to see here");
		}
		echo "<br>";
	}
	else{
		echo "<b class=\"alert-text\">
		Tables were updated according to query: $sql</b>";
		echo "<br><br><br><br>";
	}
}
}

if(!$show_limited)
	{
		if(array_key_exists("table",$_GET)){
			$show_tables=array($_GET["table"]);
	}
		else{
			$show_tables=$tables;
	}

	foreach ($show_tables as $key => $table) {

		$sql="SELECT * FROM `$table`";
		$res=$conn->query($sql);
		$col_names=array();

		if($res instanceof mysqli_result and $res->num_rows > 0)
		{
			$row=$res->fetch_assoc();
			echo "<b class=\"table-title\">Table name: $table</b>";
			echo "<table class=\"styled-table\"><tr>";

			//first head row of column names
			foreach ($row as $key=>$value) {
				echo "<th>$key</th>";
				$col_names[]=$key;
			}
			echo "</tr>";
			//values rows
			while($row)
			{
				echo "<tr>";
				foreach ($row as $key=>$value) {
					echo "<td>$value</td>";
			}
			echo "</tr>";
			$row=$res->fetch_assoc();
			}
			// //add row template command
			// $row_com="insert into $table (";
			// //print another room for add-row option
			// echo "<tr>";
			// foreach ($col_names as $key=>$value) {
			// 	echo "<td><input size=10 id=$table\_$value type=\"txt\" placeholder=$value></td>";
			// 	$row_com=$row_com . $value .",";
			// }
			// $row_com=substr($row_com,0,-1).") values ";

			// echo "</tr>";

			// echo "<tr>";
			// $insert_values="(";
			// foreach ($col_names as $key=>$value) {
			// 	$insert_values=$insert_values."document.getelementbyid($table\_$value).text,";
			// }
			// $insert_values=substr($insert_values,0,-1).")";
			// echo "<td><form action=\"?query=\"$row_com $insert_values";
			// echo ">";
			// echo "<input value=\"Add row:\" type=\"submit\"></form></td>";
			// echo "</tr>";
			echo "</table>";
		}
		else{
			print("nothin' ain't here");
		}
		echo "<br>";
	}
}
}
$conn->close();

?>
</body>
</html>