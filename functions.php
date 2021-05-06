<?php


function canIWriteHere(){
	
	$dir = dirname(__FILE__);
	if(is_writable($dir.'/index.php')):
		return true; 
	else :
		return false; 
	endif; 
	
}

function get_wallets(){
	
	if(file_exists('wallets.txt')):
		
		$wallet_file 	= file_get_contents(dirname(__FILE__).'/wallets.txt'); 
		$wallets 		= explode("\n", $wallet_file);
		
		$i=0; 
		
		$wallet_data['stats']['total_nkn'] 			= 0; 
		$wallet_data['stats']['last_transaction'] 	= date('U', strtotime('2008-01-08 00:00:00'));
			
		foreach($wallets as $wallet): 
		
			$data 		= explode(',', $wallet); 
			$address 	= $data[0]; 
			$name 		= @$data[1];
			$ip			= @$data[2]; 
			
			$wallet_data['wallets'][$i]['nw']['address'] 	= strip_tags($address); 
			$wallet_data['wallets'][$i]['nw']['name'] 		= strip_tags($name);
			$wallet_data['wallets'][$i]['nw']['ip'] 		= strip_tags($ip);
			
			$url = 'https://openapi.nkn.org/api/v1/addresses/'.$address; 
			$wallet_data['wallets'][$i]['nkn'] = get_json($url);  
			
			// Total wallets 
			$wallet_data['stats']['total_nkn'] = $wallet_data['stats']['total_nkn'] + $wallet_data['wallets'][$i]['nkn']['balance']; 
			
			// Last transaction 
			$last_transaction_date = date('U',strtotime($wallet_data['wallets'][$i]['nkn']['last_transaction'])); 
				
			if($wallet_data['stats']['last_transaction'] < $last_transaction_date): 
				$wallet_data['stats']['last_transaction'] = $last_transaction_date; 
			endif; 
			
			
			$i++; 
		endforeach; 
		
		$wallet_data['stats']['last_transaction'] = date('d-m-Y H:i:s', $wallet_data['stats']['last_transaction']);
		
		return $wallet_data; 
	
	else : 
	
		return false; 
		
	endif; 
	
	
}


function get_nodes_list(){
	
	if(file_exists('nodes.txt')):
		
		$nodes_file = file_get_contents(dirname(__FILE__).'/nodes.txt'); 
		$nodes 		= explode("\n", $nodes_file);
		
		return $nodes; 
		
	else : 
	
		return false; 
	
	endif; 
	
}

