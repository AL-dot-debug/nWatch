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

<div class="container my-5">
	
	<div class="row">
		
		<?php foreach($wallets['wallets'] as $wallet) : ?>
		
		<div class="col-lg-4 mb-3">
			
			<div class="nkn-card">
				<figure class="card__figure">
					<img src="core/img/nkn.svg" class="card__figure--logo"></img>
				</figure>
				
				<div class="card__reader">
					<div class="card__reader--risk card__reader--risk-one"></div>
					<div class="card__reader--risk card__reader--risk-two"></div>
					<div class="card__reader--risk card__reader--risk-three"></div>
					<div class="card__reader--risk card__reader--risk-four"></div>
				</div>
				
				<p class="card__number"><a data-bs-toggle="modal" data-bs-target="#wallet" data-bs-id="<?= $wallet['nw']['address'] ?>"><?= $wallet['nw']['address'] ?></a></p>
				
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
				
				?>
				
				<p class="card__name"><?= $title ?> - <strong><?= number_format_locale(nknValue($wallet['nkn']['balance']),0) ?> NKN</strong> <p>
				
			</div>
			
			
		</div>
			
		<?php endforeach; ?>
			
		</div>
		
	</div>
	
</div>


<!-- Modal -->
	<div class="modal fade" id="wallet" tabindex="-1" aria-labelledby="wallet" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Loading your wallet</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				
				<div class="modal-body">
					
					
					
					
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
				
			</div>
		</div>
	</div>