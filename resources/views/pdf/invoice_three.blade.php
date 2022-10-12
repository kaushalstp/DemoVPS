<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" type="image/png" sizes="32x32" href="images/myemailverifier-favicon.png">

	<title>Best Email Address Verification Service Plans - MyEmailVerifier</title>
</head>

<style type="text/css">
@import url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800');

body{-webkit-font-smoothing:antialiased}
body{font-family: 'Open Sans', sans-serif;font-size:16px;font-weight:400;line-height:1.6;color:#4c5c71;margin: 0;padding: 0;background: #eaeff3;}

.container{max-width: 970px;margin: 0 auto;background: #fff;padding: 25px;}
.main-content{max-width: 1000px;margin: 0 auto;}

.img-fluid {max-width: 100%;height: auto;}
a{color: #004ea8;}
p{margin: 0 0 10px;}
.paid-img{float: right;margin: -25px;}
.myemailverifier-logo{margin: 45px 0px;width: 300px;}
.text-center{text-align: center;}

.invoice-cart p{font-weight: 600;margin: 0;}
.invoice-cart span{font-weight: 900;}

.second-row{display: inline-block;width: 100%;margin-top: 30px;}
.invoiced_to{width: 300px;float: left;}
.invoiced_to span{font-weight: bold;}
.mev-address{width: 300px;float: right;}

.table-responsive{display: block;width: 100%;overflow-x: auto;-webkit-overflow-scrolling: touch;}
.table {width: 100%;margin-bottom: 1rem;color: #212529;}
.table-bordered {border: 1px solid #dee2e6;}
.table thead th {vertical-align: bottom;border-bottom: 2px solid #dee2e6;}
.table-bordered td, .table-bordered th {border: 1px solid #dee2e6;}
.table td, .table th {padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;}




</style>

<body>

	
	
	<div class="container">

		<div style="top:-100px;" >
			<a href="{{ url('') }}" >			
			<!-- <img src="{{ public_path('img\mev-logo-2.png') }}" alt="MyEmailVerifier" title="MyEmailVerifier" class="myemailverifier-logo img-fluid">			 -->

			<img src="{{ url('public/img/mev-logo-2.png') }}" alt="MyEmailVerifier" title="MyEmailVerifier" class="myemailverifier-logo img-fluid">			

			</a>
		
			
			<!-- <img src="{{ public_path('img\paidlabel.png') }}" alt="" class="paid-img img-fluid" 
			draggable="false" /> -->

			<img src="{{ url('public/img/paidlabel.png') }}" alt="" class="paid-img img-fluid" 
			draggable="false" />

			<div class="invoice-cart">
				<p>Invoice No: <span> {{ $invoice['invoice_id'] }} </span></p>
				<p>Invoice Date: <span>{{ $invoice['created_at'] }}</span></p>
			</div>
			<div class="second-row">
				<div class="invoiced_to"> 
					<p>
						<span>Invoiced To</span><br>
						{{ $invoice['firstname'] }} {{ $invoice['lastname'] }}
						<a href="mailto:{{ $invoice['email'] }}"> {{ $invoice['email'] }}</a><br>					
					</p>
				</div>
				<div class="mev-address">
					<p>
						MyEmailVerifier.com<br>
						P.O. Box 12 Norwood , NJ 07648<br>
						United States of America<br>
						<a href="mailto:support@myemailverifier.com">support@myemailverifier.com</a>
					</p>
				</div>
			</div>

		</div>	

		<div class="table-responsive text-center">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th scope="col">Detail</th>
						<th scope="col">Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td scope="row"> Email Verification Credit – {{ $invoice['credits'] }} </td>
						<td>$ {{ $invoice['amount'] }} USD</td>
					</tr>
					<tr>
						<td scope="row">Total</td>
						<td>$ {{ $invoice['amount'] }} USD</td>
					</tr>
				</tbody>
			</table>
		</div>
		<p style="font-size: 18px;"><b> Transactions</b></p>
		<div class="table-responsive text-center">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th scope="col">Transaction Date</th>
						<th scope="col">Gateway</th>
						<th scope="col">Transaction ID</th>
						<th scope="col">Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{ $invoice['created_at'] }}</td>
						<td>PayPal</td>
						<td>{{ $invoice['invoice_number'] }}</td>
						<td>${{ $invoice['amount'] }} USD</td>
					</tr>
					<tr>
						<th scope="row" colspan="3">Balance</th>
						<th>$0.00 USD</th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>