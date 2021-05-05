<div class="container">
	
	<div class="row mt-5">
		
		<div class="col-12 mb-3">
			
		
		
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#tabnwatch" type="button" role="tab" aria-controls="home" aria-selected="true">nWatch</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#tabnodes" type="button" role="tab" aria-controls="tabnodes" aria-selected="false">Nodes</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#tabwallets" type="button" role="tab" aria-controls="tabwallets" aria-selected="false">Wallets</button>
				</li>
				<li class="nav-item" role="presentation">
					<a href="?logout" class="nav-link btn btn-warning">Logout</a>
				</li>
			</ul>
			
			
			<div class="tab-content" id="myTabContent">
				<!-- nWatch --> 
				<div class="tab-pane fade show active" id="tabnwatch" role="tabpanel" aria-labelledby="nwatch-tab">
					
					<div class="mt-5 border p-4 mb-3">
						<h2>nWatch settings</h2>
						
						<form method="post">
							<div class="mb-3">
								<label for="nwatch_password" class="form-label">nWatch password</label>
								<input type="password" class="form-control" name="nwatch_password" aria-describedby="nwatch_password_help">
								<div id="nwatch_password_help" class="form-text">Update your nWatch password instance.</div>
							</div>
							
							<div class="mb-3">
								<label for="locales" class="form-label">Format display</label>
								<select class="form-select" name="locale" aria-label="nWatch format display">
									<option value="fr-FR" <?php echo ($_COOKIE['nW_locale'] == 'fr-FR') ? 'selected' : '';  ?> >European - values in € and space separator eg: 1 000€</option>
									<option value="en-US" <?php echo ($_COOKIE['nW_locale'] == 'en-US') ? 'selected' : '';  ?> >US - values in $ and , separator eg : 1,000$</option>
								</select>								
								<div id="locales" class="form-text">Pick the format and currency to display.</div>
							</div>
							
							<input type="hidden" name="form_type" value="nwatch" />
							<button type="submit" class="btn btn-primary">Save</button>
							
						</form>
					</div>
					
				</div>
				
				<!-- Nodes -->
				<div class="tab-pane fade" id="tabnodes" role="tabpanel" aria-labelledby="nodes-tab">
					
					<div class="mt-5 border p-4 mb-3">
						
						<h2>Nodes settings</h2>
						
						<p>
							Add your nodes in the following box. <strong>Please use the following format on each line:</strong> <br> <code>IP (mandatory), Name (optional)</code> <br> <br>
							Example: <br>
							<code>1.1.1.1, Raspberry Home <br> 2.2.2.2, Raspberry Mum</code> 
						</p>
						
						<form method="post" autocomplete="off">
							
							<div class="form-floating mb-3">
								<textarea class="form-control" id="nodetxt" name="nodetxt" style="height: 400px"><?= (file_get_contents('nodes.txt') ? file_get_contents('nodes.txt') : 'Add your nodes here !')  ?></textarea>
								<label for="nodetxt">Your nodes</label>
							</div>
							
							<input type="hidden" name="form_type" value="nodes" />
							<button type="submit" class="btn btn-primary">Save</button>
			
						</form>
						
					</div>
					
				</div>
				
				<!-- Wallets --> 
				<div class="tab-pane fade" id="tabwallets" role="tabpanel" aria-labelledby="wallets-tab">
					
					<div class="mt-5 border p-4 mb-3">
						
						<h2>Wallets settings</h2>
						
						<p>
							Add your wallets in the following box. <strong>Please use the following format on each line:</strong> <br> <code>Wallet address (mandatory, start with the NKN letters), Wallet name (optional), IP (optional, if you want to associate wallets and nodes)</code> <br> <br>
							Example: <br>
							<code>NKNQUttrQxNcY6cT9EmaKBT6ijshV1UZt4x2, Donate to support nWatch, 1.1.1.1</code> 
						</p>
						
						<form method="post" autocomplete="off">
							
							<div class="form-floating mb-3">
								<textarea class="form-control" id="walletstxt" name="walletstxt" style="height: 400px"><?= (file_get_contents('wallets.txt') ? file_get_contents('wallets.txt') : 'Add your wallets here !')  ?></textarea>
								<label for="walletstxt">Your wallets</label>
							</div>
							
							<input type="hidden" name="form_type" value="wallets" />
							<button type="submit" class="btn btn-primary">Save</button>
			
						</form>
						
					</div>
					
				</div>
			</div>
		
		</div>
		
	</div>
	
</div>