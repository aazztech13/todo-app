<?php
namespace TODO_APP\Hooks;

class Tasks_Pagination {

    /**
     * Render
     *
     * @return void
     */
    public function render( $attributes ) {
        tdapp_load_template('shortcodes/pagination');
    }
}