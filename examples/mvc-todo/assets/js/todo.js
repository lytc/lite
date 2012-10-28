$(function() {
    $('a[data-method=DELETE]').click(function(e) {
        e.preventDefault();

        if (!confirm('Are you sure?')) {
            return;
        }

        var form = $('<form method="POST"></form>')
        form.attr('action', $(this).attr('href'));
        form.append('<input type="hidden" name="__METHOD__" value="DELETE">');
        form.submit();
    })
})