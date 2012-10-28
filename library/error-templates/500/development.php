<!DOCTYPE html>
<html>
<head>
    <title>Application Error</title>
    <style>
        body {
            font: 12px/1.5 Helvetica,Arial,Verdana,sans-serif;
            margin: 0;
            padding: 30px;
        }
        pre {
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #F8F8F8;
            padding: 10px;
        }
    </style>
</head>
<body>
<h1>Application Error</h1>
<p>The application could not run because of the following error:</p>
<h3>Details</h3>
<pre>
Code:       <?= $exception->getCode() ?>

Message:    <?= $exception->getMessage() ?>

File:       <?= $exception->getFile() ?>

Line:       <?= $exception->getLine() ?></pre>
<h3>Trace</h3>
<pre><?= $exception->getTraceAsString() ?></pre>
</body>
</html>