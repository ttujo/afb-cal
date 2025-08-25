<div class="wrap">
    <h1><?php _e('AFB Calendar Settings', 'afb-calendar'); ?></h1>
    
    <div class="card">
        <div class="card-header">
            <h2><?php _e('Manage Academic Years and Semesters', 'afb-calendar'); ?></h2>
        </div>
        <div class="card-body">
            <p><?php _e('Use the links below to manage academic years and semesters:', 'afb-calendar'); ?></p>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="d-grid gap-2">
                        <a href="<?php echo admin_url('edit-tags.php?taxonomy=academic_year'); ?>" class="btn btn-primary btn-lg">
                            <?php _e('Manage Academic Years', 'afb-calendar'); ?>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-grid gap-2">
                        <a href="<?php echo admin_url('edit-tags.php?taxonomy=semester'); ?>" class="btn btn-primary btn-lg">
                            <?php _e('Manage Semesters', 'afb-calendar'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h2><?php _e('Import Calendar Data', 'afb-calendar'); ?></h2>
        </div>
        <div class="card-body">
            <p><?php _e('To import calendar data from an Excel file, use the import tool:', 'afb-calendar'); ?></p>
            
            <div class="d-grid gap-2 col-md-4 mx-auto mt-4">
                <a href="<?php echo admin_url('admin.php?page=afb-calendar-import'); ?>" class="btn btn-success btn-lg">
                    <?php _e('Go to Import Tool', 'afb-calendar'); ?>
                </a>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h2><?php _e('Shortcode', 'afb-calendar'); ?></h2>
        </div>
        <div class="card-body">
            <p><?php _e('Use the following shortcode to display the calendar on any page or post:', 'afb-calendar'); ?></p>
            
            <div class="input-group mb-3">
                <input type="text" class="form-control" value="[afb-ttu-cal]" readonly>
                <button class="btn btn-outline-secondary" type="button" id="copy-shortcode">
                    <?php _e('Copy', 'afb-calendar'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#copy-shortcode').on('click', function() {
        var shortcodeInput = $('input[value="[afb-ttu-cal]"]');
        shortcodeInput.select();
        document.execCommand('copy');
        
        var originalText = $(this).text();
        $(this).text('Copied!');
        
        setTimeout(function() {
            $('#copy-shortcode').text(originalText);
        }, 2000);
    });
});
</script>