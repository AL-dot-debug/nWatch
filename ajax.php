<?php 

session_start(); 

if(!isset($_SESSION['user'])):
	echo 'Auth required';
	exit(); 
endif; 

include_once('functions.php'); 

// Locale definition 
if(!isset($_COOKIE['nW_locale'])):
	setcookie("nW_locale", 'fr-FR',  time() + (10 * 365 * 24 * 60 * 60) );
endif; 

$locale = ( isset($_COOKIE['nW_locale']) ) ? str_replace('-', '_', $_COOKIE['nW_locale'] ) : 'fr_FR';
setlocale(LC_ALL, $locale.'.utf8');


// Management 

if(isset($_POST['form_type'])) : 
	
	
	switch($_POST['form_type']) : 
		
		case 'ext_stats': 
			
			$block 		= get_json('https://openapi.nkn.org/api/v1/statistics/counts'); 
			$netStats 	= nkn_GeoStat(); 
			
			$json['blocksize'] 	= $block['blockCount'];
			$json['netstats'] 	= $netStats['stats']['total']; 
			
			echo json_encode($json);
			
			exit(); 
			
		break; 
		
		case 'nodes_stats': 
			
			$nodes 	= get_nodes(1);
			$json['proposals'] 	= $nodes['total_proposals']; 
			$json['relay']		= $nodes['max_relay']; 
			$json['stats']		= $nodes['stats']; 
			
			foreach($nodes['stats'] as $key => $value):
				$json['stats.'.$key] 	= $value; 
			endforeach; 
			
			//$json['stats']		= $stats; 
				
			echo json_encode($json); 
			
			exit(); 
		break; 
		
		case 'node_infos': 
			
			$node = get_node_status($_POST['ip']); 
			echo json_encode($node, JSON_PRETTY_PRINT); 
			
			exit(); 
			
		break;
		
		
		default: 
			exit(); 
	
	endswitch; 
	
	
else : 	


	// Table update 
	
	$block 		= get_json('https://openapi.nkn.org/api/v1/statistics/counts');
	$nodes 		= get_nodes($block['blockCount']); 
	
		
	$data 		= [];
	$i			= 0; 
	
	foreach($nodes['nodes'] as $node): 
		
		if(!isset($node['name']) OR empty($node['name'])) : 
			$data[$i][] = '<span data-bs-toggle="tooltip" data-bs-placement="top" title="ID :'. $node['id'] .'"> Server Doe '.$i.'</span>';
		else : 
			$data[$i][] = '<span data-bs-toggle="tooltip" data-bs-placement="top" title="ID :'. $node['id'] .'">'. $node['name'].'</span>';
		endif; 
		
		 
		$data[$i][] = '<a data-bs-toggle="modal" data-bs-target="#node" data-bs-ip="'.$node['ip'].'">'.$node['ip'].'</a>';
		
		if(isset($node['remain'])) : 
			$data[$i][] = '<img src="'.$node['style']['img'].'" class="float-start me-1" height="25"><span class="d-none d-md-inline">'.$node['syncState'].' <small>('.$node['remain'].'%)</small></span>';
		else :
			$data[$i][] = '<img src="'.$node['style']['img'].'" class="me-1" height="25"><span class="d-none d-md-inline">'.$node['syncState'].'</span>';
		endif; 
		
		$data[$i][] = number_format_locale($node['height'], 0); 
		$data[$i][] = number_format_locale($node['relayMessageCount'], 0); 
		$data[$i][] = number_format_locale($node['relayperhour'], 0); 
		$data[$i][] = $node['version']; 
		$data[$i][] = $node['proposalSubmitted']; 
		$data[$i][] = $node['uptime']; 
		
		$i++; 
		
	endforeach; 
	
	$array['data'] = $data; 
	$json = json_encode($array); 
	
	echo $json; 

endif; 

?>