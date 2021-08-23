<?php

// Protect the form is a password is set
if(check_security() != 1 OR $_POST['form_type'] == 'login'):
	if(!isset($_SESSION['user'])):
		exit('nope');
	endif; 
endif; 

if(isset($_POST['form_type']) AND !empty($_POST['form_type'])) : 

	switch($_POST['form_type']): 
	
		
		case 'nwatch':
			
			if(isset($_POST['nwatch_password']) AND !empty($_POST['nwatch_password'])) : 
			
				// Locate the old password file 
				$path    	= dirname(__FILE__).'/admin/';
				$files 		= scandir($path);
				
				foreach($files as $file):
				
					if($file != '.' AND $file != '..' AND $file != 'index.php'): 
						
						// Delete the file 
						unlink($path.''.$file); 
						
					endif; 
				
				endforeach;
				
				// Create a random name for the password file 
				
				$length = rand(10,100);    
				$file	= substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
				
				// Store the password 
			
				$pwd_file = fopen($path.''.$file, "w"); 
				
				$hash = password_hash($_POST['nwatch_password'], PASSWORD_DEFAULT);
				
				fwrite($pwd_file, $hash);
				fclose($pwd_file);
				
			endif; 
			
			// locale
			
			if(isset($_POST['locale']) AND !empty($_POST['locale'])) : 
				
				if(isset($_COOKIE['nW_locale'])) : 
					setcookie("nW_locale", '',  time()-3600 );
				endif; 
				
				setcookie("nW_locale", $_POST['locale'],  time() + (10 * 365 * 24 * 60 * 60) );
			
			endif; 
		
		break; 
		
		case 'login':
	
			$path    	= dirname(__FILE__).'/admin/';
			$files 		= scandir($path);
			
			foreach($files as $file):
			
				if($file != '.' AND $file != '..'):
					
					$pwd = file_get_contents($path.''.$file); 
						
					if (password_verify($_POST['password'], $pwd)) : 
						
						$_SESSION['user'] = true; 
						header('Location:'.HOST_URL.'/'); 
						exit(); 
						
					else : 
					
						$error = true; 
					
					endif; 
					
					
				endif; 
				
			endforeach; 
		
		break;
		
		case 'nodes':
		
			$node_list 		= nl2br($_POST['nodetxt']); 
			$clean_list 	= strip_tags($node_list);			
			$node_file 		= fopen(dirname(__FILE__)."/nodes.txt", "w+");
			
			fwrite($node_file, $clean_list);
			fclose($node_file);
		
		break; 
		
		case 'wallets':
	
			$wallet_list 	= nl2br($_POST['walletstxt']); 
			$clean_list 	= strip_tags($wallet_list);		
			
			$lines_arr 		= preg_split('/\n|\r/',$clean_list);
			
			if(count($lines_arr) > 10):
				
				$form_error = "Please don't add more than 10 wallets";
				
			else:
			
				$node_file 		= fopen(dirname(__FILE__)."/wallets.txt", "w+");
				
				fwrite($node_file, $clean_list);
				fclose($node_file);
			
			endif; 
				
			
		
		break; 
		
	
	endswitch; 
		
endif; 
	


?>