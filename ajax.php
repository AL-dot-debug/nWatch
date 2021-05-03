<?php 

include_once('functions.php'); 



// Management 

if(isset($_POST['form_type'])) : 
	
	
	switch($_POST['form_type']) : 
	
		case 'nodes':
			
			$node_list 		= nl2br($_POST['nodetxt']); 
			$clean_list 	= strip_tags($node_list);			
			$node_file 		= fopen("nodes.txt", "w+");
			
			fwrite($node_file, $clean_list);
			fclose($node_file);
			
			exit(); 
		break;
		
		
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
			
			echo json_encode($json); 
			
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
		
		 
		$data[$i][] = $node['ip'];
		
		if(isset($node['remain'])) : 
			$data[$i][] = '<img src="'.$node['style']['img'].'" class="float-start me-1" height="25"><span class="d-none d-md-inline">'.$node['syncState'].' <small>('.$node['remain'].'%)</small></span>';
		else :
			$data[$i][] = '<img src="'.$node['style']['img'].'" class="me-1" height="25"><span class="d-none d-md-inline">'.$node['syncState'].'</span>';
		endif; 
		
		$data[$i][] = $node['height']; 
		$data[$i][] = perso_round($node['relayMessageCount'], 0); 
		$data[$i][] = $node['relayperhour']; 
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