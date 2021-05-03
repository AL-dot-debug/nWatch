<div class="container-fluid header">
	
	<div class="container">
	
		<div class="row py-5">
			
			<?php
			
			$coingecko 	= get_json('https://api.coingecko.com/api/v3/simple/token_price/ethereum?contract_addresses=0x5cf04716ba20127f1e2297addcf4b5035000c9eb&vs_currencies=eur,eth&include_market_cap=true&include_24hr_vol=true&include_24hr_change=true&include_last_updated_at=false'); 
			
			$values 	= $coingecko['0x5cf04716ba20127f1e2297addcf4b5035000c9eb']; 
			$wallets 	= get_wallets();
			
			
			?>
			
			<div class="col-md-6 mb-3">
				
				<div class="row">
					
					<div class="col-6">
						
						<h4 class="m-0 p-0"><?= perso_round($values['eur'],4, ' ') ?>€</h4>
						<p>for 1NKN</p>
						
						<h4 class="m-0 p-0"><?= perso_round($values['eur_market_cap'],0, ' ') ?>€</h4>
						<p>Market cap</p>
						
						<h4 class="m-0 p-0"><?= perso_round($values['eur_24h_change'],2, ' ') ?>%</h4>
						<p>Value change <small>last 24h</small></p>
						
					</div>
					
					<div class="col-6">
						
						<h4 class="m-0 p-0"><?= perso_round(nknValue($wallets['stats']['total_nkn']), 4, ' ') ?></h4>
						<p>NKN in your wallets</p>
						
						<h4 class="m-0 p-0" ><?= perso_round(nknValue($wallets['stats']['total_nkn'])*$values['eur'], 2, ' ') ?>€</h4>
						<p>Value total</p>
						
						<h4 class="m-0 p-0"><?= time_elapsed_string($wallets['stats']['last_transaction']) ?></h4>
						<p>since last transaction</p>	
							
					</div>
					
				</div>
				
			</div>
			
			<div class="col-md-6">
				
				<canvas class="mx-auto" id="myChart" height="105px"></canvas>
				
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
						Balance : <strong><?= perso_round(nknValue($wallet['nkn']['balance']),4,' ') ?> NKN</strong> <br />
						Last transaction : <strong><?= time_elapsed_string($wallet['nkn']['last_transaction']) ?></strong>
					</p>
					
				</div>
			</div>
		</div>
			
		<?php endforeach; ?>
			
		</div>
		
	</div>
	
</div>