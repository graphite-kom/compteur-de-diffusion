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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php

	foreach($anim_details as $detail_header => $detail_value){
	
		echo table_row($detail_header, $detail_value);
	
	}

?>
                </tbody>
            </table>
        </div>
    
    
<?php
	
	include("includes/footer.php");
	
?>