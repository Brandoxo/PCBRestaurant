<!doctype html>
<html>
<head><meta charset="utf-8">
<style>
body{font-family: DejaVu Sans;}
table{width:100%;border-collapse:collapse;}
td,th{border:1px solid #ccc;padding:6px}
</style>
</head>
<body>
<h2>{{ config('app.name') }}</h2>
<p>Factura #{{ $sale->id }} — {{ $sale->created_at->format('d/m/Y H:i') }}</p>
<table>
<thead><tr><th>Producto</th><th>Cant.</th><th>Precio</th><th>Total</th></tr></thead>
<tbody>
@foreach($sale->order->items as $it)
<tr>
<td>{{ $it->product->name }}</td>
<td>{{ $it->qty }}</td>
<td>{{ number_format($it->unit_price,2) }}</td>
<td>{{ number_format($it->total,2) }}</td>
</tr>
@endforeach
</tbody>
</table>
<p style="text-align:right">
Subtotal: {{ number_format($sale->subtotal,2) }}<br>
IVA: {{ number_format($sale->tax,2) }}<br>
Total: <strong>{{ number_format($sale->total,2) }}</strong>
</p>
</body>
</html>