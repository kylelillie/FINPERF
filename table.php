<?php

if (isset($_POST['data'])){
	
	$datasets = $_POST['data'];
	$provinces = $_POST['prov'];
	$industries = $_POST['ind'];
	$years = $_POST['years'];
	
	$group_prov = $_POST['gp'];
	$group_ind = $_POST['gi'];
	$group_prov_name = $_POST['gpn'];
	$group_ind_name = $_POST['gin'];

	$cols = ['Incorporation Status', 'NAICS', 'Province', 'Year ', 'Type', 'Size', 'Value', 'Bottom Quartile', 'Lower Middle', 'Upper Middle', 'Top Quartile','Category'];
	$matrix = [];

	if ($industries == "*") { 
		$industries_q = "";
	}	else {
	
		$industries_q = "NAICS IN (".$industries.")";
	}
	
	if ($provinces == "*") { 
		$provinces_q = "AND Province = 'Canada' ";
	}	else {
		
		$provinces_string = '';
		
		$provinces_q = "AND Province IN (".$provinces.")";
	}
	
	if ($datasets == "*") { 
		$datasets_q = "";
	}	else {
		$datasets_q = "AND Dataset IN (".$datasets.")";
	}

	if ($years == "*") { 
		$years_q = "";//AND 'Year ' IN (2015)";
	}	else {
		$years_q = "AND \"Year \" IN (".$years.")";
	}

	$sql_query = '';
	$sql_select = "SELECT *";
	$sql_from = " FROM finperf WHERE ".$industries_q." ".$provinces_q." ".$datasets_q." ".$years_q;
	$sql_order = " ORDER BY 'Year ',Province,NAICS,Size ASC";

	if ($group_prov){
		//$sql_select .= " SELECT SUM(Province)";
	}
	
	if ($group_ind){
		//$sql_select .= " SELECT SUM(NAICS)";
	}
	
	if ($group_prov & $group_ind){
		
	}
	
	$sql_query .= $sql_select.$sql_from.$sql_order;
	
	
	try{
		$myPDO = new PDO('sqlite:./data/finperf.db');
		$query = $myPDO->query($sql_query);
		
		while ($row = $query->fetch()){
			$matrix[]=$row;
		}
		
		$table_data = json_encode($matrix);
		
		echo $table_data;
	}

	catch(PDOException $e) {
		echo $e->getMessage();
	}
}

else{
	echo "No data passed to server";
}
?>