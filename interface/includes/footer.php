    
	</div><!-- /.container -->
    
    <!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo $root_path; ?>js/ie10-viewport-bug-workaround.js"></script>
    <!-- tablesorter -->
    <script src="<?php echo $root_path; ?>js/jQuery.tablesorter/jquery.tablesorter.js"></script>
    <script src="<?php echo $root_path; ?>js/jquery.uitablefilter.js"></script>
    
    <?php
		
		if($template == 'home'){
	?>
    
    <script>
    
            // + - + - + - + - + - + - + - + - 
            
            $(document).ready(function(){ 
    
                $("#mainTable").tablesorter(); 
    
            });
            
            // + - + - + - + - + - + - + - + - 
                        
            $(function() { 
                var theTable = $('#mainTable')
                
                theTable.find("tbody > tr").find("td:eq(1)").mousedown(function(){
                    $(this).prev().find(":checkbox").click()
                    
                });
                
                $("#filter").keyup(function() {
                    $.uiTableFilter( theTable, this.value );
                })
                
                $('#filter-form').submit(function(){
                    theTable.find("tbody > tr:visible > td:eq(1)").mousedown();
                    return false;
                }).focus(); //Give focus to input field
            });  
    
            // + - + - + - + - + - + - + - + - 
            
        
        
    </script>
    
    
    <?php
	
		}
		
	
		
		if($template == 'details'){
			
	?>
    
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    <script type="text/javascript">
		
		google.load("visualization", "1", {packages:["corechart"]});
		
		// + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
		
		google.setOnLoadCallback(drawHourlyDiffusionChart);
		
		function drawHourlyDiffusionChart() {
		
			var data = google.visualization.arrayToDataTable([
				/*
				['Interval', 'Diffusions', 'Machines'],
				['2004',  1000,      400],
				['2005',  1170,      460],
				['2006',  660,       1120],
				['2007',  1030,      540]
				*/
				
<?php
	
	echo $hourly_diffusion_chart_data;
	
?>
				
			]);
			
			var options = {
				title: 'Diffusions par heure',
				hAxis: {
					title: 'Heures',
					titleTextStyle: {color: 'blue'}
				},
				vAxis: {minValue: 0},
				colors:['#428bca']
			};
			
			var chart = new google.visualization.AreaChart(document.getElementById('hourlyDiffusionChart'));
			
			chart.draw(data, options);
		
		}
		
		// + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
		
		google.setOnLoadCallback(drawHourlyMachineChart);
		
		function drawHourlyMachineChart() {
		
			var data = google.visualization.arrayToDataTable([
				/*
				['Interval', 'Diffusions', 'Machines'],
				['2004',  1000,      400],
				['2005',  1170,      460],
				['2006',  660,       1120],
				['2007',  1030,      540]
				*/
				
<?php
	
	echo $hourly_machines_chart_data;
	
?>
				
			]);
			
			var options = {
				title: 'Machines diffusant cette publicité par heure',
				hAxis: {
					title: 'Heures',
					titleTextStyle: {color: 'blue'}
				},
				vAxis: {minValue: 0},
				colors:['#5cb85c']
			};
			
			var chart = new google.visualization.AreaChart(document.getElementById('hourlyMachineChart'));
			
			chart.draw(data, options);
		
		}
		
		// + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
		
		google.setOnLoadCallback(drawDailyDiffusionChart);
		
		function drawDailyDiffusionChart() {
		
			var data = google.visualization.arrayToDataTable([
				/*
				['Interval', 'Diffusions', 'Machines'],
				['2004',  1000,      400],
				['2005',  1170,      460],
				['2006',  660,       1120],
				['2007',  1030,      540]
				*/
				
<?php
	
	echo $daily_diffusion_chart_data;
	
?>
				
			]);
			
			var options = {
				title: 'Diffusions par jour',
				hAxis: {
					title: 'Jours',
					titleTextStyle: {color: 'blue'}
				},
				vAxis: {minValue: 0},
				colors:['#428bca']
			};
			
			var chart = new google.visualization.AreaChart(document.getElementById('dailyDiffusionChart'));
			
			chart.draw(data, options);
		
		}
		
		// + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
		
		google.setOnLoadCallback(drawDailyMachineChart);
		
		function drawDailyMachineChart() {
		
			var data = google.visualization.arrayToDataTable([
				/*
				['Interval', 'Diffusions', 'Machines'],
				['2004',  1000,      400],
				['2005',  1170,      460],
				['2006',  660,       1120],
				['2007',  1030,      540]
				*/
				
<?php
	
	echo $daily_machine_chart_data;
	
?>
				
			]);
			
			var options = {
				title: 'Machines diffusant cette publicité par jour',
				hAxis: {
					title: 'Jours',
					titleTextStyle: {color: 'blue'}
				},
				vAxis: {minValue: 0},
				colors:['#5cb85c']
			};
			
			var chart = new google.visualization.AreaChart(document.getElementById('dailyMachineChart'));
			
			chart.draw(data, options);
		
		}
		
		// + - + - + - + - + - + - + - + - + - + - + - + - + - + -   
		
     </script>
    
    <?php
		
		}
		
	?>
    
</body>
</html>