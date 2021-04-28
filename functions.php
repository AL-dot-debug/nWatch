<?php

function get_json($url){
	
	$headers = [
		"Accept: application/vnd.github.v3+json",
		"user-agent: nWatch"
	]; 
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	$server_output = curl_exec($ch);
		
	curl_close ($ch);
	
	if(!empty($server_output)) : 
		
		$json = json_decode($server_output, true);
		return $json;
		
	else : 
		
		return false; 
	
	endif; 
	
}

function get_node_status($ip){
	
	$url = 'http://'.$ip.':30003/';
	
	$ch = curl_init($url);
		
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS,'{"jsonrpc":"2.0","method":"getnodestate","params":{},"id":1}');
	
	$server_output = curl_exec($ch);
	
	curl_close ($ch);
	
	$json = json_decode($server_output, true); 
		
	return $json; 
	
}

function secondsToHours($seconds){
	
	$minutes 	= $seconds/60; 
	$hours 		= round($minutes/60);
	
	return $hours;  
	
}

function secondsToTime($seconds) {

	$dtF = new \DateTime('@0');
	$dtT = new \DateTime("@$seconds");

	return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');

}

function perso_round($value,$float){
	
	$return = number_format(round($value, $float), $float, ',', ' '); 
	
	return $return; 
	
}

function time_elapsed_string($datetime, $full = false) {
	
	$now 	= new DateTime;
	$ago 	= new DateTime($datetime);
	$diff 	= $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	
	return $string ? implode(', ', $string) . ' ago' : 'just now';
	
}

?>