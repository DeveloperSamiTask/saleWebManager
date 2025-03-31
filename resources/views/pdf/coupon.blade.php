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
    </style>
</head>

<body>
    <table style="background: url('{{ $imagePath }}'); background-repeat:no-repeat; height:365px;">
        <tr>
            <td style="width:25px;">&nbsp;</td>
            <td style="text-align:left;">
                <img src="{{ $barcodeBase64 }}" class="barcode" alt="Código de Barras">
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
    </table>
    <div>{!! $content !!}</div>
</body>

</html>
