<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante</title>
    <style>
        * {
            font-family: "Century Gothic", sans-serif;
            font-size: 10px;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            width: 50mm;
        }

        h3 {
            text-align: center;
            font-size: 13px;
            margin: 5px 0;
        }

        p {
            margin: 2px 10px;
        }

        .item {
            padding: 6px 10px;
            border-bottom: 1px dashed #000;
        }

        .label {
            font-weight: bold;
            display: block;
        }

        .total {
            padding: 10px;
            text-align: right;
            font-weight: bold;
            font-size: 12px;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            html,
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <h3>Validación Pago Link</h3>
    <p>Fecha: {{ now() }}</p>
    <p>Usuario: {{ $user }}</p>
    <p>Cliente: {{ $user }}</p>

    @php
        $lastCombo = null;
    @endphp

    @foreach ($data as $index => $row)


        <div class="item">
            <span class="label">{{ $row['combo'] }}</span><br>
            <span class="label">Descripción:</span><br>
            {!! nl2br(e(str_replace('+', "\n", $row['descripcion']))) !!}<br>
            <span class="label">Cantidad:</span> {{ $row['cantidad'] }}<br>
            <span class="label">Subtotal:</span> S/. {{ number_format($row['subtotal'], 2) }}
        </div>

        @php
            $lastCombo = $row['combo'];
        @endphp
    @endforeach

    <div class="total">TOTAL: S/. {{ number_format($total, 2) }}</div>
</body>

</html>
