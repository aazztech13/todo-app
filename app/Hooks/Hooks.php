<?php

namespace TODO_APP\Hooks;

class Hooks {
    public function register() {
        $action_hooks = $this->get_action_hooks();
        $this->register_hooks( 'action', $action_hooks );

        $filter_hooks = $this->get_filter_hooks();
        $this->register_hooks( 'filter', $filter_hooks );
    }

    /**
     * Get Action Hooks
     *
     * @return array
     */
    public function get_action_hooks() {
        return [
            'tdapp_after_tasks_loop' => [
                'callback' => Tasks_Pagination::class,
                'priority' => 10,
            ],
            'tdapp_shortcode_tasks_loop_no_results' => [
                'callback' => No_Results_Area::class,
                'priority' => 10,
                'args'     => 1,
            ],
        ];
    }

    /**
     * Get Filter Hooks
     *
     * @return array
     */
    public function get_filter_hooks() {
        return [];
    }

    /**
     * Register Hooks
     *
     * @return void
     */
    public function register_hooks( $hook_type = 'action', array $hooks ) {

        if ( ! count( $hooks ) ) { return; }

        foreach ( $hooks as $hook_name => $hook_args ) {
            if ( class_exists( $hook_args['callback'] ) ) {
                if ( method_exists( $hook_args['callback'],  'render') ) {
                    $class_name = $hook_args['callback'];
                    $callback = new $class_name();
                    $priority = ( isset( $hook_args['priority'] ) ) ? $hook_args['priority'] : 10;
                    $accepted_args = ( isset( $hook_args['args'] ) ) ? $hook_args['args'] : 1;

                    if ( 'action' === $hook_type ) {
                        add_action( $hook_name, [ $callback, 'render' ] , $priority, $accepted_args );
                    }

                    if ( 'filter' === $hook_type ) {
                        add_filter( $hook_name, [ $callback, 'render' ], $priority, $accepted_args );
                    }
                    
                }
            }
        }
    }
}