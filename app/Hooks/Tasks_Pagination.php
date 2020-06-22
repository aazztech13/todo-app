<?php
namespace TODO_APP\Hooks;

class Tasks_Pagination {

    /**
     * Render
     *
     * @return void
     */
    public function render() {
        $args = [
            'total'   => tdapp_get_loop_prop( 'total_pages' ),
            'current' => tdapp_get_loop_prop( 'current_page' ),
            'base'    => esc_url_raw( add_query_arg( 'task-page', '%#%', false ) ),
            'format'  => '?task-page=%#%',
        ];

        if ( ! tdapp_get_loop_prop( 'is_shortcode' ) ) {
            $args['format'] = '';
            $args['base']   = esc_url_raw( str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ) );
        }

        tdapp_load_template( 'shortcodes/pagination', $args );
    }
}