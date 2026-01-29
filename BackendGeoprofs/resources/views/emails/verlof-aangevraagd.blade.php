<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nieuwe verlofaanvraag</title>
</head>
<body>
<h2>Nieuwe verlofaanvraag</h2>

<p>
    <strong>{{ $gebruiker->name }}</strong> heeft een verlofaanvraag ingediend.
</p>

<p>
    <strong>Periode:</strong><br>
    Van {{ $aanvraag->startdatum }}<br>
    Tot {{ $aanvraag->einddatum }}
</p>

<p>
    <strong>Reden:</strong><br>
    {{ $aanvraag->reden }}
</p>

<p>
    Met vriendelijke groet,<br>
    <strong>GeoProfs</strong>
</p>
</body>
</html>
