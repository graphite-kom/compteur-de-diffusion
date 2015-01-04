<?php

	include("includes/header.php");
	
	function table_row($detail_header, $detail_value){
		
		$row = chr(9).chr(9).chr(9)."<tr>\n";
		$row .= chr(9).chr(9).chr(9).chr(9)."<td>".$detail_header."</td>\n";
		$row .= chr(9).chr(9).chr(9).chr(9)."<td>".$detail_value."</td>\n";
		$row .= chr(9).chr(9).chr(9)."</tr>\n";
		
		return $row;		
	}
	
?>

      

        <div class="panel panel-default">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>
                        	<?php echo $anim_name?>
                        </th>
                        <th style="text-align:right;">
                        	<a href="<?php echo $root_path."animation_log/".$public_key."/export"; ?>" type="button" class="btn btn-primary btn-xs">Export</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
<?php

	foreach($anim_details as $detail_header => $detail_value){
	
		echo table_row($detail_header, $detail_value);
	
	}

?>					<tr>
						<td colspan="2">
                        	<div id="hourlyDiffusionChart" style="height: 300px;"></div>
                        </td>
                    </tr>
                    <tr>
						<td colspan="2">
                        	<div id="hourlyMachineChart" style="height: 300px;"></div>
                        </td>
                    </tr>
                    <tr>
						<td colspan="2">
                        	<div id="dailyDiffusionChart" style="height: 300px;"></div>
                        </td>
                    </tr>
                    <tr>
						<td colspan="2">
                        	<div id="dailyMachineChart" style="height: 300px;"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    
    
<?php
	
	include("includes/footer.php");
	
?>