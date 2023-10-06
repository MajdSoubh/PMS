<!DOCTYPE html>
<html>
<head>
	<title>Purchases Table</title>
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
	<h1 class="table-header">Purchases Table</h1>
    @if(!count($purchases))
    <h2 class="no-data">Sorry, no data exists yet</h2>
    @else
	<table>
        <thead>
			<tr>
				<th>#</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Rest</th>
				<th>supplier</th>
				<th>items</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
            @foreach ($purchases as $purchase)
            <tr>
                <td>{{ $purchase->id }}</td>
                <td>{{ $purchase->total }}</td>
                <td>{{ $purchase->paid }}</td>
                <td>{{ $purchase->rest }}</td>
                <td>{{ $purchase->supplier?$purchase->supplier->name:"No Supplier" }}</td>
                <td>
                @foreach ($purchase->purchaseItems as $item)

                    {{ $item->drug->name }},
                    @endforeach
                </td>
                <td>{{ $purchase->date }}</td>
            </tr>
			@endforeach
		</tbody>
	</table>
    @endif
</body>
</html>
