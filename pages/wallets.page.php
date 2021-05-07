<div class="container-fluid header">
	
	<div class="container">
	
		<div class="row py-5">
			
			<?php
			
			$locale 				= localeconv();
			$currency_symbol 		= preg_replace("/\s+/", "",$locale['currency_symbol']); 
			$currency_int_symbol 	= preg_replace("/\s+/", "",$locale['int_curr_symbol']); 
			
			$coingecko 	= get_json('https://api.coingecko.com/api/v3/simple/token_price/ethereum?contract_addresses=0x5cf04716ba20127f1e2297addcf4b5035000c9eb&vs_currencies='.$currency_int_symbol.'&include_market_cap=true&include_24hr_vol=true&include_24hr_change=true&include_last_updated_at=false'); 
			
			$values 	= $coingecko['0x5cf04716ba20127f1e2297addcf4b5035000c9eb']; 			
			$wallets 	= get_wallets();
			
			
			?>
			
			<div class="col-md-6 mb-3">
				
				<div class="row">
					
					<div class="col-6">
						
						<h4 class="m-0 p-0"><?= number_format_locale($values[strtolower($currency_int_symbol)],4) ?> <?= $currency_symbol ?></h4>
						<p>for 1NKN</p>
						
						<h4 class="m-0 p-0"><?= number_format_locale($values[strtolower($currency_int_symbol).'_market_cap'],0) ?> <?= $currency_symbol ?></h4>
						<p>Market cap</p>
						
						<h4 class="m-0 p-0"><?= number_format_locale($values[strtolower($currency_int_symbol).'_24h_change'],2) ?>%</h4>
						<p>Value change <small>last 24h</small></p>
						
					</div>
					
					<div class="col-6">
						
						<h4 class="m-0 p-0"><?= number_format_locale(nknValue($wallets['stats']['total_nkn']), 4) ?></h4>
						<p>NKN in your wallets</p>
						
						<h4 class="m-0 p-0" ><?= number_format_locale(nknValue($wallets['stats']['total_nkn'])*$values[strtolower($currency_int_symbol)], 2) ?> <?= $currency_symbol ?></h4>
						<p>Value total</p>
						
						<h4 class="m-0 p-0"><?= time_elapsed_string($wallets['stats']['last_transaction']) ?></h4>
						<p>since last transaction</p>	
							
					</div>
					
				</div>
				
			</div>
			
			<div class="col-md-6">
				
				<h5 class="border-bottom pb-1 mb-2">Last operations for <?= $wallets['wallets'][0]['nw']['address'] ?></h5>
				
				<div style="height:200px;overflow:scroll">
				
					<ul class="list-unstyled transactions">
					
					<?php 
					
					$transactions = get_transactions($wallets['wallets'][0]['nw']['address']); 
						
					foreach($transactions as $transaction) : 
						
						echo '<li>'.display_transaction($transaction, $wallets['wallets'][0]['nw']['address']).'</li>';
					
					endforeach; 
					
					?>
					
					</ul>
				
				</div>
				
			</div>
			
		</div>
		
	</div>
	
</div>

<div class="container my-5">
	
	<div class="row row-cols-1 row-cols-md-4 g-4">
		
		<?php foreach($wallets['wallets'] as $wallet) : ?>
		
		<div class="col">
			<div class="card">
				<div class="card-body">
					
					<div class="position-absolute top-0 end-0">
					<button onclick="copyToClipboard('#<?= $wallet['nw']['address'] ?>')" class="btn btn-secondary btn-sm"><img src="core/img/copy.svg" height="15" alt="Copy wallet address" /></button>
					</div>
					
					<h5 class="card-title">
						
						<?php 
						
						if(!empty($wallet['nw']['name']) AND !empty($wallet['nw']['ip'])):
							$title = $wallet['nw']['name'].'<br><small>('.$wallet['nw']['ip'].')</small>';
						elseif(!empty($wallet['nw']['name'])):
							$title = $wallet['nw']['name']; 
						elseif(!empty($wallet['nw']['ip'])):
							$title = $wallet['nw']['ip']; 
						else :
							$title = 'No title'; 
						endif; 
						
						echo $title; 
						
						?>
						
					</h5>
					
					<h6 class="card-subtitle mb-2 text-muted">
						<span id="<?= $wallet['nw']['address'] ?>"><?= $wallet['nw']['address'] ?></span> 
					</h6>
					
					<p class="card-text">
						Balance : <strong><?= number_format_locale(nknValue($wallet['nkn']['balance']),4) ?> NKN</strong> <br />
						Last transaction : <strong><?= time_elapsed_string($wallet['nkn']['last_transaction']) ?></strong>
					</p>
					
				</div>
			</div>
		</div>
			
		<?php endforeach; ?>
			
		</div>
		
	</div>
	
</div>