<?php

	include("includes/header.php");
	
	function table_row($anim_found, $root_path){
		
		$row = chr(9).chr(9).chr(9)."<tr>\n";
		$row .= chr(9).chr(9).chr(9).chr(9)."<td><a href='".$root_path."animation_log/".$anim_found->public_key."/hourly'>".$anim_found->anim_name."</a></td>\n";
		$row .= chr(9).chr(9).chr(9).chr(9)."<td>".$anim_found->total_play_count."</td>\n";
		$row .= chr(9).chr(9).chr(9).chr(9)."<td>".$anim_found->first_record_date."</td>\n";
		$row .= chr(9).chr(9).chr(9)."</tr>\n";
		
		return $row;		
	}
	
?>

      

        <div class="panel panel-default">
            <table class="table table-striped table-hover" id="mainTable">
                <thead>
                    <tr>
                        <th>
                        	Animation
                        </th>
                        <th>
                        	Playcount
                        </th>
                        <th>
                        	Campaign start date
                        </th>
                    </tr>
                </thead>
                <tbody>
<?php

	foreach($anims_found as $anim_found){
	
		echo table_row($anim_found, $root_path);
	
	}

?>
                </tbody>
            </table>
        </div>
    
    
<?php
	
	include("includes/footer.php");
	
?>