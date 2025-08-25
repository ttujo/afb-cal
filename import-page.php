<?php if (!defined('ABSPATH')) exit; ?>

<div class="afb-calendar-container">
    <h2 class="calendar-header"><?php _e('Import Academic Calendar', 'afb-calendar'); ?></h2>

    <form id="excel-import-form" enctype="multipart/form-data">
        <?php wp_nonce_field('afb_calendar_nonce', 'security'); ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="academic_year"><?php _e('Academic Year', 'afb-calendar'); ?></label>
                <select name="academic_year" id="academic_year" class="form-select" required>
                    <option value=""><?php _e('Select Year', 'afb-calendar'); ?></option>
                    <?php
                    $years = get_terms(array(
                        'taxonomy' => 'academic_year',
                        'hide_empty' => false,
                    ));
                    if (!is_wp_error($years)) {
                        foreach ($years as $year) {
                            echo '<option value="' . esc_attr($year->term_id) . '">' . esc_html($year->name) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="semester"><?php _e('Semester', 'afb-calendar'); ?></label>
                <select name="semester" id="semester" class="form-select" required>
                    <option value=""><?php _e('Select Semester', 'afb-calendar'); ?></option>
                    <?php
                    $semesters = get_terms(array(
                        'taxonomy' => 'semester',
                        'hide_empty' => false,
                    ));
                    if (!is_wp_error($semesters)) {
                        foreach ($semesters as $semester) {
                            echo '<option value="' . esc_attr($semester->term_id) . '">' . esc_html($semester->name) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-12">
                <input type="file" name="excel_file" required accept=".xlsx,.xls">
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-12">
                <button type="submit"><?php _e('Import Data', 'afb-calendar'); ?></button>
            </div>
        </div>
    </form>

    <div id="import-result" class="mt-3"></div>

    <!-- عرض التقويم -->
    <div class="calendar-body mt-4">
        <table id="calendar-table" class="afb-calendar-table table-responsive">
            <thead>
                <tr>
                    <th><?php _e('Date (EN)', 'afb-calendar'); ?></th>
                    <th><?php _e('Activity (EN)', 'afb-calendar'); ?></th>
                    <th><?php _e('Date (AR)', 'afb-calendar'); ?></th>
                    <th><?php _e('Activity (AR)', 'afb-calendar'); ?></th>
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
