<?php

// Required 
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise;


// uri can be array or string 
// params must be array 
function API_request($uri='', $method='GET', $params=''){
	
	$client = new Client(['timeout' => 2000, 'headers' => ['user-agent' => 'nWatch 3.0'] ]);
	
	if(is_array($uri)):
		
		switch($method):
		
			case 'GET':
			
				foreach($uri as $endpoint):
				
					$promises[] = $client->getAsync($endpoint);
				
				endforeach; 
			
			break;
			case 'POST':
				
				foreach($uri as $endpoint):
			
					$promises[] = $client->postAsync($endpoint, $params);
				
				endforeach; 
				
			break; 
		
		endswitch;
	
		// Wait for the requests to complete, even if some of them fail
		$responses = Promise\Utils::settle($promises)->wait(true);
				
		foreach($responses as $response):
			
			if(isset($response['value'])) : 
				$content[] = (string) $response['value']->getBody();
			endif; 
		
		endforeach; 
	
	
	else: 
		
		switch($method):
	
			case 'GET':
				
				$response = $client->get($uri);
				
			break;
			case 'POST':
				
				$response = $client->post($uri, $params);
				
			break; 
			
		endswitch; 
		
		$content = (string) $response->getBody();
	
	endif; 
		
	return $content; 
	
	
}



function use_node_api($method,$params = ''){
	
	$postfields['jsonrpc'] 	= '2.0';
	$postfields['id'] 		= '1';
	$postfields['method']	= $method; 
	
	if(empty($params)):
		$postfields['params'] = new ArrayObject();
	else:
		$postfields['params']	= $params; 
	endif;

	$post 		= ['json' => $postfields];  
			
	$api_host 	= random_select_node(); 
	$url 		= 'http://'.preg_replace("/\s+/", "",$api_host).':30003/';
				
	$return 	= API_request($url, 'POST', $post);
	
	if(!empty($return)): 
	
		$data 	= json_decode($return, true);
		return $data; 
	
	else : 
		
		use_node_api($method,$params); 
	
	endif; 
	
	
}

function random_select_node(){
	
	if($nodes = get_nodes_list()):
		
		$max 	= count($nodes)-1; 
		$node 	= explode(',', $nodes[rand(0,$max)]); 
		
		if(check_node_status($node[0])):
			
			return $node[0]; 
			
		else:
		
			random_select_node(); 
		
		endif; 
	
	else:
	
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

function check_node_status($ip){
	
	$url = 'http://'.preg_replace("/\s+/", "",$ip).':30003/';
	
	$postfields['jsonrpc'] 	= '2.0';
	$postfields['id'] 		= '1';
	$postfields['method']	= 'getnodestate'; 
	$postfields['params'] 	= new ArrayObject();
	
	$post 					= ['json' => $postfields];
	$return 				= API_request($url, 'POST', $post);
	
	if(!empty($return)): 
	
		$data 	= json_decode($return, true);
		
		if( isset($data['result']['syncState']) AND $data['result']['syncState'] == 'PERSIST_FINISHED' ) : 
			
			return true; 
			
		else : 
		
			return false; 
		
		endif; 
	
	else : 
		
		return false;  
	
	endif; 
	
	
}


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
						
			$method 		= 'getbalancebyaddr'; 
			$params 		= ['address' => $address];
			$wallet_balance = use_node_api($method,$params); 
			
			$wallet_data['wallets'][$i]['nkn']['balance'] = $wallet_balance['result']['amount'];
			
			
			// Total wallets 
			$wallet_data['stats']['total_nkn'] = $wallet_data['stats']['total_nkn'] + $wallet_data['wallets'][$i]['nkn']['balance']; 
						
			$i++; 
			
			if($i == 9): break; endif; 
			
		endforeach; 
				
		return $wallet_data; 
	
	else : 
	
		return false; 
		
	endif; 
	
	
}


function get_transactions($wallet){
	

	$url 	= 'https://openapi.nkn.org/api/v1/addresses/'.preg_replace("/\s+/", "",$wallet).'/transactions';
	$json 	= API_request($url, 'GET');
	$data 	= json_decode($json, true);
	
	return $data['data']; 

}

