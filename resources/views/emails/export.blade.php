<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    Your export is ready to be downloaded, simply visit this url.
    <p><a href="{{ route('hub.export.download', $hash) }}">Download CSV</a></p>
    <strong>This url will only work once</strong>
</body>
</html>