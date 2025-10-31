<!DOCTYPE html>
<html>

<head>
    <style>
        * {
            font-family: "century gothic";
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            border-bottom: 1px solid #000;
            border-style: dotted;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        td {
            font-size: 10px;
        }

        h3 {
            font-size: 15px;
        }
    </style>
</head>

<body>
    <h3>Validación de Ventas Web - FDT</h3>
    <p style="font-size: 14px">Codigo Reserva: {{$code}}</p>
    <p style="font-size: 14px">Fecha: {{ now()->format('d/m/Y H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Producto</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody border="1">
            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['id'] }}</td>
                    <td>{{ $row['producto'] }}</td>
                    <td>S/. {{ number_format($row['precio'], 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3" style="text-align: right;">TOTAL</th>
                <th>S/. {{ number_format($total, 2) }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