function display_transaction($transaction, $wallet){
	
	$return = '<div class="single-transaction mb-3">'; 
	
	switch($transaction['txType']) : 
	
		case 'COINBASE_TYPE':
			
			$return .= '<div class="d-flex align-items-center">';
			
				$return .= '<div class="flex-shrink-0">';
					$return .= '<img src="core/img/mining.svg" height="40" class="me-2">';
				$return .= '</div>';
				
				$return .= ' <div class="flex-grow-1 ms-3">';
		
					$return .= '<strong>Mining reward</strong> ( + '.nknValue($transaction['payload']['amount']).' NKN ) <br> 
					<span class="text-muted">'.time_elapsed_string($transaction['payload']['added_at']).'</span>'; 
			
				$return .= '</div>';
				
			$return .= '</div>';
			
		break;
		
		case 'TRANSFER_ASSET_TYPE':
		
			if($transaction['payload']['senderWallet'] == $wallet):
				
				$img 	= '<img src="core/img/send_funds.svg" height="40" class="me-2">'; 
				$type 	= '<strong>Funds sent </strong>';
				$value 	= '-'.nknValue($transaction['payload']['amount'] + $transaction['fee']);
			
			else : 
				
				$img 	= '<img src="core/img/receive_funds.svg" height="40" class="me-2">'; 
				$type 	= '<strong>Funds received</strong>';
				$value 	= '+'.nknValue($transaction['payload']['amount']);
				
			endif; 
			
			
			$return .= '<div class="d-flex align-items-center">';
		
				$return .= '<div class="flex-shrink-0">';
					$return .= $img;
				$return .= '</div>';
				
				$return .= ' <div class="flex-grow-1 ms-3">';
		
					$return .= $type.' ( '.$value.' NKN ) <br> 
					<span class="text-muted">'.time_elapsed_string($transaction['payload']['added_at']).'</span>'; 
			
				$return .= '</div>';
				
			$return .= '</div>';
			
			
		break;
		
		default  : 
			$return .= '<strong>'.$transaction['txType'].'</strong> ( '.nknValue($transaction['payload']['amount']).' NKN ) <br> 
			<span class="text-muted">'.time_elapsed_string($transaction['payload']['added_at']).'</span>';
			
	endswitch;
	
	$return .= '</div>'; 
	
	return $return;  
	
}




function get_nodes($blockCount = 1){
	
	if(file_exists('nodes.txt')): 
	
		$nodes_file 	= file_get_contents(dirname(__FILE__).'/nodes.txt'); 
		$nodes 			= explode("\n", $nodes_file);
		
		$return['total_nodes'] 		= 0;
		$return['total_proposals'] 	= 0;
		$return['total_rewards'] 	= 0;
		$return['max_relay'] 		= 0;
		$return['min_relay'] 		= 999999999999;
		$return['average_relay'] 	= 0;  
		
		$return['stats']						= []; 
		$return['stats']['ERROR']				= 0; 
		$return['stats']['WAIT_FOR_SYNCING']	= 0;
		$return['stats']['SYNC_STARTED']		= 0;
		$return['stats']['SYNC_FINISHED']		= 0;
		$return['stats']['PERSIST_FINISHED']	= 0;
		$return['stats']['OFFLINE']				= 0;
		
		
		if(is_array($nodes)) : 
			
			foreach($nodes as $nodel) :
					
				// Whitelines .. 
				if(isset($nodel) AND !empty($nodel)) : 
					
					$data 	= explode(',', $nodel); 
					$uris[] = 'http://'.preg_replace("/\s+/", "",$data[0]).':30003/';
					
					$nodeTab[preg_replace("/\s+/", "",$data[0])] = @$data[1]; 
					
				endif;
				
			endforeach;
			
			$nodes = []; 
			
			$postfields['jsonrpc'] 	= '2.0';
			$postfields['id'] 		= '1';
			$postfields['method']	= 'getnodestate';
			$postfields['params']  	= new ArrayObject(); 
			
			$post 					= ['json' => $postfields];
			
			$data = API_request($uris,'POST', $post);
			
			if(is_array($data)): 
				
				foreach($data as $json):
					
					$nodeData = json_decode($json, true); 
					
					if(preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $nodeData['result']['addr'], $ip_match)): 
						
												
						if(array_key_exists($ip_match[0], $nodeTab)):
						
							$nodes[$ip_match[0]]['name'] = @$nodeTab[$ip_match[0]];
							$nodes[$ip_match[0]]['data'] = $nodeData; 
							
						endif; 
					 
					endif; 
					
				endforeach; 
			
			endif; 
		
		endif; 
		
			
		if(is_array($nodes)) : 
		
			foreach($nodes as $ip => $nodeData) :
										
				
				if(empty($nodeData['name'])) : 
					$name = "Server Doe"; 
				else : 
					$name = strip_tags($nodeData['name']);
				endif; 
				
				// Common 
				
				$return['nodes'][$ip]['ip'] 	= $ip;
				$return['nodes'][$ip]['name'] 	= $name;
				
				//$node 	= get_node_status(preg_replace("/\s+/", "",$ip));
					
				if(!empty($nodeData['data'])) :
				
					$node = $nodeData['data'];  
					
					// Error 
						
					if(isset($node['error'])):
					
						$return['nodes'][$ip]['style']['border'] 	= 'border-alert';
						$return['nodes'][$ip]['style']['cell'] 		= 'bg-alert';
						$return['nodes'][$ip]['style']['img'] 		= 'core/img/warning.svg';
						
						switch($node['error']['code']): 
						
							case '-45022':
								$return['nodes'][$ip]['syncState'] = "Generation fees needs to be paid to : ".$node['error']['walletAddress']; 
							break;
							
							case '-45023':
								$return['nodes'][$ip]['syncState'] = "ID generation in process"; 
							break;
						
							case '-45024':
								$return['nodes'][$ip]['syncState'] = "Database is unavailable <br> <small>(database optimization might be in progress)</small>"; 
							break;
							
							default : 
								$return['nodes'][$ip]['syncState'] 	= strip_tags($node['error']['message']); 
						
						endswitch; 
						
						$return['nodes'][$ip]['height']				= 0;
						$return['nodes'][$ip]['relayMessageCount'] 	= 0; 
						$return['nodes'][$ip]['uptime'] 			= 0;
						
						$return['stats']['ERROR']++;
					
					else : 
						
						// Get node rewards from pubkey 
						$pubkey 	= $node['result']['publicKey']; 
						$url 		= 'https://api.my-nkn.cloud/rewards/pubkey/'.$pubkey; 
						$data 		= API_request($url, 'GET'); 
						$rewards 	= json_decode($data, true); 
						
						
						$return['nodes'][$ip]['syncState'] 			= strip_tags(str_replace('_', ' ', $node['result']['syncState']));
						$return['nodes'][$ip]['height'] 			= strip_tags($node['result']['height']);
						$return['nodes'][$ip]['relayMessageCount'] 	= strip_tags($node['result']['relayMessageCount']);
						$return['nodes'][$ip]['version'] 			= strip_tags($node['result']['version']);
						$return['nodes'][$ip]['proposalSubmitted'] 	= strip_tags($node['result']['proposalSubmitted']);
						$return['nodes'][$ip]['uptime'] 			= secondsToTime($node['result']['uptime']);
						$return['nodes'][$ip]['id']					= strip_tags($node['result']['id']); 
						
						if(isset($rewards) AND is_array($rewards)):
							
							$return['nodes'][$ip]['rewards'] = $rewards['count']; 
							
							if($rewards['count'] != 0):
								$return['total_rewards'] = $return['total_rewards'] + $rewards['count'];
							endif;
							
						else: 
						
							$return['nodes'][$ip]['rewards'] = 0; 
						
						endif; 
						
						
						
						if($node['result']['proposalSubmitted'] != 0):
							$return['total_proposals'] = $return['total_proposals'] + $node['result']['proposalSubmitted'];
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
						
						if($true_relay < $return['min_relay']):
							$return['min_relay'] = $true_relay; 
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
					
			
			endforeach;
			
			// Quick math 
			$return['average_relay'] = ($return['min_relay'] + $return['max_relay'])/2; 
			
		endif; 
		
		return $return; 
	
	else: 
	
		return false; 
	
	endif;
	
	
}


