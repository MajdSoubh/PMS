<!DOCTYPE html>
<html>
<head>
	<title>Suppliers Table</title>
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
	<h1 class="table-header">Suppliers Table</h1>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Contact</th>
				<th>Email</th>
				<th>Address</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($suppliers as $supplier)
				<tr>
					<td>{{ $supplier->id }}</td>
					<td>{{ $supplier->name }}</td>
					<td>{{ $supplier->contact }}</td>
					<td>{{ $supplier->email }}</td>
					<td>{{ $supplier->address }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
