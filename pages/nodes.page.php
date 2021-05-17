<div class="container-fluid header">
	
	<?php  
	
	$block 		= get_json('https://openapi.nkn.org/api/v1/statistics/counts'); 
	$netStats 	= nkn_GeoStat(); 
	$github 	= get_json('https://api.github.com/repos/nknorg/nkn/releases'); 
	$nodes 		= get_nodes($block['blockCount']); 
		
	?>
		
	<div class="row pb-5">
		
		<div class="col-md-6 mb-3">
			
			<div class="row mt-2 p-3 nodes_status">
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Your nodes</h6>
					<span class="stats"><?= number_format_locale($nodes['total_nodes'],0) ?></span>
				</div>
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Max relay</h6>
					<span class="nodes-stats" data-prop="relay"><?= number_format_locale($nodes['max_relay'],0) ?></span>
				</div>
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Reward(s)</h6>
					<span class="nodes-stats" data-prop="proposals"><?= $nodes['total_rewards'] ?></span>
				</div>
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Latest block</h6>
					<span class="ext-stats" data-prop="blocksize"><?= number_format_locale($block['blockCount'],0) ?></span>
				</div>
				
				<div class="col-6 col-lg-4 p-3 mb-3 border-bottom">
					<h6>Total nodes</h6>
					<span class="ext-stats" data-prop="netstats"><?= number_format_locale($netStats['stats']['total'],0) ?></span>
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
					<span class="nodes-stats" data-prop="stats.PERSIST_FINISHED"><?= $nodes['stats']['PERSIST_FINISHED']  ?></span>
				</div>
				
				<div class="col-4 p-3 mb-3 border-start">
					<h6>Sync started</h6>
					<span class="nodes-stats" data-prop="stats.SYNC_STARTED"><?= $nodes['stats']['SYNC_STARTED']  ?></span>
				</div>
				
				<div class="col-4 p-3 mb-3 border-start">
					<h6>Sync finished</h6>
					<span class="nodes-stats" data-prop="stats.SYNC_FINISHED"><?= $nodes['stats']['SYNC_FINISHED']  ?></span>
				</div>
				
				<div class="col-4 p-3 mb-3 border-warning">
					<h6>Waiting for sync</h6>
					<span class="nodes-stats" data-prop="stats.WAIT_FOR_SYNCING"><?= $nodes['stats']['WAIT_FOR_SYNCING']  ?></span>
				</div>
				
				<div class="col-4 p-3 mb-3 border-alert">
					<h6>Error</h6>
					<span class="nodes-stats" data-prop="stats.ERROR"><?= $nodes['stats']['ERROR']  ?></span>
				</div>
				
				<div class="col-4 p-3 mb-3 border-alert">
					<h6>Offline</h6>
					<span class="nodes-stats" data-prop="stats.OFFLINE"><?= $nodes['stats']['OFFLINE']  ?></span>
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



<!-- Modal -->
<div class="modal fade" id="node" tabindex="-1" aria-labelledby="node" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Loading your node</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<div class="modal-body">
				
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="status-tab" data-bs-toggle="tab" data-bs-target="#status" type="button" role="tab" aria-controls="status" aria-selected="true">Node Status</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="neighbors-tab" data-bs-toggle="tab" data-bs-target="#neighbors" type="button" role="tab" aria-controls="neighbors" aria-selected="false">Neighbors</button>
					</li>
				</ul>
				
				
				<div class="tab-content" id="myTabContent">
					<!-- nWatch --> 
					<div class="tab-pane fade show active" id="status" role="tabpanel" aria-labelledby="status">
						
						<div class="row">
							
							<div class="col-lg-8 pt-4">
								<pre></pre>
							</div>
							
							<div class="col-lg-4 pt-4 explain">
								
								<h5 class="m-0 p-0">addr</h5>
								<p class="mb-2">TCP connection address of your node</p>
								
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
								<p class="mb-2">Current version of the NKN consensus protocol</p>
								
								<h5 class="m-0 p-0">publicKey</h5>
								<p class="mb-2">Node's public key from the node wallet which is used to sign sigchain transactions</p>
								
								<h5 class="m-0 p-0">relayMessageCount</h5>
								<p class="mb-2">Amount of relayed messages by your node</p>
								
								<h5 class="m-0 p-0">syncState</h5>
								<p class="mb-2">Node status</p>
								
								<h5 class="m-0 p-0">tlsJsonRpcDomain</h5>
								<p class="mb-2">HTTPS domain name where the node is reachable through secure RPC-Requests</p>
								
								<h5 class="m-0 p-0">tlsJsonRpcPort</h5>
								<p class="mb-2">Port which is used for secure RPC-Requests to communicate through HTTPS</p>
								
								<h5 class="m-0 p-0">tlsWebsocketDomain</h5>
								<p class="mb-2">HTTPS domain name where the node is reachable through secure websocket connections</p>
								
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
					
					<!-- Nodes -->
					<div class="tab-pane fade" id="neighbors" role="tabpanel" aria-labelledby="neighbors">
						
						<div class="w-100 pt-2">
						
							<table id="neighborstab" class="table pt-2">
								
								<thead>
									<tr>
										<th scope="col">Address</th>
										<th scope="col">ID</th>
										<th scope="col">Ping</th>
										<th scope="col">State</th>
									</tr>
								</thead>
								
								<tbody>
									
								</tbody>
								
							</table>
						
						</div>
						
						
					</div>
					
				</div>
	
				
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
			
		</div>
	</div>
</div>