function get_node_neighbors($ip){
	
	$url = 'http://'.preg_replace("/\s+/", "",$ip).':30003/';
	
	$ch = curl_init($url);
		
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS,'{"jsonrpc":"2.0","method":"getneighbor","params":{},"id":1}');
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 2);
	
	$server_output = curl_exec($ch);
		
	curl_close ($ch);
	
	$json = json_decode($server_output, true); 
		
	return $json;
	
}

function get_json($url){
	
	$headers = [
		"Accept: application/vnd.github.v3+json",
		"user-agent: nWatch"
	]; 
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 4);
	
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

	$url = 'http://'.preg_replace("/\s+/", "",$ip).':30003/';
	
	$postfields['jsonrpc'] 	= '2.0';
	$postfields['id'] 		= '1';
	$postfields['method']	= 'getnodestate';
	$postfields['params']  	= new ArrayObject(); 
	
	$post 					= ['json' => $postfields];
	
	$json = API_request($url,'POST', $post);
	$data = json_decode($json, true); 
	
	return $data; 
	
}


function nkn_GeoStat(){
	
	$geoStats 	= API_request('https://api.nkn.org/v1/geo/summary', 'GET');
	$data 		= json_decode($geoStats, true); 
	
	$countries['stats']['total'] 	= 0; 
	
	if(isset($data['Payload']['summary']) AND !empty($data['Payload']['summary'])) : 
		
		foreach($data['Payload']['summary'] as $country): 
			
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
	
	return number_format($number,$decimals,$locale['decimal_point'],$locale['thousands_sep']);

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


if ('' != directory()) {
	$subdirectory = '/'.directory().'/';
} else {
	$subdirectory = '/';
}

if (isset($_SERVER['HTTP_HOST'])) {
	if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
		define('HOST_URL', $_SERVER['HTTP_X_FORWARDED_PROTO'].'://'.$_SERVER['HTTP_HOST'].$subdirectory);
	} elseif (isset($_SERVER['REQUEST_SCHEME'])) {
		define('HOST_URL', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$subdirectory);
	} else {
		if (isset($_SERVER['HTTPS']) && 'on' == $_SERVER['HTTPS']) {
			define('HOST_URL', 'https://'.$_SERVER['HTTP_HOST'].$subdirectory);
		} else {
			define('HOST_URL', 'http://'.$_SERVER['HTTP_HOST'].$subdirectory);
		}
	}
}

//# Subdirectory trick
function directory()
{
	return substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))) + 1);
}



?>