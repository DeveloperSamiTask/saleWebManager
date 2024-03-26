<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Ticket</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .ticket-container {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="ticket-container">
        <img src="{{$image}}" alt="QR Code">
        <h2>{{ $code }}</h2>
    </div>
</body>

</html>
