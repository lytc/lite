<?= $this->yield('title', function() { ?>
    Todo::edit
<? })?>

<h1>Todo::edit</h1>
<form action="/todo/<?= $todo['id'] ?>" method="POST">
    <input type="hidden" name="__METHOD__" value="PUT">
    <label for="title">Title:</label><br>
    <input id="title" type="text" name="title" value="<?= $todo['title'] ?>"><br><br>
    <label for="description">Description:</label><br>
    <textarea id="description" name="description"><?= $todo['description'] ?></textarea><br>
    <input type="submit" value="Submit">
</form>