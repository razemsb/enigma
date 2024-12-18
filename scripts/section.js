$(document).ready(function() {
    $('.dropdown-menu a').each(function() {
        if ($(this).attr('href') == 'admin.php?section=' + '<?= $section ?>') {
            $(this).parent().addClass('active');
        }
    });
});