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
				
				<div class="row">
					
					<div class="col-6">
						
						<h4 class="m-0 p-0 ext-stats" data-prop="blocksize"><?= perso_round($block['blockCount'],0, ' ') ?></h4>
						<p>Latest block</p>
						
						<h4 class="m-0 p-0 ext-stats" data-prop="netstats"><?= perso_round($netStats['stats']['total'],0, ' ') ?></h4>
						<p>Nodes</p>
						
						<h4 class="m-0 p-0 ext-stats" data-prop="github"><?= $github[0]['name'] ?></h4>
						<p>since <?= time_elapsed_string($github[0]['published_at']) ?></p>
						
					</div>
					
					<div class="col-6">
						
						<h4 class="m-0 p-0"><?= $nodes['total_nodes'] ?></h4>
						<p>Nodes</p>
						
						<h4 class="m-0 p-0 nodes-stats" data-prop="relay"><?=  perso_round($nodes['max_relay'],0, ' ') ?></h4>
						<p>Max relay</p>
						
						<h4 class="m-0 p-0 nodes-stats" data-prop="proposals"><?= $nodes['total_proposals'] ?></h4>
						<p>Reward(s)</p>	
							
					</div>
					
					<div class="col-12">
						<button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#manager">Manage my nodes</button>
					</div>
					
				</div>
				
			</div>
			
			<div class="col-md-6 d-none d-md-block text-center">
				<canvas style="margin-top:-.5rem;" class="mx-auto" id="myChart" height="105px"></canvas>
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
<div class="modal fade" id="manager" tabindex="-1" aria-labelledby="manager" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Node Manager</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
		
		<div class="modal-body">
			
			<p>
				Add your nodes in the following box. <br> 
				<strong>Please use the following format on each line:</strong> <code>IP, Name</code> <br> 
				Example: <br>
				<code><kbd class="-2">1.1.1.1, Raspberry Home <br> 2.2.2.2, Raspberry Mum</kbd></code> 
			</p>
			
			<form id="nodecfg" autocomplete="off">
				
				<div class="form-floating">
					<textarea class="form-control" id="nodetxt" name="nodetxt" style="height: 400px"><?= (file_get_contents('nodes.txt') ? file_get_contents('nodes.txt') : 'Add your nodes here !')  ?></textarea>
					<label for="floatingTextarea2">Your nodes</label>
				</div>
				
				<input type="hidden" name="form_type" value="nodes" />

			</form>
			
		</div>
		
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary btn-nodecfg-js">Save changes</button>
		</div>
		
		</div>
	</div>
</div>