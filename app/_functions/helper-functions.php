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
 * Converts a string (e.g. 'yes' or 'no') to a bool.
 *
 * @since 1.0
 * @param string $string String to convert.
 * @return bool
 */
function tdapp_string_to_bool( $string ) {
    return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
}
