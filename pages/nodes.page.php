<div class="container-fluid header">
	
	<?php  
	
	$block 		= get_json('https://openapi.nkn.org/api/v1/statistics/counts'); 
	$netStats 	= nkn_GeoStat(); 
	$github 	= get_json('https://api.github.com/repos/nknorg/nkn/releases'); 
	$nodes 		= get_nodes($block['blockCount']); 
		
	?>
	
	<div class="container">
		
		<div class="row py-5">
			
			<div class="col-md-6 mb-3">
				
				<div class="row pt-4">
					
					<div class="col-6">
						
						<h4 class="m-0 p-0 ext-stats" data-prop="blocksize"><?= number_format_locale($block['blockCount'],0) ?></h4>
						<p>Latest block</p>
						
						<h4 class="m-0 p-0 ext-stats" data-prop="netstats"><?= number_format_locale($netStats['stats']['total'],0) ?></h4>
						<p>Nodes</p>
						
						<h4 class="m-0 p-0 ext-stats" data-prop="github"><?= $github[0]['name'] ?></h4>
						<p>since <?= time_elapsed_string($github[0]['published_at']) ?></p>
						
					</div>
					
					<div class="col-6">
						
						<h4 class="m-0 p-0"><?= number_format_locale($nodes['total_nodes'],0) ?></h4>
						<p>Nodes</p>
						
						<h4 class="m-0 p-0 nodes-stats" data-prop="relay"><?=  number_format_locale($nodes['max_relay'],0) ?></h4>
						<p>Max relay</p>
						
						<h4 class="m-0 p-0 nodes-stats" data-prop="proposals"><?= $nodes['total_proposals'] ?></h4>
						<p>Reward(s)</p>	
													
					</div>
					
				</div>
				
			</div>
			
			<div class="col-md-6 d-none d-md-block">
				<div class="table-responsive">
					<table class="table">
					
						<thead>
							<tr>
								<th scope="col">Status</th>
								<th scope="col">Nodes</th>
							</tr>
						</thead>
						
						<tbody>
							
							<?php foreach($nodes['stats'] as $key => $value) : ?>
							
							<tr>
								<th scope="row"><?= $key ?></th>
								<td><?= $value ?></td>
							</tr>
							
							<?php endforeach; ?>
							
						</tbody>
					
					</table>
				</div>
			</div>
			
		</div>
	
	</div>

</div>

<div class="container-fluid">
	
	<div class="row my-5">
	
		<table id="nodes" class="table">
			
			<thead>
				<tr>
					<th scope="col">Node</th>
					<th scope="col" data-priority="1">IP</th>
					<th scope="col" data-priority="1">Status</th>
					<th scope="col">Current block</th>
					<th scope="col">Relayed Messages</th>
					<th scope="col">Relay / hour</th>
					<th scope="col">Version</th>
					<th scope="col" data-priority="2">Reward</th>
					<th scope="col">Uptime</th>
				</tr>
				</thead>
			
			<tbody>
	
			</tbody>
		
		</table>			
	
	</div>
		
</div>