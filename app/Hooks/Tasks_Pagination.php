<?php
namespace TODO_APP\Hooks;

class Tasks_Pagination {

    /**
     * Render
     *
     * @return void
     */
    public function render() {
        tdapp_load_template( 'shortcodes/pagination' );
    }
}