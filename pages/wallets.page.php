<div class="container-fluid header">
	
	<div class="container">
	
		<div class="row pb-5">
			
			<?php
			
			$locale 				= localeconv();
			$currency_symbol 		= preg_replace("/\s+/", "",$locale['currency_symbol']); 
			$currency_int_symbol 	= preg_replace("/\s+/", "",$locale['int_curr_symbol']); 
			
			$coingecko 	= get_json('https://api.coingecko.com/api/v3/simple/token_price/ethereum?contract_addresses=0x5cf04716ba20127f1e2297addcf4b5035000c9eb&vs_currencies='.$currency_int_symbol.'&include_market_cap=true&include_24hr_vol=true&include_24hr_change=true&include_last_updated_at=false'); 
			
			$values 	= $coingecko['0x5cf04716ba20127f1e2297addcf4b5035000c9eb']; 			
			$wallets 	= get_wallets();
			
			if(substr($values[strtolower($currency_int_symbol).'_24h_change'],0, 1) == '-'):
				$change_class = 'red';
			else:
				$change_class = 'green';
			endif;
			
			?>
			
			<div class="col-md-6 mb-3">
				
				<div class="row p-3 wallets_status">
					
					<div class="col-6 p-3 mb-3 border-bottom">
						<h6>NKN value</h6>
						<span class="stats"><?= number_format_locale($values[strtolower($currency_int_symbol)],4) ?> <?= $currency_symbol ?></span>
					</div>
					
					<div class="col-6 p-3 mb-3 border-bottom">
						<h6>Variation <small>(last 24h)</small></h6>
						<span class="stats <?= $change_class ?>"><?= number_format_locale($values[strtolower($currency_int_symbol).'_24h_change'],2) ?>%</span>
					</div>
					
					<div class="col-6 p-3 mb-3 border-bottom">
						<h6>Wallet(s) value</h6>
						<span class="stats"><?= number_format_locale(nknValue($wallets['stats']['total_nkn']), 4) ?> NKN</span>
					</div>
					
					<div class="col-6 p-3 mb-3 border-bottom">
						<h6>Wallet(s) value in <?= $currency_symbol ?></h6>
						<span class="stats"><?= number_format_locale(nknValue($wallets['stats']['total_nkn'])*$values[strtolower($currency_int_symbol)], 2) ?> <?= $currency_symbol ?></span>
					</div>
					
					<div class="col-lg-6 p-3 mb-3 border-bottom">
						<h6>Market cap</h6>
						<span class="stats"><?= number_format_locale($values[strtolower($currency_int_symbol).'_market_cap'],0) ?> <?= $currency_symbol ?></span>
					</div>
					
					<div class="col-lg-6 p-3 mb-3 border-bottom">
						<h6>Last transaction</h6>
						<span class="stats"><?= date('d M Y H:i', strtotime($wallets['stats']['last_transaction'])) ?></span>
					</div>
					
				</div>
				
			</div>
			
			<div class="col-md-6">
				
				<div style="height:350px;overflow-y:scroll;overflow-x:hidden">
					
					<?php 
					
					$transactions = get_transactions($wallets['wallets'][0]['nw']['address']); 
						
					foreach($transactions as $transaction) : 
						
						echo display_transaction($transaction, $wallets['wallets'][0]['nw']['address']);
					
					endforeach; 
					
					?>
									
				</div>
				
				
			</div>
			
		</div>
		
	</div>
	
</div>


<?php 

$nodes = get_nodes_list();
$i=0; 

foreach($nodes as $node): 
				
	$nodeData 		= explode(',',$node); 
	$url 			= 'https://api.my-nkn.cloud/rewards/ip/'.$nodeData[0]; 
	$nodeRewards 	= get_json($url); 
		
	$dataRewards[$i]['ip'] = $nodeData[0]; 
	
	if(isset($nodeData[1])):
		$dataRewards[$i]['name'] = $nodeData[1]; 
	else:
		$dataRewards[$i]['name'] = 'Node Doe '.$i; 
	endif; 
	
	if($nodeRewards['data']): 
		
		$dataRewards[$i]['rewards'] = $nodeRewards['data'];	
		
		foreach($nodeRewards['data'] as $rewardsData):
			
			if(isset($jsTab[date('W',strtotime($rewardsData['date']))])):
				$jsTab[date('W',strtotime($rewardsData['date']))] = $jsTab[date('W',strtotime($rewardsData['date']))]+1; 
			else:
				$jsTab[date('W',strtotime($rewardsData['date']))] = 1; 
			endif;
		
		endforeach;
		
	else: 
		$dataRewards[$i]['rewards'] = []; 				
	endif; 
	
	$i++; 

endforeach; 

ksort($jsTab); 

foreach($jsTab as $week => $rewards):
	
	$jsweeks[] 		= $week; 
	$jsrewards[] 	= $rewards; 
	
endforeach;

				
?>

<div class="jsrewards" data-values="<?= json_encode($jsrewards) ?>" ></div>
<div class="jsweek" data-values="<?= json_encode($jsweeks) ?>"></div>

<div class="container">
	
	<div class="row my-5">
		
		<div class="col-12 mb-5">
			<h2>Mining history</h2>
		</div>
		
		<div class="col-12">
			
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="graph-tab" data-bs-toggle="tab" data-bs-target="#tabgraph" type="button" role="tab" aria-controls="graph" aria-selected="true">Graph</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#tabdetails" type="button" role="tab" aria-controls="tabdetails" aria-selected="false">Details</button>
				</li>
			</ul>
			
			
			<div class="tab-content mt-5" id="myTabContent">
				<!-- Graph --> 
				<div class="tab-pane fade show active" id="tabgraph" role="tabpanel" aria-labelledby="graph">
					
					<div id="myChart"></div>
					
				</div>
				
				<!-- Details --> 
					
				<div class="tab-pane fade" id="tabdetails" role="tabpanel" aria-labelledby="tabdetails">
					
					<div class="table-responsive">
						
						<table class="table" id="rewards">
							<thead>
			
								<tr>
									<th scope="col">Node</th>
									<th scope="col">IP</th>
									<th scope="col">Total rewards</th>
								</tr>
			
							</thead>
							<tbody>
								
								<?php foreach($dataRewards as $node) : ?>
								
								
								<tr>
									<th scope="row"><?= $node['name'] ?></th>
									<td><?= $node['ip'] ?></td>
									<td><?= count($node['rewards']) ?></td>
								</tr>
								
									<?php if(!empty($node['rewards'])) :  ?>
									
									<tr>
										<td colspan="3">
											<table class="table inertable mb-4">
												<thead>
													<th scope="col">Date</th>
													<th scope="col">Block</th>
													<th scope="col">Recipient</th>
												</thead>
												<tbody>
													<?php foreach($node['rewards'] as $reward): ?>
													<tr>
														<th scope="row"><?= date('d M Y H:i', strtotime($reward['date'])) ?></th>
														<td><?= $reward['block'] ?></td>
														<td><?= $reward['recipientWallet'] ?></td>
													</tr>
													<?php endforeach;  ?>
												</tbody>
											</table>
										</td>
									</tr>
									
									<?php endif; ?>
								
								<?php endforeach; ?>
								
							</tbody>
						</table>
						
					</div>
					
				</div>
			
			</div>
			
		</div>
	
	</div>
		
</div>