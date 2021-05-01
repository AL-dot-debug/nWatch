$(document).ready(function() {
	
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
	
	
	function refresh_value(DashElement,UpdateType){
		
		$.ajax({
			url:"ajax.php",
			method:"POST",
			data:{form_type:DashElement},
			success:function(data){
				
				data = $.parseJSON(data);
					
				switch(UpdateType) {
					
					case 'nodes':
						
						$('.nodes-stats').each(function() {
							
							const prop 	= $(this).data("prop"); 
							const Start = $(this).text().replace(/\s/g,'');
								
							$(this).prop('Counter', Start).animate(
								{
									Counter: data[prop]
								}, {
									duration: 10000,
									easing: 'swing',
									step: function(now) {
										$(this).text(toFrench(Math.ceil(now)));
									}
								}
							);
								
						
						});
						
					break; 
					
					case 'ext':
						
						$('.ext-stats').each(function() {
							
							const prop 	= $(this).data("prop"); 
							const Start = $(this).text().replace(/\s/g,'');
							
							$(this).prop('Counter', Start).animate(
								{
									Counter: data[prop]
								}, {
									duration: 60000,
									easing: 'swing',
									step: function(now) {
										$(this).text(toFrench(Math.ceil(now)));
									}
								}
							);
								
						});
						
					break; 
					
				}

				
			}
		});
		
	}
	
	function toFrench(x) {
		return x.toLocaleString('FR-fr');
	}
	
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	  return new bootstrap.Tooltip(tooltipTriggerEl)
	})
	
	$('.btn-nodecfg-js').click(function() {
		
		var datastring = $("#nodecfg").serialize();
		console.log(datastring);

		$.ajax({
			type: "POST",
			url: "ajax.php",
			data: datastring,
			success: function(data) {
				location.reload();
			}
		});
	
	});

} );


Chart.defaults.global.legend.display = false;

var chartOptions = {
  legend: {
	display: false 
  },
  elements: {
		arc: {
			borderWidth: 1,
			borderColor: 'rgba(255,255,255, 0.5)'
		}
	}
};


// const data = {
//   labels: [
// 	'ERROR',
// 	'WAIT_FOR_SYNCING',
// 	'SYNC_STARTED',
// 	'SYNC_FINISHED',
// 	'PERSIST_FINISHED',
// 	'OFFLINE'
//   ],
//   datasets: [{
// 	label: 'My First Dataset',
// 	data: [<?= implode(', ', $nodes['stats'] ) ?>],
// 	backgroundColor: [
// 	  'rgb(243, 66, 19, 0.75)',
// 	  'rgb(244, 185, 66, 0.75)',
// 	  'rgb(111, 208, 140, 0.75)',
// 	  'rgb(111, 208, 140, 0.75)',
// 	  'rgb(172, 203, 225, 0.75)',
// 	  'rgb(255, 255, 255, 0.75)',
// 	],
// 	hoverOffset: 4
//   }]
// };
// 
// const config = {
//   type: 'pie',
//   data: data,
//   options: chartOptions
// };
// 
// var myChart = new Chart(
// document.getElementById('myChart'),
// config
// );
