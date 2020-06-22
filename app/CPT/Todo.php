<?php
namespace TODO_APP\CPT;

class Todo {
    /**
     * Register
     *
     * @return void
     */
    public function register() {
        $this->register_cpt();
    }

    /**
     * Register CPT
     *
     * @return void
     */
    public function register_cpt() {

        register_post_type( 'todo', [
            'label'  => 'Todo',
            'public' => true,
        ] );
    }
}