<?php 

include_once('functions.php'); 



// Management 

if(isset($_POST['form_type'])) : 
	
	
	switch($_POST['form_type']) : 
	
		case 'nodes':
			
			$node_list 		= nl2br($_POST['nodetxt']); 
			$clean_list 	= strip_tags($node_list);
			$node_file 		= fopen("nodes.txt", "w"); 
			
			fwrite($node_file, $clean_list);
			fclose($node_file);
			
			exit(); 
		break;
		default: 
			exit(); 
	
	endswitch; 

endif; 




// Table update 

$block 		= get_json('https://openapi.nkn.org/api/v1/statistics/counts');

$nodes 	= get_nodes($block['blockCount']); 
$data 	= [];
$i		= 0; 

foreach($nodes['nodes'] as $node): 
	
	$data[$i][] = '<img src="core/img/id.svg" height="15" data-bs-toggle="tooltip" data-bs-placement="top" title="ID :'. $node['id'] .'"> '. $node['name']; 
	$data[$i][] = $node['ip'];
	
	if(isset($node['remain'])) : 
		$data[$i][] = '<img src="'.$node['style']['img'].'" height="25">'.$node['syncState'].'<br><small>'.$node['remain'].'%</small>';
	else :
		$data[$i][] = '<img src="'.$node['style']['img'].'" height="25">'.$node['syncState'];
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

?>