<?php session_start(); ?>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet">

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
					<div name="datasets" class="multiple ui search selection fluid dropdown datasets" multiple="">
						<input name="datasets" type="hidden">
						<i class="dropdown icon"></i>
						<div class="default text" value="">All Datasets</div>
						<div class="menu">
							<?php
								$datasets = array(
									'Balance Sheet'=>'Balance Sheet',
									'Financial Ratios'=>'Financial Ratios',
									'Revenue'=>'Revenue',
									'Expenses'=>'Expenses'
									);
									
								foreach ($datasets as $key => $value){
									echo "<div class='item' data-value='".$key."&dataset'>".$value."</div>";
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</li>
		<div class="row">&nbsp</div>
		<li class="row">
			<div class="ui form">
				<div class="inline field">
					<label>Provinces & Territories</label>
					<div name="provinces" class="ui search input multiple selection fluid dropdown provinces" multiple="">
						<input name="provinces" type="hidden">
						<i class="dropdown icon"></i>
						<div class="default text" value="">All Provinces & Territories</div>
						<div class="menu">
							<?php
								$data = file('./data/provinces.csv');
								$provinces = [];
								
								foreach ($data as $line){
									$provinces[] = str_getcsv($line);
								}
								
								foreach ($provinces as $pnum => $prov){
									echo "<div class='item' data-value='".$prov[1]."&prov'>".$prov[1]."</div>";
								}
							?>
						</div>
					</div>
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
						<div name="industries" class="ui fluid multiple search selection dropdown industries" multiple="">
							<input name="industries" type="hidden">
							<i class="dropdown icon"></i>
							<div class="default text" name="all" value="">All Industries</div>
							<div class="menu">
							<?php
								$data = file('./data/naics.csv');
								$naics = [];
								
								foreach ($data as $line){
									$naics[] = str_getcsv($line);
								}
							
								foreach ($naics as $nnum => $ncode){
									if ($ncode[2] == 2){
										echo "<div class='item naics_two ".$ncode[3]."' data-value='".$ncode[0]."&ind'>".$ncode[1]."&nbsp&nbsp(".$ncode[0].")</div>";
									}
									if ($ncode[2] == 3){
										echo "<div class='item naics_three ".$ncode[3]."' data-value='".$ncode[0]."&ind'>".$ncode[1]."&nbsp&nbsp(".$ncode[0].")</div>";
									}
									if ($ncode[2] == 4){
										echo "<div class='item naics_four ".$ncode[3]."' data-value='".$ncode[0]."&ind'>".$ncode[1]."&nbsp&nbsp(".$ncode[0].")</div>";
									}
									if ($ncode[2] == 5){
										echo "<div class='item naics_five ".$ncode[3]."' data-value='".$ncode[0]."&ind'>".$ncode[1]."&nbsp&nbsp(".$ncode[0].")</div>";
									}
									if ($ncode[2] == 6){
										echo "<div class='item naics_six ".$ncode[3]."' data-value='".$ncode[0]."&ind'>".$ncode[1]."&nbsp&nbsp(".$ncode[0].")</div>";
									}
								}
							?>
							</div>
						</div>
					</div>
					<div class="ui toggle checkbox">
						<input type="checkbox" name="group-industries">
						<label>Group industries as <input type="text" placeholder="custom group name" class="groupby"></input></label>
					</div>
				</div>
		</li>
		<div class="row">&nbsp</div>
		<li class="row">
			<div class="ui form">
					<div class="inline field">
						<label>Years</label>
						<div name="years" class="ui fluid multiple search selection dropdown years">
							<input name="years" type="hidden">
							<i class="dropdown icon"></i>
							<div class="default text" name="all" value="">All Years</div>
							<div class="menu">
								<?php
									for ($i=2016; $i >= 2012; $i--){
										echo "<div class='item' data-value='".$i."&yr'>".$i."</div>";
									}
								?>
							</div>
						</div>
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
		<div id="display">
			<div class="tab tble active">
				Table
			</div>
			<div class="tab charts">
				Charts
			</div>
			<div class="data-container">
				<div id="data-table" class="eleven wide padded column">
				</div>
				<div id="data-charts" class="eleven wide padded column">
				</div>
			</div>
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
<script src="https://unpkg.com/bootstrap-table@1.13.5/dist/extensions/group-by-v2/bootstrap-table-group-by.min.js"></script>
<script src="js/tableExport.js"></script>
<script src="js/bootstrap-table-export.js"></script>
<script src="https://unpkg.com/bootstrap-table/dist/extensions/select2-filter/bootstrap-table-select2-filter.min.js"></script>
<script src="https://unpkg.com/bootstrap-table/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.14.1/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>

</body>
</html>
<script>
$('.ui.dropdown')
  .dropdown();

$('.tab').on('click', function() {
	
	$('.tab').removeClass('active');
	$(this).addClass('active');
	
	if ($(this).hasClass('tble')) {
		$('#data-charts').addClass('hide');
		$('#data-table').removeClass('hide');
	}
	else {
		$('#data-charts').removeClass('hide');
		$('#data-table').addClass('hide');
	}

})

function getNumberFilterTemplate(fieldId) {
    var numberFilterClass = 'numberFilter-' + fieldId
    var template = function(bootstrapTable, col, isVisible) {
      var search = function(event, value) {
        bootstrapTable.searchText = undefined
        clearTimeout(bootstrapTable.timeoutId)
        bootstrapTable.timeoutId = setTimeout(function() {
          bootstrapTable.onColumnSearch(event, fieldId, value)
        }, bootstrapTable.options.searchTimeOut)
      }

      var $el = $('<div class="input-group input-group-sm ' + numberFilterClass + '" style="width: 100%; visibility:' + isVisible + '">' +
            '<span class="input-group-addon">&gt;</span>' +
            '<input type="number" class="form-control">' +
            '</div>')
      var $input = $el.find('input')

      $input.off('keyup').on('keyup', function(event) {
        search(event, $(this).val())
      })

      $input.off('mouseup').on('mouseup', function(event) {
        var $input = $(this)
        var oldValue = $input.val()

        if (oldValue === '') {
          return
        }

        setTimeout(function() {
          var newValue = $input.val()
          if (newValue === '') {
            search(event, newValue)
          }
        }, 1)
      })
      return $el
    }
    return template
}

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
	document.getElementById('display').style.display = 'none';
});

