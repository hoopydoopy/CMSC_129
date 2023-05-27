function showPassword() {
	
	var password1 = document.getElementById("password1");
	var password2 = document.getElementById("password2");
	if (password1.type === "password") {
		password1.type = "text";
		password2.type = "text";
	}
	else {
		password1.type = "password";
		password2.type = "password";
	}
}

function getCurrentDate() {
	
	var date = new Date();

	var day = date.getDate();
	var month = date.getMonth() + 1;
	var year = date.getFullYear();

	if (month < 10)
		month = "0" + month;
	if (day < 10)
		day = "0" + day;

	var today = year + "-" + month + "-" + day;
	
	document.getElementById("dateInput").value = today;

	var startOfWeek = new Date(date.getTime() - (date.getDay() === 0 ? 6 : date.getDay() - 1) * 24 * 60 * 60 * 1000);
  	var endOfWeek = new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000);

 	var startOfWeekFormatted = startOfWeek.getFullYear() + "-" + (startOfWeek.getMonth() + 1) + "-" + startOfWeek.getDate();
  	var endOfWeekFormatted = endOfWeek.getFullYear() + "-" + (endOfWeek.getMonth() + 1) + "-" + endOfWeek.getDate();
	document.getElementById("startOfWeekInput").value = startOfWeekFormatted;
	document.getElementById("endOfWeekInput").value = endOfWeekFormatted;


}


function drawChart(incomes, expenses) {
	google.charts.load('current', {'packages':['corechart']});
		
	if(Array.isArray(incomes) && incomes.length) {
		google.charts.setOnLoadCallback(drawIncomesChart);
	}
				
	if(Array.isArray(expenses) && expenses.length) {
		google.charts.setOnLoadCallback(drawExpensesChart);
	}
			
	function drawIncomesChart() {
				
		var incomesData = new google.visualization.DataTable();
		incomesData.addColumn('string', 'Category');
		incomesData.addColumn('number', 'Amount');

		for (var i = 0; i < incomes.length; i++) {
			incomesData.addRow([incomes[i].income_category, parseFloat(incomes[i].income_amount)]);
		}
				
		var incomesOptions = {
			title: 'Expense Distribution',
			//colors: ['#00e64d', '#66ff99', '#b3ffcc'],
			//colors: ['#370617', '#6a040f', '#9d0208'],
			colors: ['#C8EB9B', '#5E807F  ', '#D3C1C3 ','#082D0F '],
			backgroundColor: { fill:'transparent' },
			chartArea:{top:30,bottom:10,width:'100%',height:'100%'},
			fontSize: 16
		};

		var incomesChart = new google.visualization.PieChart(document.getElementById('piechart1'));
		incomesChart.draw(incomesData, incomesOptions);
	}
			
	function drawExpensesChart() {

		var expensesData = new google.visualization.DataTable();
		expensesData.addColumn('string', 'Category');	
		expensesData.addColumn('number', 'Amount');

		var expensesOptions = {
			title: 'Income Distribution',
			//colors: ['#ff3333', '#ff6666', '#ffb3b3'],
			//colors: ['#370617', '#6a040f', '#9d0208','#dc2f02','#e85d04'],
			colors: ['#7D6FBB', '#F2BEFC', '#669BBC','#E4B7E5'],
			backgroundColor: { fill:'transparent' },
			chartArea:{top:30,bottom:10,width:'100%',height:'100%'},
			fontSize: 16
		};
				
		for (var i = 0; i < expenses.length; i++) {
			expensesData.addRow([expenses[i].expense_category, parseFloat(expenses[i].expense_amount)]);
		}

		var expensesChart = new google.visualization.PieChart(document.getElementById('piechart2'));
		expensesChart.draw(expensesData, expensesOptions);
	}
}