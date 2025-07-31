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

        h3 {
            font-size: 15px;
        }
    </style>
</head>

<body>
    <h3>Validación de Compra por Pago Link</h3>
    <p style="font-size: 14px">Fecha: {{ now() }}</p>
    <p style="font-size: 12px">Usuario: {{ $user }}</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Combo</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['combo'] }}</td>
                    <td>{{ $row['descripcion'] }}</td>
                    <td>{{ $row['cantidad'] }}</td>
                    <td>{{ number_format($row['subtotal'], 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="4" style="text-align: right;">TOTAL</th>
                <th>S/. {{ number_format($total, 2) }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
