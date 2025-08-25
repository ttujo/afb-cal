<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once AFB_CALENDAR_PLUGIN_PATH . 'lib/SimpleXLSX.php';
require_once AFB_CALENDAR_PLUGIN_PATH . 'lib/SimpleXLSXEx.php';

use Shuchkin\SimpleXLSX;
use Shuchkin\SimpleXLSXEx;

class AFB_Excel_Parser {

    public static function parse_excel($file_path, $academic_year, $semester) {

        if (!file_exists($file_path)) {
            return [
                'success' => false,
                'message' => 'File does not exist.'
            ];
        }

        // قراءة الملف
        $xlsx = SimpleXLSX::parse($file_path);
        if (!$xlsx) {
            return [
                'success' => false,
                'message' => 'Unable to read Excel file: ' . SimpleXLSX::parseError()
            ];
        }

        $data = [];
        $rowCount = 0;

        // استخدم $xlsx->rows() مباشرة
        foreach ($xlsx->rows() as $row) {
            if (!empty(array_filter($row))) {
                $data[] = [
                    'year'     => $academic_year,
                    'semester' => $semester,
                    'row'      => $row,
                ];
                $rowCount++;
            }
        }

        return [
            'success' => true,
            'count'   => $rowCount,
            'data'    => $data,
        ];
    }

    public static function get_calendar_data($year_id, $semester_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'afb_calendar';

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT date_en, activity_en, date_ar, activity_ar 
                 FROM $table_name 
                 WHERE academic_year_id = %d AND semester_id = %d 
                 ORDER BY date_en ASC",
                $year_id,
                $semester_id
            ),
            ARRAY_A
        );

        return $results ?: [];
    }
}
