
function copyToClipboard(element) {
	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val($(element).text()).select();
	document.execCommand("copy");
	$temp.remove();
}


const DATA_COUNT = 7;
const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

const labels = $('.jsweek').data('values');
const data = {
  labels: labels,
  datasets: [
	{
	  label: 'Rewards',
	  data: $('.jsrewards').data('values'),
	  borderColor: '#6FD08C',
	  backgroundColor: 'transparent',
	}
  ]
};

const config = {
	  type: 'line',
	  data: data,
	  options: {
		responsive: true,
		plugins: {
		  legend: {
			display: false
		  },
		  title: {
			display: true,
			text: 'Reward distribution per week'
		  }
		}
	  },
	};
	
$('#myChart').replaceWith('<canvas id="myChart"></canvas>');
var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, config); 	

// var BSModal = document.getElementById('wallet')
// 
// BSModal.addEventListener('show.bs.modal', function (event) {
// 	var button 		= event.relatedTarget
// 	var Wallet 		= button.getAttribute('data-bs-id')
// 	var modalTitle 	= BSModal.querySelector('.modal-title')
// 	var modalBody 	= BSModal.querySelector('.modal-body')
// 	
// 	var time_stamp 	= new Date().getTime();
// 	var myurl 		= "ajax.php?timestamp=" + time_stamp;
// 	
// 	$.ajax({
// 		url:myurl,
// 		method:"POST",
// 		data:{form_type:'wallet_transactions', wallet: Wallet},
// 		success:function(data){
// 			
// 			modalTitle.textContent 	= 'Wallet ' + Wallet
// 			$(modalBody).html(data); 
// 			
// 		}
// 	});
// 
// })