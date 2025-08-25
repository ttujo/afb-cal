jQuery(document).ready(function($) {
    $('#excel-import-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('action', 'import_excel_data');

        $.ajax({
            url: ajaxurl, // WordPress admin AJAX
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#import-result').html('<div class="alert alert-info">Importing data, please wait...</div>');
            },
            success: function(response) {
                if (response.success) {
                    $('#import-result').html('<div class="alert alert-success">' + response.data.message + '</div>');
                } else {
                    $('#import-result').html('<div class="alert alert-danger">' + response.data.message + '</div>');
                }
            },
            error: function() {
                $('#import-result').html('<div class="alert alert-danger">An error occurred during import.</div>');
            }
        });
    });
});
