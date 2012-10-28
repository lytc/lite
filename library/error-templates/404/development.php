<!DOCTYPE html>
<html>
<head>
    <title>404 Page Not Found</title>
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
<h1>404 Page Not Found</h1>
<p>The page you are looking for could not be found. Check the address bar to ensure your URL is spelled correctly.</p>
<h3>Try this:</h3>
<pre>
$app-><?= strtolower($this->getRequest()->getMethod()) ?>('<?= $this->getUri() ?>', function() {
    echo 'Hello World!';
});</pre>
</body>
</html>