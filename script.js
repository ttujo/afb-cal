jQuery(document).ready(function($) {
    // عند تغيير العام الدراسي
    $('#academic-year').on('change', function() {
        var yearId = $(this).val();
        
        if (yearId) {
            $('#semester').prop('disabled', false);
            loadCalendarData(yearId, $('#semester').val());
        } else {
            $('#semester').prop('disabled', true);
            clearCalendarTable();
        }
    });
    
    // عند تغيير الفصل الدراسي
    $('#semester').on('change', function() {
        var yearId = $('#academic-year').val();
        var semesterId = $(this).val();
        
        if (yearId && semesterId) {
            loadCalendarData(yearId, semesterId);
        } else {
            clearCalendarTable();
        }
    });
    
    // تحميل بيانات التقويم
    function loadCalendarData(yearId, semesterId) {
        $('.calendar-body').addClass('loading');
        
        $.ajax({
            url: afb_calendar_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_calendar_data',
                year_id: yearId,
                semester_id: semesterId,
                nonce: afb_calendar_ajax.nonce
            },
            success: function(response) {
                $('.calendar-body').removeClass('loading');
                
                if (response.success) {
                    updateCalendarTable(response.data);
                } else {
                    alert('Error loading calendar data: ' + response.data.message);
                }
            },
            error: function() {
                $('.calendar-body').removeClass('loading');
                alert('Error loading calendar data');
            }
        });
    }
    
    // تحديث جدول التقويم
    function updateCalendarTable(data) {
        var tableBody = $('#calendar-table tbody');
        tableBody.empty();
        
        if (data.length === 0) {
            tableBody.append(
                '<tr><td colspan="4" class="text-center py-5">' + 
                'No calendar data available for the selected year and semester' + 
                '</td></tr>'
            );
            return;
        }
        
        $.each(data, function(index, row) {
            var tableRow = $('<tr></tr>');
            
            $.each(row, function(colIndex, cell) {
                tableRow.append($('<td></td>').text(cell));
            });
            
            tableBody.append(tableRow);
        });
    }
    
    // مسح جدول التقويم
    function clearCalendarTable() {
        var tableBody = $('#calendar-table tbody');
        tableBody.empty();
        tableBody.append(
            '<tr><td colspan="4" class="text-center py-5">' + 
            'Please select an academic year and semester to view the calendar' + 
            '</td></tr>'
        );
    }
});