<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>La Granja Villa</title>
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
    </style>
</head>

<body>
    @php
        $barcodeUrl =
            '<img src="http://generator.barcodetools.com/barcode.png?gen=0&data=CI-25032025-001&bcolor=FFFFFF&fcolor=000000&tcolor=000000&fh=14&bred=0&w2n=2.5&xdim=2&w=70px&h=220px&debug=1&btype=7&angle=90&quiet=1&balign=2&talign=0&guarg=1&text=1&tdown=1&stst=1&schk=0&cchk=1&ntxt=1&c128=0">';
    @endphp
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
            <td>{{$client->father_surname . " " .$client->mother_surname . " " . $client->names}}</td>
        </tr>
        <tr>
            <td align="left" style="font-size:15px;"><b>DNI:</b> </td>
            <td>{{$client->number_doc}}</td>
        </tr>
    </table>
</body>

</html>