function get_nodes($blockCount = 1){
	
	if(file_exists('nodes.txt')): 
	
		$nodes_file = file_get_contents(dirname(__FILE__).'/nodes.txt'); 
		$nodes 		= explode("\n", $nodes_file);
		
		$return['total_nodes'] 		= 0;
		$return['total_proposals'] 	= 0;
		$return['max_relay'] 		= 0;
		
		$return['stats']						= []; 
		$return['stats']['ERROR']				= 0; 
		$return['stats']['WAIT_FOR_SYNCING']	= 0;
		$return['stats']['SYNC_STARTED']		= 0;
		$return['stats']['SYNC_FINISHED']		= 0;
		$return['stats']['PERSIST_FINISHED']	= 0;
		$return['stats']['OFFLINE']				= 0;
		
		
		foreach($nodes as $nodel) :
				
			// Whitelines .. 
			if(isset($nodel) AND !empty($nodel)) : 
				
				$data 	= explode(',', $nodel); 
				$ip 	= $data[0]; 
				
				if(!isset($data[1])) : 
					$name = "Server Doe"; 
				else : 
					$name = strip_tags($data[1]);
				endif; 
				
				// Common 
				
				$return['nodes'][$ip]['ip'] 	= $ip;
				$return['nodes'][$ip]['name'] 	= $name;
				
				$node 	= get_node_status(preg_replace("/\s+/", "",$ip));
					
				if(!empty($node)) : 
					
					// Error 
						
					if(isset($node['error'])):
					
						$return['nodes'][$ip]['style']['border'] 	= 'border-alert';
						$return['nodes'][$ip]['style']['cell'] 		= 'bg-alert';
						$return['nodes'][$ip]['style']['img'] 		= 'core/img/warning.svg';
						
						$return['nodes'][$ip]['syncState'] 			= strip_tags($node['error']['message']); 
						
						$return['nodes'][$ip]['height']				= 0;
						$return['nodes'][$ip]['relayMessageCount'] 	= 0; 
						$return['nodes'][$ip]['uptime'] 			= 0;
						
						$return['stats']['ERROR']++;
					
					else : 
						
						$return['nodes'][$ip]['syncState'] 			= strip_tags(str_replace('_', ' ', $node['result']['syncState']));
						$return['nodes'][$ip]['height'] 			= strip_tags($node['result']['height']);
						$return['nodes'][$ip]['relayMessageCount'] 	= strip_tags($node['result']['relayMessageCount']);
						$return['nodes'][$ip]['version'] 			= strip_tags($node['result']['version']);
						$return['nodes'][$ip]['proposalSubmitted'] 	= strip_tags($node['result']['proposalSubmitted']);
						$return['nodes'][$ip]['uptime'] 			= secondsToTime($node['result']['uptime']);
						$return['nodes'][$ip]['id']					= strip_tags($node['result']['id']); 
						
						
						if($node['result']['proposalSubmitted'] != 0):
							$return['total_proposals']++;
						endif;
						
						$return['stats'][$node['result']['syncState']]++; 
						
						switch($node['result']['syncState']) : 
						
							case 'WAIT_FOR_SYNCING': 
								$return['nodes'][$ip]['style']['border'] 	= 'border-warning';
								$return['nodes'][$ip]['style']['cell'] 		= 'bg-warning';
								$return['nodes'][$ip]['style']['img'] 		= 'core/img/sync.svg';
							break; 
							
							case 'SYNC_STARTED': 
								$return['nodes'][$ip]['style']['border'] 	= 'border-start';
								$return['nodes'][$ip]['style']['cell'] 		= 'bg-start';
								$return['nodes'][$ip]['style']['img'] 		= 'core/img/start.svg';
								
								$return['nodes'][$ip]['remain'] 			= perso_round(($node['result']['height']/$blockCount)*100, 4);
							break; 
							
							case 'SYNC_FINISHED': 
								$return['nodes'][$ip]['style']['border'] 	= 'border-success';
								$return['nodes'][$ip]['style']['cell'] 		= 'bg-success';
								$return['nodes'][$ip]['style']['img'] 		= 'core/img/finish.svg';
							break; 
							
							case 'PERSIST_FINISHED': 
								$return['nodes'][$ip]['style']['border'] 	= 'border-success';
								$return['nodes'][$ip]['style']['cell'] 		= 'bg-success';
								$return['nodes'][$ip]['style']['img'] 		= 'core/img/mining.svg';
							break; 
							
							default : 
								$return['nodes'][$ip]['style']['border'] 	= 'border-warning';
								$return['nodes'][$ip]['style']['cell'] 		= 'bg-warning';
								$return['nodes'][$ip]['style']['img'] 		= 'core/img/sync.svg';
							break; 
						
						endswitch; 
						
						// Relay calculation
						
						$node_uptime = secondsToHours($node['result']['uptime']);
							
						if($node_uptime > 0) : 
							$return['nodes'][$ip]['relayperhour'] = perso_round(($node['result']['relayMessageCount']/$node_uptime), 0 ); 
							$true_relay = $node['result']['relayMessageCount']/$node_uptime; 
						else : 
							$return['nodes'][$ip]['relayperhour'] = perso_round($node['result']['relayMessageCount'], 0 );
							$true_relay = $node['result']['relayMessageCount']; 
						endif;
						
						if($true_relay > $return['max_relay']):
							$return['max_relay'] = $true_relay; 
						endif;
						
					endif;
				
				else : 
					
					$return['nodes'][$ip]['style']['border'] 	= 'border-alert';
					$return['nodes'][$ip]['style']['cell'] 		= 'bg-alert';
					$return['nodes'][$ip]['style']['img'] 		= 'core/img/warning.svg';
					
					$return['nodes'][$ip]['syncState'] 			= 'OFFLINE';
					$return['nodes'][$ip]['height']				= 0;
					$return['nodes'][$ip]['relayMessageCount'] 	= 0; 
					$return['nodes'][$ip]['uptime'] 			= 0;
					
					$return['stats']['OFFLINE']++; 
					
				endif; 
				
				
				$return['total_nodes']++; 
				
			endif; 
		
		endforeach; 
		
		return $return; 
	
	else: 
	
		return false; 
	
	endif;
	
	
}


function get_json($url){
	
	$headers = [
		"Accept: application/vnd.github.v3+json",
		"user-agent: nWatch"
	]; 
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 400);
	
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
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 400);
	
	$server_output = curl_exec($ch);
	
	curl_close ($ch);
	
	$json = json_decode($server_output, true); 
		
	return $json; 
	
}


function nkn_GeoStat(){
	
	$geoStats						= get_json('https://api.nkn.org/v1/geo/summary'); 
	$countries['stats']['total'] 	= 0; 
	
	if(isset($geoStats['Payload']['summary']) AND !empty($geoStats['Payload']['summary'])) : 
		
		foreach($geoStats['Payload']['summary'] as $country): 
			
			$countries['stats']['total'] 					= $countries['stats']['total'] + $country['Count']; 
			$countries['countries'][$country['Country']] 	= strip_tags($country['Count']); 
		
		endforeach; 
		
		arsort($countries['countries']);
		
		return $countries; 
		
	else : 
	
		return false; 
		
	endif; 
	
}


function secondsToHours($seconds){
	
	$minutes 	= $seconds/60; 
	$hours 		= round($minutes/60);
	
	return $hours;  
	
}

function secondsToTime($seconds) {

	$dtF = new \DateTime('@0');
	$dtT = new \DateTime("@$seconds");

	return $dtF->diff($dtT)->format('%a days, %h hours and %i minutes');

}

function perso_round($value,$float, $separator = ''){
	
	$return = number_format($value, $float, ',', $separator); 
	
	return $return; 
	
}


function number_format_locale($number,$decimals=2) {
	
	$locale = localeconv();
	
	return utf8_encode(number_format($number,$decimals,$locale['decimal_point'],$locale['thousands_sep']));

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


function nknValue($nkn){
	$nkn = substr_replace($nkn,'.',-8,0); 
	return $nkn; 
}


function check_security(){
	
	$path    	= dirname(__FILE__).'/admin/';
	$files 		= scandir($path);
	$pwd 		= 0; 
	
	foreach($files as $file):
	
		if($file != '.' AND $file != '..'): 
			
			$pwd++;  
			
		endif; 
	
	endforeach;
	
	return $pwd;
	
	
}


?>