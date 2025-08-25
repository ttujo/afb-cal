<?php
// الحصول على اللغة الحالية (فقط العربية والإنجليزية)
$current_lang = get_locale();
if (!in_array($current_lang, array('ar', 'en_US'))) {
    $current_lang = 'en_US'; // افتراضي إلى الإنجليزية إذا كانت لغة أخرى
}
$is_rtl = ($current_lang === 'ar');

// الحصول على السنوات الدراسية
$academic_years = get_terms(array(
    'taxonomy' => 'academic_year',
    'hide_empty' => false,
    'parent' => 0
));

// الحصول على الفصول الدراسية
$semesters = get_terms(array(
    'taxonomy' => 'semester',
    'hide_empty' => false
));
?>

<div class="afb-calendar-container <?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">
    <div class="calendar-header bg-primary text-white p-4 rounded-top">
        <h2 class="text-center mb-4">
            <?php echo $is_rtl ? __('التقويم الأكاديمي للجامعة', 'afb-calendar') : __('University Academic Calendar', 'afb-calendar'); ?>
        </h2>
        
        <div class="row g-3">
            <div class="col-md-6">
                <label for="academic-year" class="form-label">
                    <?php echo $is_rtl ? __('السنة الدراسية', 'afb-calendar') : __('Academic Year', 'afb-calendar'); ?>
                </label>
                <select class="form-select" id="academic-year">
                    <option value="">
                        <?php echo $is_rtl ? __('اختر السنة الدراسية', 'afb-calendar') : __('Select Academic Year', 'afb-calendar'); ?>
                    </option>
                    <?php foreach ($academic_years as $year) : ?>
                        <option value="<?php echo $year->term_id; ?>">
                            <?php echo $year->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6">
                <label for="semester" class="form-label">
                    <?php echo $is_rtl ? __('الفصل الدراسي', 'afb-calendar') : __('Semester', 'afb-calendar'); ?>
                </label>
                <select class="form-select" id="semester" disabled>
                    <option value="">
                        <?php echo $is_rtl ? __('اختر الفصل الدراسي', 'afb-calendar') : __('Select Semester', 'afb-calendar'); ?>
                    </option>
                    <?php foreach ($semesters as $semester) : ?>
                        <option value="<?php echo $semester->term_id; ?>">
                            <?php echo $semester->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    
    <div class="calendar-body bg-light p-4 rounded-bottom">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="calendar-table">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">
                            <?php echo $is_rtl ? __('الإجراء/النشاط', 'afb-calendar') : __('Procedure/Activity', 'afb-calendar'); ?>
                        </th>
                        <th scope="col">
                            <?php echo $is_rtl ? __('التاريخ', 'afb-calendar') : __('Date', 'afb-calendar'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'afb_calendar';

                    // جلب البيانات حسب اللغة الفعالة
                    if ($is_rtl) {
                        $rows = $wpdb->get_results("SELECT activity_ar, date_ar FROM $table_name ORDER BY academic_year_id, semester_id", ARRAY_A);
                    } else {
                        $rows = $wpdb->get_results("SELECT activity_en, date_en FROM $table_name ORDER BY academic_year_id, semester_id", ARRAY_A);
                    }

                    if (!empty($rows)) :
                        foreach ($rows as $row) :
                    ?>
                            <tr>
                                <td><?php echo esc_html($is_rtl ? $row['activity_ar'] : $row['activity_en']); ?></td>
                                <td><?php echo esc_html($is_rtl ? $row['date_ar'] : $row['date_en']); ?></td>
                            </tr>
                    <?php
                        endforeach;
                    else :
                    ?>
                        <tr>
                            <td colspan="2" class="text-center py-5">
                                <?php echo $is_rtl 
                                    ? __('الرجاء اختيار السنة الدراسية والفصل لعرض التقويم', 'afb-calendar') 
                                    : __('Please select an academic year and semester to view the calendar', 'afb-calendar'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
