<div class="container-fluid header">
	
	<?php  
	
	$blocHeight 	= use_node_api('getblockcount'); 
	$block 			= $blocHeight['result']; 
		
	$github 		= get_json('https://api.github.com/repos/nknorg/nkn/releases'); 
	$nodes 			= get_nodes_list(); 
		
	?>
		
	<div class="row pb-5">
		
		<div class="col-md-6 mb-3">
			
			<div class="row mt-2 p-3 nodes_status">
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Your nodes</h6>
					<span class="stats"><?= number_format_locale(count($nodes),0) ?></span>
				</div>
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Average relays</h6>
					<span class="nodes-stats" data-prop="relay"><span style="font-size:1.2rem">Loading values...</span></span>
				</div>
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Reward(s)</h6>
					<span class="nodes-stats" data-prop="rewards"><span style="font-size:1.2rem">Loading values...</span></span>
				</div>
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Latest block</h6>
					<span class="ext-stats" data-prop="blocksize"><?= number_format_locale($block,0) ?></span>
				</div>
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Total nodes</h6>
					<span class="ext-stats" data-prop="netstats"><span style="font-size:1.2rem">Loading values...</span></span>
				</div>
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Stable version</h6>
					<span class="ext-stats"><?= $github[0]['name'] ?></span> 
				</div>
				
			</div>
			
		</div>
		
		<div class="col-md-6 d-none d-md-block">
			
			<div class="row mt-2 p-3 nodes_status">
				
				<div class="col-4 p-3 mb-3 border-success">
					<h6>Mining</h6>
					<span class="nodes-stats" data-prop="stats.PERSIST_FINISHED"><span style="font-size:1.2rem">Loading values...</span></span>
				</div>
				
				<div class="col-4 p-3 mb-3 border-start">
					<h6>Sync started</h6>
					<span class="nodes-stats" data-prop="stats.SYNC_STARTED"><span style="font-size:1.2rem">Loading values...</span></span>
				</div>
				
				<div class="col-4 p-3 mb-3 border-start">
					<h6>Sync finished</h6>
					<span class="nodes-stats" data-prop="stats.SYNC_FINISHED"><span style="font-size:1.2rem">Loading values...</span></span>
				</div>
				
				<div class="col-4 p-3 mb-3 border-warning">
					<h6>Waiting for sync</h6>
					<span class="nodes-stats" data-prop="stats.WAIT_FOR_SYNCING"><span style="font-size:1.2rem">Loading values...</span></span>
				</div>
				
				<div class="col-4 p-3 mb-3 border-alert">
					<h6>Error</h6>
					<span class="nodes-stats" data-prop="stats.ERROR"><span style="font-size:1.2rem">Loading values...</span></span>
				</div>
				
				<div class="col-4 p-3 mb-3 border-alert">
					<h6>Offline</h6>
					<span class="nodes-stats" data-prop="stats.OFFLINE"><span style="font-size:1.2rem">Loading values...</span></span>
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
					<th scope="col" data-priority="2">Reward(s)</th>
					<th scope="col">Uptime</th>
				</tr>
			</thead>
			
			<tbody>
	
			</tbody>
		
		</table>			
	
	</div>
		
</div>