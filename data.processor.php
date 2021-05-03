<?php

if(isset($_POST['form_type']) AND !empty($_POST['form_type'])) : 

	switch($_POST['form_type']): 
	
		
		case 'nwatch':
	
			// Locate the old password file 
			$path    	= 'admin/';
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
		
		break; 
		
		case 'login':
	
			$path    	= 'admin/';
			$files 		= scandir($path);
			
			foreach($files as $file):
			
				if($file != '.' AND $file != '..'):
					
					$pwd = file_get_contents($path.''.$file); 
					
					if (password_verify($_POST['password'], $pwd)) : 
						
						$_SESSION['user'] = true; 
						header('Location:/'); 
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
			$node_file 		= fopen("nodes.txt", "w+");
			
			fwrite($node_file, $clean_list);
			fclose($node_file);
		
		break; 
		
		case 'wallets':
	
			$wallet_list 	= nl2br($_POST['walletstxt']); 
			$clean_list 	= strip_tags($wallet_list);			
			$node_file 		= fopen("wallets.txt", "w+");
			
			fwrite($node_file, $clean_list);
			fclose($node_file);
		
		break; 
		
	
	endswitch; 
		
endif; 
	


?>