<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
    <style>
        @page {
            margin-left: 23px;
        }

        * {
            font-family: "century gothic";
        }

        table {
            margin: 1px;
            font-size: 12px;
            border-collapse: collapse;
            width: 100%;
        }

        thead tr td {
            background-color: #00BCD4;
            text-align: center;
            padding: 3px;
            color: white;
        }

        thead {
            border-bottom: 1px solid #000;
            border-style: dotted;
        }

        tbody tr td {
            padding: 1em;
            text-align: center;
        }

        .combo-cell {
            text-align: center;
        }
    </style>
</head>
@php
    use Carbon\Carbon;
@endphp
<body>
    <h4>Validación Cupón</h4>
    <p>Fecha: {{ Carbon::parse($coupon->txt_foto)->format('d/m/Y H:i:s') }}</p>
    <table border="1">
        <thead>
            <tr>
                <th>Cod.</th>
                <th>Producto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="combo-cell">{{ $coupon->int_retoque }}</td>
                <td>{{ $coupon->txt_motivo }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
