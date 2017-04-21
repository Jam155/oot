<?php

	//header('Content-Type: application/json');

	$results = json_decode($_POST);
	
	foreach($results as $j => $result) {
		//$open = str_split($opening['open']['time'],2);
		//$open = implode(':', $open);
		//$close = str_split($opening['close']['time'],2);
		//$close = implode(':', $close);
		//$day = $dow[$opening['open']['day']];
		//$acf_opening_hours[$day][$j] = array('open' => $open, 'close' => $close );		
	}
	
	//$acf_opening_hours = array($acf_opening_hours);
	//update_field($opening_field_key, $acf_opening_hours, $event_ID_success);

	//echo $results['monday'][0]['open'];
	//echo "Stuff";
	echo json_encode($results['monday']);

?>