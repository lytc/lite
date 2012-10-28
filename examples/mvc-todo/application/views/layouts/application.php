<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $this->yield('title') ?></title>
    <?= $this->link() ?>
</head>
<body>
<? foreach ($this->messages as $type => $messages): ?>
    <ul>
        <li>
            <?= $type ?>
            <ul>
                <? foreach ($messages as $message): ?>
                <li><?= $message ?></li>
                <? endforeach ?>
            </ul>
        </li>
    </ul>
<? endforeach ?>

<?= $this->content() ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<?= $this->script() ?>
<script src="/assets/js/todo.js"></script>
</body>
</html>