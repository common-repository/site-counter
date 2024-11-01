<?php
/**
 * Plugin Name: Site Counter
 * Plugin URI: http://web-page.pw/site-counter
 * Description: Site Counter - is a simple count of visitors. It is will bring the number of visitors since the establishment of the counter on the site.
 * Version: 1.2
 * Author: Sam Williams
 * Author URI: http://sam-lab.biz/
 * License: GPL2
 **/
add_action('plugins_loaded', 'sc_site_counter_language');
function sc_site_counter_language(){
    load_plugin_textdomain('site-counter', false, dirname(plugin_basename(__FILE__)) . '/lan/');
}

add_action('admin_init', 'sc_sitecounter_settings');
function sc_sitecounter_settings(){
    register_setting( 'option_group', 'sitecounter_start' );
    register_setting( 'option_group', 'sitecounter_cookies' );
    register_setting( 'option_group', 'sitecounter_help' );

    add_settings_section( 'section_id', esc_html__('Settings', 'site-counter'), '', 'sitecounter_page' );
    add_settings_field('start_field', esc_html__('Start counting from', 'site-counter'), 'start_field', 'sitecounter_page', 'section_id' );
    add_settings_field('cookies_field', esc_html__('How to store Cookies (days)', 'site-counter'), 'cookies_field', 'sitecounter_page', 'section_id' );
    add_settings_field('help_field', esc_html__('Donate', 'site-counter'), 'help_field', 'sitecounter_page', 'section_id' );
}

function sc_sitecounter_page(){
    add_options_page( esc_html__('Site Counter', 'site-counter'), esc_html__('Site Counter', 'site-counter'), 'manage_options', 'sitecounter.php', 'sc_sitecounter_options_page_output' );
}
add_action('admin_menu', 'sc_sitecounter_page');

function sc_standart_setting(){
    update_option('sitecounter_start', '0');
    update_option('sitecounter_cookies', '1');
}
register_activation_hook(__FILE__, 'sc_standart_setting');

wp_clear_scheduled_hook(__FILE__, 'sc_delete_sitecounter');
function sc_delete_sitecounter(){
    delete_option('sitecounter_start');
    delete_option('sitecounter_cookies');
}

function sitecounter_add_dashboard_widgets() {
    wp_add_dashboard_widget('siteciunter_dashboard_widget', esc_html__('Statistic', 'site-counter'), 'sitecounter_dashboard_widget_function');
}
add_action('wp_dashboard_setup', 'sitecounter_add_dashboard_widgets' );

function sc_sitecounter_options_page_output(){
    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <form action="options.php" method="POST">
            <?php
            wp_nonce_field('update-options');
            settings_fields( 'option_group' );
            do_settings_sections( 'sitecounter_page' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function start_field(){
    $start = get_option('sitecounter_start');
    ?>
    <input type="number" min="0" name="sitecounter_start" value="<?php echo $start; ?>" />
    <?php
}

function cookies_field(){
    $cookies = get_option('sitecounter_cookies');
    ?>
    <input type="number" min="1" name="sitecounter_cookies" value="<?php echo $cookies; ?>" />
    <?php
}

function help_field(){
    ?>
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BSM7RW9DWDZPW" target="_blank">
        <img alt="" border="0" src="<?php echo plugins_url('/img/donate.gif', __FILE__); ?>">
    </a>
    <?php
}

function sc_set_sitecounter_cookie(){
    $cookies = get_option('sitecounter_cookies');
    setcookie('sitecounter', 'true', time()+3600*24*$cookies, COOKIEPATH, COOKIE_DOMAIN, false);
}
add_action('init', 'sc_set_sitecounter_cookie');

function sc_get_counter(){
    $counter = get_option('sitecounter_start');
    if (!isset($_COOKIE['sitecounter'])){
        sc_set_sitecounter_cookie();
        $counter++;
        update_option('sitecounter_start', $counter);
        return $counter;
    } else {
        return $counter;
    }
}

function sitecounter_dashboard_widget_function(){
    $visit_today = get_option('sitecounter_visit_day');
    ?>
    <table>
        <tbody>
        <tr>
            <td><b><?php echo esc_html__('Total:', 'site-counter'); ?></b></td>
            <td><?php echo sc_sitecounter(); ?></td>
        </tr>
        </tbody>
    </table>
    <p style="float: right; font-style: italic;">&copy; <a href="/wp-admin/options-general.php?page=sitecounter.php">Sitecounter</a></p>
    <?php
}

add_action('widgets_init', 'register_sc_widget');
function register_sc_widget() {
    register_widget('siteCounter_Widget');
}

class siteCounter_Widget extends WP_Widget {
    public function __construct(){
        $widgetSettings = array(
            'classname' => 'siteCounter_Widget',
            'description' => esc_html__('Site Counter Widget', 'site-counter'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('siteCounter_Widget', esc_html__('Site Counter Widget', 'site-counter'), $widgetSettings);
    }

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', empty( $instance['title']) ? esc_html__('Site Counter', 'site-counter') : $instance['title'], $instance, $this->id_base);
        $siteCounter = sc_get_counter();
        $text = $instance['text'];

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        if ($text){
            printf($text, $siteCounter);
        }
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['text'] = sanitize_text_field($new_instance['text']);

        return $instance;
    }

    public function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => ''));
        $title = sanitize_text_field($instance['title']);
        $text = sanitize_text_field($instance['text']);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo esc_html__('Title Wiget:', 'site-counter'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('text'); ?>"><?php echo esc_html__('Text Widget(%s):', 'site-counter'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo esc_attr($text); ?>" />
            </p>
        <?php
    }
}

function sc_sitecounter(){
    $counterValue = sc_get_counter();
    printf("%s", $counterValue);
}