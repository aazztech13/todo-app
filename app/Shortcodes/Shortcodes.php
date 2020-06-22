<?php
namespace TODO_APP\Shortcodes;

class Shortcodes {
    /**
     * Register
     *
     * @return void
     */
    public function register() {
        $this->register_shortcodes();
    }

    /**
     * Get Shortcodes
     *
     * @return array
     */
    public function get_shortcodes() {
        return [
            'td_all_task' => ALL_Task::class,
        ];
    }

    /**
     * Register Shortcodes
     *
     * @return void
     */
    public function register_shortcodes() {
        $shortcodes = $this->get_shortcodes();

        if ( ! count( $shortcodes ) ) {return;}

        foreach ( $shortcodes as $shortcode_name => $class_name ) {
            if ( class_exists( $class_name ) ) {
                if ( method_exists( $class_name, 'render' ) ) {
                    $shortcode = new $class_name();
                    add_shortcode( $shortcode_name, [$shortcode, 'render'] );
                }
            }
        }
    }
}
