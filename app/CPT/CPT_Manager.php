<?php
namespace TODO_APP\CPT;

class CPT_Manager {
    /**
     * Register
     *
     * @return void
     */
    public function register() {
        $this->register_all_cpt();
    }

    /**
     * Get CPT List
     *
     * @return array
     */
    public function get_cpt_list() {
        return [
            'td_todo' => Todo::class,
        ];
    }

    /**
     * Register All CPT
     *
     * @return void
     */
    public function register_all_cpt() {
        $cpt_list = $this->get_cpt_list();

        if ( ! count( $cpt_list ) ) { return; }

        foreach ( $cpt_list as $cpt_name => $class_name ) {
            if ( class_exists( $class_name ) ) {
                if ( method_exists( $class_name,  'register') ) {
                    $cpt = new $class_name();
                    $cpt->register();
                }
            }
        }
    }
}