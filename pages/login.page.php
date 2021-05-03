<main class="form-signin my-5">
	
	<form method="post">
		
		<div class="text-center">
			
			<img class="mb-4" src="core/img/nWatch.svg" alt="nWatch" height="100"> 
			<h1 class="h3 mb-3 fw-normal">Please sign in</h1>
			
		</div>
	
		<div class="form-floating">
			<input type="password" class="form-control" name="password" placeholder="Password">
			<label for="password">Password</label>
		</div>
		
		<!-- <div class="checkbox mb-3">
			<label>
				<input type="checkbox" value="remember-me"> Remember me
			</label>
		</div> -->
		
		<input type="hidden" name="form_type" value="login" />
		<button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
	</form>
</main>