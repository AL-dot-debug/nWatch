$(document).ready(function() {
	
	
	// Node table management 
	
	var table = $('#nodes').DataTable({
		"paging":   false,
		"responsive": true,
		"ajax": 'ajax.php',
		
		"aoColumnDefs": [ {
			"aTargets": [2],
			"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
				if ( sData.indexOf('WAIT FOR SYNCING') > -1 ) {
					$(nTd).addClass('bg-warning');
				}
				else if( sData.indexOf('SYNC STARTED') > -1 ){
					$(nTd).addClass('bg-start');
				}
				else if( sData.indexOf('SYNC FINISHED') > -1 ){
					$(nTd).addClass('bg-success');	
				}
				else if( sData.indexOf('PERSIST FINISHED') > -1 ){
					$(nTd).addClass('bg-success');	
				}
				else if( sData.indexOf('OFFLINE') > -1 ){
					$(nTd).addClass('bg-alert');	
				}
				else{
					$(nTd).addClass('bg-alert');	
				}
			}
		} ]
		
	});
	
	// Refresh every minutes 
	setInterval( function () {				
		table.ajax.reload();
		refresh_value('ext_stats','ext'); 
	}, 60000 );
	
	// To differ the requests from the table 
	setInterval( function () {				
		refresh_value('nodes_stats','nodes'); 
	}, 50000 );
	
	
	
	
	// Refresh dashboard values 
	function refresh_value(DashElement,UpdateType){
		
		$.ajax({
			url:"ajax.php",
			method:"POST",
			data:{form_type:DashElement},
			success:function(data){
				
				data = $.parseJSON(data);
				
				switch(UpdateType) {
					
					case 'nodes':
						
						// Update the graph 
						//node_graph_create(data['stats']);
						
						
						// Update the dash numbers 
						$('.nodes-stats').each(function() {
							
							const prop 	= $(this).data("prop"); 
							const Start = $(this).text().replace(/\s/g,'').replace(/,/g,'');
								
							$(this).prop('Counter', Start).animate(
								{
									Counter: data[prop]
								}, {
									duration: 10000,
									easing: 'swing',
									step: function(now) {
										$(this).text(toLocale(Math.ceil(now)));
									}
								}
							);
								
						
						});
						
					break; 
					
					case 'ext':
						
						$('.ext-stats').each(function() {
							
							const prop 	= $(this).data("prop"); 
							const Start = $(this).text().replace(/\s/g,'').replace(/,/g,'');
							
							$(this).prop('Counter', Start).animate(
								{
									Counter: data[prop]
								}, {
									duration: 60000,
									easing: 'swing',
									step: function(now) {
										$(this).text(toLocale(Math.ceil(now)));
									}
								}
							);
								
						});
						
					break; 
					
				}

				
			}
		});
		
	}
	
	// Nice display 
	function toLocale(x) {
		
		var locale = readCookie('nW_locale'); 
		
		if(locale){
			return x.toLocaleString(locale);
		}else{
			return x.toLocaleString('FR-fr');
		}
		
	}
	
	function readCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
		}
		return null;
	}
	
	
	
	
	// Create Graph 
	// function node_graph_create(GraphValues){
	// 	
	// 	const data = {
	// 		  labels: [
	// 			'ERROR',
	// 			'WAIT_FOR_SYNCING',
	// 			'SYNC_STARTED',
	// 			'SYNC_FINISHED',
	// 			'PERSIST_FINISHED',
	// 			'OFFLINE'
	// 		  ],
	// 		  datasets: [{
	// 			label: 'Nodes status',
	// 			data: GraphValues,
	// 			backgroundColor: [
	// 			  'rgb(243, 66, 19)',
	// 			  'rgb(244, 185, 66)',
	// 			  'rgb(111, 208, 140)',
	// 			  'rgb(111, 208, 140)',
	// 			  'rgb(172, 203, 225',
	// 			  'rgb(11, 22, 60)',
	// 			],
	// 			hoverOffset: 4
	// 		  }]
	// 		};
	// 	
	// 	$('#myChart').replaceWith('<canvas id="myChart"></canvas>');
	// 	
	// 	var ctx = document.getElementById('myChart');
	// 	var myChart = new Chart(ctx, {
	// 		type: 'doughnut',
	// 		data: data,
	// 		options: {
	// 			plugins: {
	// 				legend: {
	// 					display: false
	// 				}
	// 			},
	// 			layout: {
	// 				padding: 20
	// 			}
	// 		}
	// 	});
	// 	
	// 	
	// }
	
	
	// Activate BS tooltip 
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	  return new bootstrap.Tooltip(tooltipTriggerEl)
	})
	
} );