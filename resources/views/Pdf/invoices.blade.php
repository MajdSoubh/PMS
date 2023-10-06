<!DOCTYPE html>
<html>
<head>
	<title>Invoices Table</title>
</head>
<style>
    .table-header{
        margin:1rem auto;
        width:max-content;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        text-align: left;
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #4CAF50;
        color: white;
    }
    tr:hover {
        background-color: #f5f5f5;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .company-name {
        font-size: 24px;
        font-weight: bold;
    }
</style>
<body>
<div class="header">
    <div class="company-name">PMS</div>
    <div class="date">Current Date: {{ date('F j, Y') }}</div>
</div>
	<h1 class="table-header">Inovices Table</h1>
    @if(!count($invoices))
    <h2 class="no-data">Sorry, no data exists yet</h2>
    @else
	<table>
        <thead>
			<tr>
				<th>#</th>
				<th>Total</th>
				<th>Discount</th>
				<th>Net</th>
				<th>Paid</th>
				<th>Rest</th>
				<th>Customer</th>
				<th>items</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
            @foreach ($invoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->total }}</td>
                <td>{{ $invoice->totalDiscount }}</td>
                <td>{{ $invoice->totalNet }}</td>
                <td>{{ $invoice->paid }}</td>
                <td>{{ $invoice->rest }}</td>
                <td>{{ $invoice->customer->name }}</td>
                <td>
                @foreach ($invoice->invoiceItems as $item)

                    {{ $item->drug->name }},
                    @endforeach
                </td>
                <td>{{ $invoice->date }}</td>
            </tr>
			@endforeach
		</tbody>
	</table>
    @endif
</body>
</html>
