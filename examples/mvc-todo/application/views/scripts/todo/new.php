<?= $this->yield('title', function() { ?>
Todo::new
<? })?>

<h1>Todo::new</h1>

<form action="/todo" method="POST">
    <label for="title">Title:</label><br>
    <input id="title" type="text" name="title"><br><br>
    <label for="description">Description:</label><br>
    <textarea id="description" name="description"></textarea><br>
    <input type="submit" value="Submit">
</form>