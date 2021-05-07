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
								<td class="nodes-stats" data-prop="stats.<?= $key ?>"><?= $value ?></td>
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



<!-- Modal -->
<div class="modal fade" id="node" tabindex="-1" aria-labelledby="node" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<div class="modal-body">
				
				<div class="row">
					
					<div class="col-lg-8">
						<pre></pre>
					</div>
					
					<div class="col-lg-4 explain">
						
						<h5 class="m-0 p-0">addr</h5>
						<p class="mb-2">TCP connexion address of your node</p>
						
						<h5 class="m-0 p-0">currTimeStamp</h5>
						<p class="mb-2">Server date (in sec)</p>
						
						<h5 class="m-0 p-0">height</h5>
						<p class="mb-2">Node mining current block</p>
						
						<h5 class="m-0 p-0">id</h5>
						<p class="mb-2">Unique identifier of your node</p>
						
						<h5 class="m-0 p-0">jsonRpcPort</h5>
						<p class="mb-2">Port used by your node</p>
						
						<h5 class="m-0 p-0">proposalSubmitted</h5>
						<p class="mb-2">Amount of reward generated since last restart</p>
						
						<h5 class="m-0 p-0">protocolVersion</h5>
						<p class="mb-2">NKN Protocol the node currently uses to communicate with others - should be default to 1</p>
						
						<h5 class="m-0 p-0">publicKey</h5>
						<p class="mb-2">The node's public key which is used to sign sigchain transactions</p>
						
						<h5 class="m-0 p-0">relayMessageCount</h5>
						<p class="mb-2">Amount of relayed messages by your node</p>
						
						<h5 class="m-0 p-0">syncState</h5>
						<p class="mb-2">Node status</p>
						
						<h5 class="m-0 p-0">tlsJsonRpcDomain</h5>
						<p class="mb-2">HTTPS domain name where the node is reachable through regular RPC-Requests</p>
						
						<h5 class="m-0 p-0">tlsJsonRpcPort</h5>
						<p class="mb-2">Port which is used for regular RPC-Requests to communicate through HTTPS</p>
						
						<h5 class="m-0 p-0">tlsWebsocketDomain</h5>
						<p class="mb-2">HTTPS domain name where the node is reachable through websocket connections</p>
						
						<h5 class="m-0 p-0">tlsWebsocketPort</h5>
						<p class="mb-2">Port which is used for regular websocket-Requests to communicate through HTTPS</p>
						
						<h5 class="m-0 p-0">uptime</h5>
						<p class="mb-2">Server uptime (in sec)</p>
						
						<h5 class="m-0 p-0">version</h5>
						<p class="mb-2">Actual version of your node</p>
						
						<h5 class="m-0 p-0">websocketPort</h5>
						<p class="mb-2">Port used for websocket communication</p>
						
					</div>
					
					
				</div>
				
				
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
			
		</div>
	</div>
</div>
