<?php
if (!defined('ABSPATH')) exit;

// جلب السنوات الأكاديمية والفصول من قاعدة البيانات
$academic_years = get_terms([
    'taxonomy' => 'academic_year',
    'hide_empty' => false,
]);

$semesters = get_terms([
    'taxonomy' => 'semester',
    'hide_empty' => false,
]);
?>

<div class="afb-calendar-shortcode-container">
    <div class="afb-calendar-filters row g-3">
        <div class="col-md-6">
            <select id="academic-year" class="form-select">
                <option value=""><?php _e('Select Academic Year', 'afb-calendar'); ?></option>
                <?php foreach ($academic_years as $year): ?>
                    <option value="<?php echo esc_attr($year->term_id); ?>"><?php echo esc_html($year->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <select id="semester" class="form-select" disabled>
                <option value=""><?php _e('Select Semester', 'afb-calendar'); ?></option>
                <?php foreach ($semesters as $semester): ?>
                    <option value="<?php echo esc_attr($semester->term_id); ?>"><?php echo esc_html($semester->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="table-responsive mt-3">
        <table id="calendar-table" class="afb-calendar-table">
            <thead>
                <tr>
                    <th><?php _e('Date', 'afb-calendar'); ?></th>
                    <th><?php _e('Activity (EN)', 'afb-calendar'); ?></th>
                    <th><?php _e('Activity (AR)', 'afb-calendar'); ?></th>
                    <th><?php _e('Date (AR)', 'afb-calendar'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <?php _e('Please select an academic year and semester to view the calendar', 'afb-calendar'); ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
