<?php
/**
 * Template Loader
 *
 * @since 1.0
 */
function tdapp_load_template( $template_path, $data = [], $return_type = 'echo' ) {
    $path = TODO_APP_PLUGIN_PATH . "templates/$template_path.php";

    ob_start();

    if ( file_exists( $path ) ) {
        include $path;
    }

    $content = ob_get_clean();

    if ( 'echo' !== $return_type ) {
        return;
    }

    echo $content;
};

/**
 * Tasks Loop Start
 *
 * @since 1.0
 */
function tdapp_tasks_loop_start() {
    tdapp_load_template( 'shortcodes/loop/task-loop-start' );
}

/**
 * Tasks Loop End
 *
 * @since 1.0
 */
function tdapp_tasks_loop_end() {
    tdapp_load_template( 'shortcodes/loop/task-loop-end' );
}

/**
 * Sets up the tdapp_loop global from the passed args or from the main query.
 *
 * @since 1.0
 * @param array $args Args to pass into the global.
 */
function tdapp_setup_loop( $args = array() ) {
    $default_args = array(
		'loop'         => 0,
		'columns'      => wc_get_default_products_per_row(),
		'name'         => '',
		'is_shortcode' => false,
		'is_paginated' => true,
		'is_search'    => false,
		'is_filtered'  => false,
		'total'        => 0,
		'total_pages'  => 0,
		'per_page'     => 0,
		'current_page' => 1,
    );
    
    // If this is a main WC query, use global args as defaults.
	if ( $GLOBALS['wp_query']->get( 'wc_query' ) ) {
		$default_args = array_merge(
			$default_args,
			array(
				'is_search'    => $GLOBALS['wp_query']->is_search(),
				'is_filtered'  => is_filtered(),
				'total'        => $GLOBALS['wp_query']->found_posts,
				'total_pages'  => $GLOBALS['wp_query']->max_num_pages,
				'per_page'     => $GLOBALS['wp_query']->get( 'posts_per_page' ),
				'current_page' => max( 1, $GLOBALS['wp_query']->get( 'paged', 1 ) ),
			)
		);
    }
    
    // Merge any existing values.
	if ( isset( $GLOBALS['tdapp_loop'] ) ) {
		$default_args = array_merge( $default_args, $GLOBALS['tdapp_loop'] );
	}
	$GLOBALS['tdapp_loop'] = wp_parse_args( $args, $default_args );
}

add_action( 'tdapp_after_tasks_loop', 'tdapp_setup_loop' );


/**
 * Gets a property from the tdapp_loop global.
 *
 * @since 1.0
 * @param string $prop Prop to get.
 * @param string $default Default if the prop does not exist.
 * @return mixed
 */
function tdapp_get_loop_prop( $prop, $default = '' ) {
	tdapp_setup_loop(); // Ensure shop loop is setup.

	return isset( $GLOBALS['tdapp_loop'], $GLOBALS['tdapp_loop'][ $prop ] ) ? $GLOBALS['tdapp_loop'][ $prop ] : $default;
}


/**
 * Gets pagination links
 *
 * @since 1.0
 */
function tdapp_get_page_links( $args ) {
    extract( $args );

    $params = apply_filters( 'tdapp_pagination_params', [] );

    if ( $total <= 1 ) {
        return;
    }

    $pages = paginate_links( array_merge( [
        'base'      => $base,
        'format'    => $format,
        'add_args'  => false,
        'current'   => max( 1, $current ),
        'total'     => $total,
        'end_size'  => 3,
        'mid_size'  => 3,

        'type'      => 'array',
        'prev_next' => true,
        'prev_text' => __( '« Prev' ),
        'next_text' => __( 'Next »' ),
        'add_args'  => false,
    ], $params )
    );

    return $pages;
}

/**
 * Converts a string (e.g. 'yes' or 'no') to a bool.
 *
 * @since 1.0
 * @param string $string String to convert.
 * @return bool
 */
function tdapp_string_to_bool( $string ) {
    return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
}
