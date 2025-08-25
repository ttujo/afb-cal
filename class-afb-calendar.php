<?php
class AFB_Calendar {
    public function init() {
        add_action('init', [$this, 'register_taxonomies']);
        add_action('init', [$this, 'create_database_table']);
        add_action('init', [$this, 'register_shortcodes']); // تسجيل الشورتكود
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('plugins_loaded', [$this, 'load_textdomain']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }

    public function load_textdomain() {
        load_plugin_textdomain('afb-calendar', false, dirname(plugin_basename(__FILE__)) . '/../languages');
    }

    public function create_database_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'afb_calendar';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            academic_year_id mediumint(9) NOT NULL,
            semester_id mediumint(9) NOT NULL,
            date_en text NOT NULL,
            activity_en text NOT NULL,
            activity_ar text NOT NULL,
            date_ar text NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function register_taxonomies() {
        register_taxonomy('academic_year', [], [
            'hierarchical' => true,
            'labels' => [
                'name' => __('Academic Years', 'afb-calendar'),
                'singular_name' => __('Academic Year', 'afb-calendar'),
            ],
            'show_ui' => true,
            'show_admin_column' => true,
        ]);

        register_taxonomy('semester', [], [
            'hierarchical' => true,
            'labels' => [
                'name' => __('Semesters', 'afb-calendar'),
                'singular_name' => __('Semester', 'afb-calendar'),
            ],
            'show_ui' => true,
            'show_admin_column' => true,
        ]);
    }

    public function add_admin_menu() {
        add_menu_page(
            __('AFB Calendar', 'afb-calendar'),
            __('AFB Calendar', 'afb-calendar'),
            'manage_options',
            'afb-calendar',
            [$this, 'admin_page'],
            'dashicons-calendar-alt',
            30
        );
        add_submenu_page(
            'afb-calendar',
            __('Import Excel', 'afb-calendar'),
            __('Import Excel', 'afb-calendar'),
            'manage_options',
            'afb-calendar-import',
            [$this, 'import_page']
        );
    }

    public function admin_page() {
        include AFB_CALENDAR_PLUGIN_PATH . 'templates/admin-page.php';
    }

    public function import_page() {
        include AFB_CALENDAR_PLUGIN_PATH . 'templates/import-page.php';
    }

    public function enqueue_scripts() {
        wp_enqueue_style('afb-calendar-style', AFB_CALENDAR_PLUGIN_URL . 'assets/css/style.css');
        wp_enqueue_script('afb-calendar-script', AFB_CALENDAR_PLUGIN_URL . 'assets/js/script.js', ['jquery'], AFB_CALENDAR_VERSION, true);
        wp_localize_script('afb-calendar-script', 'afb_calendar_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('afb_calendar_nonce')
        ]);
    }

    public function admin_enqueue_scripts($hook) {
        if ($hook === 'toplevel_page_afb-calendar' || $hook === 'afb-calendar_page_afb-calendar-import') {
            wp_enqueue_style('afb-calendar-admin-style', AFB_CALENDAR_PLUGIN_URL . 'assets/css/admin-style.css');
            wp_enqueue_script('afb-calendar-admin-script', AFB_CALENDAR_PLUGIN_URL . 'assets/js/admin-script.js', ['jquery'], AFB_CALENDAR_VERSION, true);
        }
    }

    // ====== تسجيل الشورتكود الجديد ======
    public function register_shortcodes() {
        add_shortcode('afb-ttu-cal', [$this, 'render_calendar_shortcode']);
    }

    public function render_calendar_shortcode($atts) {
        ob_start();
        $academic_years = get_terms(['taxonomy' => 'academic_year', 'hide_empty' => false]);
        $semesters = get_terms(['taxonomy' => 'semester', 'hide_empty' => false]);

        include AFB_CALENDAR_PLUGIN_PATH . 'templates/calendar-shortcode.php';
        return ob_get_clean();
    }
}
