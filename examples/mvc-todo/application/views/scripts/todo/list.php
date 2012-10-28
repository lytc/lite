<?= $this->yield('title', function() { ?>
Todo::list
<? })?>

<h1>Todo List</h1>

<a href="/todo/new">New</a>
<? if ($todos): ?>
    <table>
        <thead>
        <tr>
            <th style="width: 200px; text-align: left;">Title</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($todos as $id => $todo): ?>
        <tr>
            <td><?= $todo['title'] ?></td>
            <td><a href="/todo/<?= $id ?>/edit">Edit</a> | <a href="/todo/<?= $id ?>" data-method="DELETE">Delete</a></td>
        </tr>
        <? endforeach ?>
        </tbody>
    </table>
<? else: ?>
    <p>No items to display...</p>
<? endif ?>