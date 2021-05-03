<div class="container">
	
	<div class="row mt-5">
		
		<div class="col-12 mb-3 text-end">
			<a href="?logout" class="btn btn-warning">Logout</a>
		</div>
		
		<div class="col-12 border p-4 mb-3">
			
			<h2>nWatch settings</h2>
			
			<form method="post">
				<div class="mb-3">
					<label for="nwatch_password" class="form-label">nWatch password</label>
					<input type="password" class="form-control" name="nwatch_password" aria-describedby="nwatch_password_help">
					<div id="nwatch_password_help" class="form-text">Update your nWatch password instance.</div>
				</div>
				
				<input type="hidden" name="form_type" value="nwatch" />
				<button type="submit" class="btn btn-primary">Submit</button>
				
			</form>
			
		</div>
		
		<!-- <div class="col-12 border p-4 mb-3">
			
			<h2>Nodes settings</h2>
			
		</div>
		
		
		<div class="col-12 border p-4 mb-3">
			
			<h2>Wallets settings</h2>
			
		</div> -->
		
		
	</div>
	
</div>