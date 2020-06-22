<?php
namespace TODO_APP\Hooks;

class No_Results_Area {

    /**
     * Render
     *
     * @return void
     */
    public function render( $attributes ) {
        tdapp_load_template( 'shortcodes/no-results', $attributes );
    }
}