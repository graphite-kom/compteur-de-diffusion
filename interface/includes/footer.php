    
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
		
	?>
    
    
</body>
</html>