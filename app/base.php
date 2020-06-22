<?php

defined( 'ABSPATH' ) || exit;

$autoloader = TODO_APP_PLUGIN_PATH . 'app/autoloader.php';

if ( file_exists( $autoloader ) ) {
    require_once $autoloader;
}

use TODO_APP\Assets\Enqueue;
use TODO_APP\CPT\CPT_Manager;
use TODO_APP\Shortcodes\Shortcodes;
use TODO_APP\Hooks\Hooks;

final class TODO_APP {
    /**
     * Instance
     *
     * @return TODO_APP
     */
    protected static $instance = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        add_action( 'init', [$this, 'register_services'] );
    }

    /**
     * Instance
     *
     * @return TODO_APP
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get Services
     *
     * @return array
     */
    public function get_services() {
        return [
            Enqueue::class,
            CPT_Manager::class,
            Shortcodes::class,
            Hooks::class,
        ];
    }

    /**
     * Register Services
     *
     * @return void
     */
    public function register_services() {
        $services = $this->get_services();

        if ( ! count( $services ) ) {return;}

        foreach ( $services as $class_name ) {
            if ( class_exists( $class_name ) ) {
                if ( method_exists( $class_name, 'register' ) ) {
                    $service = new $class_name();
                    $service->register();
                }
            }
        }
    }
}

function TODO_APP() {
    return TODO_APP::instance();
}

TODO_APP();