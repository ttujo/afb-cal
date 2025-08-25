<?php
/*
Plugin Name: AFB Calendar
Description: Academic calendar plugin for import and display.
Version: 1.0.9
Author: Ateyah Albdour
Text Domain: afb-calendar
*/

if (!defined('ABSPATH')) exit;

// تعريف الثوابت
define('AFB_CALENDAR_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('AFB_CALENDAR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AFB_CALENDAR_VERSION', '1.0.9');

// تحميل الملفات الأساسية
require_once AFB_CALENDAR_PLUGIN_PATH . 'includes/class-afb-calendar.php';
require_once AFB_CALENDAR_PLUGIN_PATH . 'includes/class-afb-excel-parser.php';

// تهيئة الإضافة
function afb_calendar_init() {
    $afb_calendar = new AFB_Calendar();
    $afb_calendar->init();
}
add_action('plugins_loaded', 'afb_calendar_init');

// Ajax: استيراد بيانات Excel
function afb_calendar_import_data() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Permission denied.']);
    }

    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'afb_calendar_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce.']);
    }

    if (!isset($_FILES['excel_file']) || empty($_FILES['excel_file']['tmp_name'])) {
        wp_send_json_error(['message' => 'No file uploaded.']);
    }

    $academic_year = isset($_POST['academic_year']) ? intval($_POST['academic_year']) : 0;
    $semester = isset($_POST['semester']) ? intval($_POST['semester']) : 0;

    if (!$academic_year || !$semester) {
        wp_send_json_error(['message' => 'Please select academic year and semester.']);
    }

    $file = $_FILES['excel_file']['tmp_name'];

    // تضمين مكتبات SimpleXLSX
    require_once AFB_CALENDAR_PLUGIN_PATH . 'lib/SimpleXLSX.php';
    require_once AFB_CALENDAR_PLUGIN_PATH . 'lib/SimpleXLSXEx.php';

    $result = AFB_Excel_Parser::parse_excel($file, $academic_year, $semester);

    if ($result['success']) {
        wp_send_json_success(['message' => sprintf('%d rows imported successfully.', $result['count'])]);
    } else {
        wp_send_json_error(['message' => $result['message']]);
    }
}
add_action('wp_ajax_import_excel_data', 'afb_calendar_import_data');

// Ajax: جلب بيانات التقويم للعرض
function afb_calendar_get_data() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'afb_calendar_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce']);
    }

    $year_id = isset($_POST['year_id']) ? intval($_POST['year_id']) : 0;
    $semester_id = isset($_POST['semester_id']) ? intval($_POST['semester_id']) : 0;

    if (!$year_id || !$semester_id) {
        wp_send_json_error(['message' => 'Invalid year or semester']);
    }

    $data = AFB_Excel_Parser::get_calendar_data($year_id, $semester_id);
    wp_send_json_success($data);
}
add_action('wp_ajax_get_calendar_data', 'afb_calendar_get_data');

// Shortcode: عرض التقويم
function afb_calendar_shortcode($atts) {
    ob_start();
    $academic_years = get_terms(['taxonomy' => 'academic_year', 'hide_empty' => false]);
    $semesters = get_terms(['taxonomy' => 'semester', 'hide_empty' => false]);

    include AFB_CALENDAR_PLUGIN_PATH . 'templates/calendar-shortcode.php';

    return ob_get_clean();
}
add_shortcode('afb_calendar', 'afb_calendar_shortcode');