$('.industries.ui.dropdown')
  .dropdown({
    allowCategorySelection: true
  });

function queryData(datasets=[],provinces=[],industries=[],years=[],group_prov=false,group_ind=false,group_prov_name='',group_ind_name=''){
	
	if (datasets.length == 0) { datasets = '*'; };
	if (provinces.length == 0) { provinces = '*'; };
	if (industries.length == 0) { industries = '*'; };
	if (years.length == 0) { years = '*'; };
	
	//datasets = JSON.stringify(datasets);
	try { datasets = datasets.join(); } catch(e) {}
	try { provinces = provinces.join(); } catch(e) {}
	try { industries = industries.join(); } catch(e) {}
	try { years = years.join(); } catch(e) {}
	//values = values.join();
	
	console.log(datasets,provinces,industries,years);
	
	document.getElementById('data-table').innerHTML = '<img id="loading-icon" src="loading.gif">';
	
	$.post('table.php',({data:datasets,prov:provinces,ind:industries,gp:group_prov,gi:group_ind,gpn:group_prov_name,gin:group_ind_name,years:years}),function(result){
		//alert(result);
		
		window.query_data = JSON.parse(result);
		createReport(query_data);
		
	});
}

function createReport(result) {
	
	columns = [
		{field:'Category',title:'Category'},
		{field:'NAICS',title:'Industry'},
		{field:'Province',title:'Province'},
		{field:'Year ',title:'Year'},
		{field:'Size',title:'Size'},
		{field:'Value',title:'Value ($1,000)'},
		{field:'Bottom Quartile',title:'Bottom Quartile'},
		{field:'Lower Middle',title:'Lower Middle'},
		{field:'Upper Middle',title:'Upper Middle'},
		{field:'Top Quartile',title:'Top Quartile'},
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
	  data: result,
	  pagination: true,
	  pageSize: 25,
	  pageList: '[25,50,100,250,500,All]',
	  groupBy: true,
	  groupByField: 'Dataset',
	  filterControl: true,
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
	
	var values = new Array(5);
	
	var selected = document.getElementsByClassName('ui label transition');
	console.log(selected);
	var datasets = [];
	var provinces = [];
	var industries = [];
	var years = [];
	
	group_prov = document.getElementsByName('group-provinces')[0].checked;
	group_ind = document.getElementsByName('group-industries')[0].checked;
	group_prov_name = document.getElementsByClassName('groupby')[0].value;
	group_ind_name = document.getElementsByClassName('groupby')[1].value;
	
	for (i=0; i < (selected.length); i++){
		var group = selected[i].dataset.value.split('&');
		
		if (group[1] == 'dataset')
			datasets.push("'"+group[0]+"'");
			
		if (group[1] == 'prov')
			provinces.push("'"+group[0]+"'");
			
		if (group[1] == 'ind')
			industries.push(group[0]);
		
		if (group[1] == 'yr')
			years.push(group[0]);
	}

	document.getElementById('display').style.display = 'block';
	queryData(datasets,provinces,industries,years,group_prov,group_ind,group_prov_name,group_ind_name);
}
</script>