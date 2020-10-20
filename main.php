<html>
<head>
	<title>SQL passenger_food</title>
<link rel="stylesheet" href="./main.css">
<link rel="icon" href="./logo.png">
<script src="jquery-3.5.1.min.js"></script>
<!-- upper line can be replaced with this: <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

</head>

<body>

<script>
	//mode: 0-native, 1-query results, 2-updates
	var mode=0;
	function change_to_updated_mode(){
		mode=2;
	}
	function change_to_data_mode(){
		mode=1;
	}
    $(function() {
		$(".table-title").click(function(){
			$(this).next().fadeToggle("slow");
		});


		//header line th
		$(".table-header").click(function(){
		 	//$(this).next().fadeToggle("slow");
			 recursive_Toggle($(this).next());
		});

		function recursive_Toggle(_this)
		{
			if(_this.length>0)
			{
				setTimeout(()=>recursive_Toggle(_this.next()),20);
				// recursive_Toggle(_this.next());
				_this.slideToggle("fast");
			}
		}


		$(".table-select").click(function()
		{
			let val=$(this).val();
			if(val=="view all tables")
			{
				if(mode==1)
				{
					window.location.replace("");
				}

				else
				{
				$(".div-table").each(
					function(){
						$(this).show();
					})

				$(".table-title").each(
					function(){
						$(this).next().fadeIn("slow");
					})

				$(".table-row").each(
					function(){
						$(this).next().slideDown("slow");
					})

				}
			}
			else
			{
				if (mode==0||mode==2)
				{
					$(".div-table").each(function(){
							$(this).hide();
						}
					)
					$("#"+val).show();
				}
			}

		}
		);
    });

</script>


<?php
main();

function draw_table($title,$arr)
{
		if (strlen($title)>0){
			echo "<div class=\"div-table\" id=$title><b class=\"table-title\">Table name: $title</b>";
		}
		echo "<table class=\"styled-table\"><tr class=\"table-header\">";
		foreach ($arr[0] as $key=>$value) {
			echo "<th>$key</th>";
		}
		echo "</tr>";
	foreach ($arr as $row)
	{
		echo "<tr class=\"table-row\">";
			foreach ($row as $key=>$value) {
				echo "<td>$value</td>";
			}
			echo "</tr>";
	}

	echo "</table></div>";
	}

	function main(){

	//connection data
	$servername="localhost";
	$username="root";
	$password="";
	$dbname="passenger_food";

	//create new sql connection to mysql local server
	$conn=new mysqli($servername,$username,$password,$dbname);
	if($conn->connect_error)
	{
		print("<br>connection failed: $conn->connect_error <br>");
	}
	else{
		//create query textbox and button
		echo '<form method="post" action="">
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
			$rows=$res->fetch_all();
			echo "<table class=\"navbar\"><tr value=\"15\">";
			echo "<td><input class=\"table-select\" type=\"button\" value=\"view all tables\"></input></td>";
			foreach ($rows as $row)
			{
				foreach ($row as $value) {
					$tables[]=$value;
					echo "<td><input class=\"table-select\" type=\"button\" value=$value></input></td>";
				}
			}

			echo "</table><br>";
			echo "<br><br>";
		}
		else
		{
			print("error");
		}


		//if query appears, ignore table and show results of the query
		$show_limited=false;
		if (array_key_exists("query",$_POST))
		{
			$sql=$_POST["query"];
			if (strlen($sql)<1)
			{
				echo '<b class="alert-text">
				Query must be longer than 1</b>';
				echo "<br><br><br><br>";
			}
			else{
			$res=$conn->query($sql);
			if($res instanceof mysqli_result)
			{
				echo "<script>change_to_data_mode();</script>";
				//show only the results of the 'select' query
				$show_limited=true;
				if($res instanceof mysqli_result and $res->num_rows > 0)
				{
					echo "<b class=\"alert-text\">Query results: $sql</b><br>";
				//remember to print the first line
					$rows=$res->fetch_all(MYSQLI_ASSOC);
					draw_table("",$rows);
				}

				else
				{
					print("nothin' to see here");
				}
				echo "<br>";
			}
			elseif($res){
				echo "<script>change_to_updated_mode();</script>";
				echo "<b class=\"alert-text\">
				Tables were updated according to query: $sql</b>";
				echo "<br><br><br><br>";
			}
			else{
				echo "<b class=\"alert-text\">
				Tables were not updated, error with query: $sql</b>";
				echo "<br><br><br><br>";
			}
		}
	}

	if(!$show_limited)
		{
		$show_tables=$tables;

		foreach ($show_tables as $key => $table) {

			$sql="SELECT * FROM `$table`";
			$res=$conn->query($sql);
			$col_names=array();

			if($res instanceof mysqli_result and $res->num_rows > 0)
			{
				$rows=$res->fetch_all(MYSQLI_ASSOC);
				draw_table($table,$rows);

			}
			else{
				print("nothin' ain't here");
			}
			echo "<br>";
		}
	}
	}
	$conn->close();
}
?>
</body>
</html>