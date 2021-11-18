<?php 

session_start(); 
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60))); // 1 hour

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

// Locale definition 
$loc = setlocale(LC_ALL, $locale.'.utf8');
if($loc == false): 
	$loc = setlocale(LC_ALL, $locale.'.UTF-8');
endif; 
if($loc == false): 
	$loc = setlocale(LC_ALL, $locale);
endif; 

// Management 

if(isset($_POST['form_type'])) : 
	
	
	switch($_POST['form_type']) : 
		
		case 'ext_stats': 
			
			$blocHeight 	= use_node_api('getblockcount'); 			
			$netStats 		= nkn_GeoStat(); 
			
			$json['blocksize'] 	= $blocHeight['result'];
			$json['netstats'] 	= $netStats['stats']['total']; 
			
			echo json_encode($json);
			
			exit(); 
			
		break; 
		
		case 'nodes_stats': 
			
			$nodes 				= get_nodes(1);
			$json['proposals'] 	= $nodes['total_proposals']; 
			$json['relay']		= $nodes['average_relay']; 
			$json['stats']		= $nodes['stats']; 
			$json['rewards']	= $nodes['total_rewards']; 
			
			foreach($nodes['stats'] as $key => $value):
				$json['stats.'.$key] 	= $value; 
			endforeach; 
				
			echo json_encode($json); 
			
			exit(); 

		break; 
		
		case 'node_infos': 
			
			$node = get_node_status($_POST['ip']); 
			echo json_encode($node, JSON_PRETTY_PRINT); 
			
			exit(); 
			
		break;
		
		case 'wallet_transactions':
		
			$transactions = get_transactions($_POST['wallet']); 
			
			if(!empty($transactions)): 
			
				foreach($transactions as $transaction) : 
					echo display_transaction($transaction, $_POST['wallet']);  
				endforeach; 
			
			endif; 
			
			exit(); 
		
		break; 
		
		
		default: 
			exit(); 
	
	endswitch; 
	
	
else : 	

	
	// Table update 
	
	$blocHeight 	= use_node_api('getblockcount'); 
	$block 			= $blocHeight['result'];
	
	$nodes 			= get_nodes($block); 
		
	$data 			= [];
	$i				= 0; 
	
	foreach($nodes['nodes'] as $node): 
		
		if(!isset($node['name']) OR empty($node['name'])) : 
			$data[$i][] = '<span data-bs-toggle="tooltip" data-bs-placement="top" title="ID :'. $node['id'] .'"> Server Doe '.$i.'</span>';
		else : 
			$data[$i][] = '<span data-bs-toggle="tooltip" data-bs-placement="top" title="ID :'. $node['id'] .'">'. $node['name'].'</span>';
		endif; 
		
		 
		$data[$i][] = '<a class="ms-2" href="http://nstatus.org/?ip='.$node['ip'].'" target="_blank"><img src="core/img/open.svg" height="20" alt="check on nstatus" /> '.$node['ip'].'</a>';
		
		switch($node['syncState']):
			
			case 'PERSIST FINISHED':
				$node['syncState'] = 'Mining';
			break;
			
			case 'WAIT FOR SYNCING':
				$node['syncState'] = 'Waiting for sync.';
			break;
			
			case 'SYNC STARTED':
				$node['syncState'] = 'Sync. started';
			break;
			
			case 'SYNC FINISHED':
				$node['syncState'] = 'Sync. finished';
			break;
			
			case 'OFFLINE':
				$node['syncState'] = 'Offline';
			break;
			
		endswitch;
		
		
		if(isset($node['remain'])) : 
			$data[$i][] = '<img src="'.$node['style']['img'].'" class="float-start me-1" height="25"><span class="d-none d-md-inline">'.$node['syncState'].' <small>('.$node['remain'].'%)</small></span>';
		else :
			$data[$i][] = '<img src="'.$node['style']['img'].'" class="me-1" height="25"><span class="d-none d-md-inline">'.$node['syncState'].'</span>';
		endif; 
		
		$data[$i][] = number_format_locale($node['height'], 0); 
		$data[$i][] = number_format_locale($node['ping'],0).' ms'; 
		$data[$i][] = number_format_locale($node['relayperhour'], 0); 
		$data[$i][] = $node['version']; 
		$data[$i][] = $node['rewards'];
		$data[$i][] = $node['uptime']; 
		
		$i++; 
		
	endforeach; 
	
	$array['data'] = $data; 
	$json = json_encode($array); 
	
	echo $json; 

endif; 

?>