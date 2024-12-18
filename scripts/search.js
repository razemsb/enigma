$(document).ready(function() {
    $('#search').on('input', function() {
        var query = $(this).val();
        if (query.length > 2) { 
            $.ajax({
                url: 'search.php',
                method: 'GET',
                data: { search: query },
                success: function(response) {
                    $('#results').html(response);
                }
            });
        } else {
            $('#results').empty(); 
        }
    });
});
