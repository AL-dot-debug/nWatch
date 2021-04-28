<?php include_once('functions.php'); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		
		<title>nWatch - NKN Node Monitoring</title>
		<link rel="stylesheet" href="core/css/bootstrap.min.css" />
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Francois+One&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
		
	</head>
	
	<body>
		

		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container-fluid">
				
				<a class="navbar-brand" href="#">nWatch</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="#">Nodes</a>
						</li>
			
						<li class="nav-item">
							<a class="nav-link" href="#">Wallets</a>
						</li>
					
					</ul>
			
					
				</div>
				
			</div>
		</nav>
		
		
		<div class="container-fluid header">
			
			<?php  
			
			$values = get_json('https://api.coingecko.com/api/v3/simple/token_price/ethereum?contract_addresses=0x5cf04716ba20127f1e2297addcf4b5035000c9eb&vs_currencies=eur,eth&include_market_cap=true&include_24hr_vol=true&include_24hr_change=true&include_last_updated_at=false'); 
			
			$block 		= get_json('https://openapi.nkn.org/api/v1/statistics/counts'); 
			$netStats 	= get_json('https://api.nknx.org/network/stats'); 
			$github 	= get_json('https://api.github.com/repos/nknorg/nkn/releases'); 
			
			
			?>
			
			<div class="container">
			
				<div class="row">
					
					<div class="col mb-5">
						
						<h1 class="my-5">Network</h1>
						
						<div class="row">
							
							<div class="col">
								<h4><?= perso_round($values['0x5cf04716ba20127f1e2297addcf4b5035000c9eb']['eur'],4) ?><small>€</small></h4>
								<p>NKN value</p>
							</div>
							
							<div class="col">
								<h4><?= perso_round($values['0x5cf04716ba20127f1e2297addcf4b5035000c9eb']['eur_24h_change'],2) ?><small>%</small></h4>
								<p>24h change</p>
							</div>
							
							<div class="col">
								<h4><?= perso_round($values['0x5cf04716ba20127f1e2297addcf4b5035000c9eb']['eth_market_cap'],0) ?><small>ETH</small></h4>
								<p>Market Cap</p>
							</div>
							
						</div>
						
						<div class="row">
							
							<div class="col">
								<h4><?= perso_round($block['blockCount'],0) ?></h4>
								<p>Latest block</p>
							</div>
							
							<div class="col">
								<h4><?= perso_round($netStats['totalNodes'],0) ?></h4>
								<p>Nodes</p>
							</div>
							
							<div class="col">
								<h4><?= $github[0]['name'] ?></h4>
								<p>since <?= time_elapsed_string($github[0]['published_at']) ?></p>
							</div>
							
						</div>
							
						
					</div>
					
				</div>
			
			</div>
		
		</div>
		
		<div class="container">
			
			<div class="row my-5">
			
				<table id="nodes" class="table">
					
					<thead>
						<tr>
							<th scope="col">Node</th>
							<th scope="col">IP</th>
							<th scope="col">Status</th>
							<th scope="col">Current block</th>
							<th scope="col">Relayed Messages</th>
							<th scope="col">Relay / hour</th>
							<th scope="col">Version</th>
							<th scope="col">Proposal</th>
							<th scope="col">Uptime</th>
						</tr>
						</thead>
					
					<tbody>
		
					<?php 
					
					$nodes_file = file_get_contents('nodes.txt'); 
					$nodes 		= explode("\n", $nodes_file);
					
					foreach($nodes as $node) : 
						
						if(isset($node) AND !empty($node)) : 
						
							$data 	= explode(',', $node); 
							$ip 	= $data[0]; 
							$name 	= $data[1]; 
							
							unset($remains);
							
							$node 	= get_node_status($ip); 
							
							if(!empty($node)) : 
								
								if(isset($node['error'])):
									
									$border_state_class = 'border-alert';
									$cell_state_class 	= 'bg-alert';
									$img 				= 'core/img/warning.svg';
									$relayperhour		= 0; 
									
									$node['result']['syncState'] = $node['error']['message']; 
									
									$node['result']['height']				= 0;
									$node['result']['relayMessageCount'] 	= 0; 
									$node['result']['uptime'] 				= 0;
								
								else : 
								
									$state 	= $node['result']['syncState']; 
									
									switch($state) : 
									
										case 'WAIT_FOR_SYNCING': 
											$border_state_class = 'border-warning';
											$cell_state_class 	= 'bg-warning';
											$img 				= 'core/img/sync.svg';
										break; 
										
										case 'SYNC_STARTED': 
											$border_state_class = 'border-start';
											$cell_state_class 	= 'bg-start';
											$img 				= 'core/img/start.svg';
										break; 
										
										case 'SYNC_FINISHED': 
											$border_state_class = 'border-success';
											$cell_state_class 	= 'bg-success';
											$img 				= 'core/img/finish.svg';
										break; 
										
										case 'PERSIST_FINISHED': 
											$border_state_class = 'border-success'; 
											$cell_state_class 	= 'bg-success';
											$img 				= 'core/img/mining.svg';
										break; 
									
									endswitch; 
								
									$running_hours = secondsToHours($node['result']['uptime']);
									
									if($running_hours > 0) : 
										$relayperhour = perso_round(($node['result']['relayMessageCount']/$running_hours), 2 ); 
									else : 
										$relayperhour = perso_round($node['result']['relayMessageCount'], 2 ); 
									endif;
									
									if($state == 'SYNC_STARTED'):
										
										$remains = perso_round(($node['result']['height']/$block['blockCount'])*100, 2);
									
									endif; 
									
								endif; 
								
							else : 
							
								$border_state_class = 'border-alert';
								$cell_state_class 	= 'bg-alert';
								$img 				= 'core/img/warning.svg';
								$relayperhour		= 0; 
								
								$node['result']['syncState'] 			= 'OFFLINE';
								$node['result']['height']				= 0;
								$node['result']['relayMessageCount'] 	= 0; 
								$node['result']['uptime'] 				= 0; 
							
							endif; 
						
					?>
				
						<tr class="<?= $border_state_class ?>">
							<td scope="row"><?= $name ?></td>
							<td><?= $ip ?></td>
							<td class="<?= $cell_state_class ?>"> <img src="<?= $img ?>" height="25"> <?= $node['result']['syncState'] ?> <?php if($remains) : echo '<br><small>'.$remains.'%</small>'; endif; ?> </td>
							<td><?= perso_round($node['result']['height'],0) ?></td>
							<td><?= perso_round($node['result']['relayMessageCount'], 0) ?></td>
							<td><?= $relayperhour ?></td>
							<td><?= $node['result']['version'] ?></td>
							<td><?= $node['result']['proposalSubmitted'] ?></td>
							<td><?= secondsToTime($node['result']['uptime']) ?></td>
						</tr>
			
					<?php endif; endforeach;  ?>
			
				</tbody>
				
			</table>			
		
		</div>
		
		<footer class="container-fluid">
			
			<div class="row copyrights">
				<div class="col-lg-2">
					<p>© <?= date('Y') ?> AL</p>
				</div>
				<div class="col-lg-8 text-center">
					<p>Do you enjoy nWatch? Feed the dev! Donate to <code>NKNQUttrQxNcY6cT9EmaKBT6ijshV1UZt4x2</code> </p>
				</div>
				<div class="col-lg-2 text-end">
					<p>Made with ♥️ in 🇫🇮</p>
				</div>
			</div>
			
		</footer>
		
		
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
		<script src="core/js/bootstrap.bundle.min"></script>
		
		<script>
			$(document).ready(function() {
				$('#nodes').DataTable({
					"paging":   false
				});
			} );
		</script>
		
	</body>
</html>