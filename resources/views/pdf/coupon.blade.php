<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>CUPON CUMPLEAÑOS</title>
    <style>
        body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            background-color: #fff;
        }
        table {
            width: 100%;
        }
        p {
            width: auto;
            height: auto;
            transform: rotate(270deg);
        }
        ol,
        li {
            line-height: 1;
            margin-bottom: 20px;
        }
        /* Estilos para rotar el cﾃｳdigo de barras */
        .barcode-container {
            display: inline-block;
            margin-top: 0.8rem;
            margin-left: 1rem;
        }
        .barcode {

            width: 80px;
            height: 240px;
        }
    </style>
</head>
<body>
    <table style="background: url('{{ $imagePath }}'); background-repeat:no-repeat; height:365px;">
        <tr>
            <td>
                <div class="barcode-container">
                    <img src="{{ $barcodeBase64 }}" class="barcode" alt="Código de Barras">
                </div>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td align="left" style="font-size:15px;"><b>NOMBRE:</b></td>
            <td>{{ $client->father_surname . ' ' . $client->mother_surname . ' ' . $client->names }}</td>
        </tr>
        <tr>
            <td align="left" style="font-size:15px;"><b>DNI:</b> </td>
            <td>{{ $client->number_doc }}</td>
        </tr>
        <tr>
            <td align="left" style="font-size:15px;"><b>FECHA EXPIRACION:</b> </td>
            <td>{{ $client->expired_date }}</td>
        </tr>
    </table>
    <div>{!! $content !!}</div>
</body>
</html>
