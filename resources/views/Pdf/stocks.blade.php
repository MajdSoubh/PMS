<!DOCTYPE html>
<html>
<head>
	<title>Stock Table</title>
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
	<h1 class="table-header">Stock Table</h1>
    @if(!count($stocks))
    <h2 class="no-data">Sorry, no data exists yet</h2>
    @else
	<table>
        <thead>
			<tr>
				<th>#</th>
				<th>MFD</th>
				<th>EXP</th>
				<th>Drug</th>
				<th>QTY</th>
				<th>Supplier</th>
			</tr>
		</thead>
		<tbody>
            @foreach ($stocks as $stock)
            <tr>
                <td>{{ $stock->id }}</td>
                <td>{{ $stock->mfd }}</td>
                <td>{{ $stock->exp }}</td>
                <td>{{ $stock->drug->name }}</td>
                <td>{{ $stock->qty }}</td>
                <td>{{ $stock->supplier->name }}</td>
            </tr>
			@endforeach
		</tbody>
	</table>
    @endif
</body>
</html>
