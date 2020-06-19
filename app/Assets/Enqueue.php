<?php
namespace TODO_APP\Assets;

class Enqueue {
    /**
     * Register
     *
     * @return void
     */
    public function register() {
        $this->load_scripts();
    }

    /**
     * Get Frontend Styles
     * array params id, src, dep, ver, media
     * @return array
     */
    public function get_frontend_styles() {
        $styles = [
            'bootstrap' => [
                'src' => '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
                'ver' => '4.0',
                'desable' => false
            ],
        ];

        return apply_filters( 'tdapp_frontend_styles', $styles );
    }

    /**
     * Get Frontend Scripts
     * array params id, src, dep, ver, in_footer
     * @return array
     */
    public function get_frontend_scripts() {
        $scripts = [
            'popper' => [
                'src' => '//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js'
            ],
            'bootstrap' => [
                'src' => '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js',
                'dep' => ['jquery', 'popper'],
                'ver' => '4.0',
            ],
        ];

        return apply_filters( 'tdapp_frontend_scripts', $scripts );
    }

    /**
     * Register Styles
     *
     * @return void
     */
    public function register_styles( array $styles ) {
        foreach ( $styles as $id => $args ) {

            if (  ! empty( $args['desable'] )  ) {
                continue;
            }

            $defaults = [
                'src' => '', 'dep' => [], 'ver' => false, 'media' => 'all',
            ];

            $args = array_merge( $defaults, $args );
            wp_register_style( $id, $args['src'], $args['dep'], $args['ver'], $args['media'] );
        }
    }

    /**
     * Register Scripts
     *
     * @return void
     */
    public function register_scripts( array $scripts ) {
        foreach ( $scripts as $id => $args ) {

            if (  ! empty( $args['desable'] )  ) {
                continue;
            }

            $defaults = [
                'src' => '', 'dep' => [], 'ver' => false, 'in_footer' => false,
            ];

            $args = array_merge( $defaults, $args );
            wp_register_script( $id, $args['src'], $args['dep'], $args['ver'], $args['in_footer'] );
        }
    }

    /**
     * Enqueue Styles
     *
     * @return void
     */
    public function enqueue_styles( array $styles ) {
        foreach ( $styles as $id => $args ) {
            wp_enqueue_style( $id );
        }
    }

    /**
     * Enqueue Scripts
     *
     * @return void
     */
    public function enqueue_scripts( array $scripts ) {
        foreach ( $scripts as $id => $args ) {
            wp_enqueue_script( $id );
        }
    }

    /**
     * Upgrade JQuery
     *
     * @return void
     */
    public function upgrade_jquery() {
        wp_dequeue_script('jquery');
        wp_deregister_script('jquery');

        wp_register_script('jquery', '//code.jquery.com/jquery-3.5.1.min.js', false, '3.5.1', 'true');
        wp_enqueue_script('jquery');
    }

    /**
     * Load Scripts
     *
     * @return void
     */
    public function load_scripts() {
        // Load Frontend Styles
        $frontend_styles = $this->get_frontend_styles();
        $this->register_styles(  $frontend_styles );
        $this->enqueue_styles(  $frontend_styles );

        // Upgrade JQuery
        $upgrade_jquery = apply_filters( 'tdapp_upgrade_jquery', true );
        if ( $upgrade_jquery ) {
            $this->upgrade_jquery();
        }
        
        // Load Frontend Scripts
        $frontend_scripts = $this->get_frontend_scripts();
        $this->register_scripts(  $frontend_scripts );
        $this->enqueue_scripts(  $frontend_scripts );
    }
}
