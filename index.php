<!DOCTYPE html>
<html lang="en">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>FINPERF | Financial Performance Data</title>

<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
<link rel="stylesheet" href="css/bootstrap-table.css">
<link rel="stylesheet" href="css/bootstrap.css">
<link href="https://unpkg.com/bootstrap-table@1.13.2/dist/extensions/group-by-v2/bootstrap-table-group-by.css" rel="stylesheet">
<link href="https://unpkg.com/bootstrap-table/dist/extensions/sticky-header/bootstrap-table-sticky-header.css" rel="stylesheet">

</head>
<body>

<div class="ui relaxed grid">
	<div class="top aligned sixteen column relaxed row">
		<div class="four wide padded column">
		<form method="POST">
		<ul class="left-nav">
		<li class="row">
			<h2>FINPERF |</h2><span class="head-link"><a target="_new" href="https://www.ic.gc.ca/eic/site/pp-pp.nsf/eng/home">Financial Performance Data for Canadian Businesses</a>
		</li>
		<div class="row">&nbsp</div>
		<h3>Choose Parameters</h3>
		<li class="row">
			<div class="ui form">
				<div class="inline field">
					<label>Datasets</label>
					<select name="datasets" class="label ui search selection fluid dropdown datasets" multiple="">
						<option value="">All Datasets</option>
						
						<?php
							$datasets = array(
								'balance'=>'Balance Sheet',
								'ratios'=>'Financial Ratios',
								'revenue'=>'Revenue',
								'expenses'=>'Expenses'
								);
								
							foreach ($datasets as $key => $value){
								echo "<option value='".$key."'&dataset'>".$value."</option>";
							}
						?>
					</select>
				</div>
			</div>
		</li>
		<div class="row">&nbsp</div>
		<li class="row">
			<div class="ui form">
				<div class="inline field">
					<label>Provinces & Territories</label>
					<select name="provinces" class="label ui search input selection fluid dropdown provinces" multiple="">
						<option value="">All Provinces & Territories</option>
						<?php
							$data = file('./data/provinces.csv');
							$provinces = [];
							
							foreach ($data as $line){
								$provinces[] = str_getcsv($line);
							}
							
							foreach ($provinces as $pnum => $prov){
								echo "<option value='".$prov[0]."&prov'>".$prov[1]."</option>";
							}
						?>
					</select>
				</div>
				<div class="ui toggle checkbox">
					<input type="checkbox" name="group-provinces">
					<label>Group provinces as <input type="text" placeholder="custom group name" class="groupby"></input></label>
				</div>
			</div>
		</li>
		<div class="row">&nbsp</div>
		<li class="row">
			<div class="ui form">
					<div class="inline field">
						<label>Industries</label>
						<select name="industries" class="label ui search selection fluid dropdown industries" multiple="">
							<option name="all" value="">All Industries</option>
							<?php
								$data = file('./data/naics.csv');
								$naics = [];
								
								foreach ($data as $line){
									$naics[] = str_getcsv($line);
								}
							
								foreach ($naics as $nnum => $ncode){
									echo "<option value='".$ncode[0]."&ind'>".$ncode[1]."&nbsp&nbsp(".$ncode[0].")</option>";
								}
							?>
						</select>
					</div>
					<div class="ui toggle checkbox">
						<input type="checkbox" name="group-industries">
						<label>Group industries as <input type="text" placeholder="custom group name" class="groupby"></input></label>
					</div>
				</div>
		</li>
		<div class="row">&nbsp</div>
		<li class="row">
			<button class="ui button reset">
				<i class="undo alternate icon center"></i>
				Reset
			</button>
			<div class="blue ui button create" onclick="checkValues()">
				<i class="bolt icon"></i>
				Create Report
			</div>
		</li>
		</ul>
		</form>
	</div>
	<div id="data-table" class="ten wide padded column">
		<?php
			$cols = ['Incorporation Status', 'NAICS', 'Province', 'Year ', 'Type', 'Size', 'Value', 'Bottom Quartile', 'Lower Middle', 'Upper Middle', 'Top Quartile','Category'];
			
			try{
				$myPDO = new PDO('sqlite:./data/finperf.db');
				$query = $myPDO->query("DESCRIBE finperf;");
				$query = $myPDO->query("select * from finperf where NAICS = 331 limit 5");
				//$query->setFetchMode(PDO::FETCH_ASSOC);
			
				while ($row = $query->fetch()) {
					extract($row);
					$data[] = array();
				}

				//$df = json_encode($data);
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		?>
	</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="js/jquery-ui.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.6.2/core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.13.1/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table/dist/extensions/multiple-sort/bootstrap-table-multiple-sort.js"></script>
<script src="js/tableExport.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.13.1/extensions/export/bootstrap-table-export.js"></script>
<script src="https://unpkg.com/bootstrap-table/dist/extensions/select2-filter/bootstrap-table-select2-filter.min.js"></script>
<script src="https://unpkg.com/bootstrap-table/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js"></script>

</body>
</html>

<script>
$('.label.ui.dropdown')
  .dropdown();

$('.no.label.ui.dropdown')
  .dropdown({
  useLabels: false
});

$('.ui.button.reset').on('click', function () {
  $('.ui.dropdown')
    .dropdown('restore defaults')
	document.getElementById('data-table').innerHTML = '<p>';
})

function createReport(url){

	console.log('Creating data table');
	
	columns = [
		{field:'Incorporation Status',title:'Incorporation Status'},
		{field:'NAICS',title:'NAICS'},
		{field:'Province',title:'Province'},
		{field:'Year',title:'Year'},
		{field:'Type',title:'Type'},
		{field:'Size',title:'Size'},
		{field:'Value',title:'Value'},
		{field:'Bottom Quartile',title:'Bottom Quartile'},
		{field:'Lower Middle',title:'Lower Middle'},
		{field:'Upper Middle',title:'Upper Middle'},
		{field:'Top Quartile',title:'Top Quartile'},
		{field:'Category',title:'Category'},
		{field:'Dataset',title:'Dataset'}
		];
	
	document.getElementById('data-table').innerHTML = '<p><table id="table" class="table-striped table-condensed"></table>';
	//generate the table dynamically
	//generate a dataset dynamically from a database
	//..combine based on user selections
	//Bottom, Lower, Upper as column names;
	//Provinces as super header
	//dataset as subgroups
	$('#table').bootstrapTable({
	  method: 'get',
	  url: url,
	  pagination: true,
	  search: true,
	  showExport: true,
	  showColumns: true,
	  showMultiSort: true,
	  stickyHeader: true,
	  smartDisplay: true,
	  sidePagination: 'client',
	  exportTypes: ['csv','excel','json'],
	  columns: columns
	})
	
	$('#toolbar').find('select').change(function () {
		$table.bootstrapTable('destroy').bootstrapTable({
		exportDataType: $(this).val()
		})
	})
}

function checkValues(){

	var url = '';
	
	var values = new Array(5);
	
	values[0] = document.getElementsByName('group-provinces')[0].checked;
	values[1] = document.getElementsByName('group-industries')[0].checked;
	values[2] = document.getElementsByClassName('groupby')[0].value;
	values[3] = document.getElementsByClassName('groupby')[1].value;
	
	var selected = document.getElementsByClassName('ui label transition');
	
	var datasets = [];
	var provinces = [];
	var industries = [];

	for (i=0; i < (selected.length); i++){
		var group = selected[i].dataset.value.split('&');
		
		if (group[1] == 'dataset')
			datasets.push(group[0]);
			
		if (group[1] == 'prov')
			provinces.push(group[0]);
			
		if (group[1] == 'ind')
			industries.push(group[0]);
	}

	createReport(url);
}
</script>