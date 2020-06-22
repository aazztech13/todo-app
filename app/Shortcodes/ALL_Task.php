<?php
namespace TODO_APP\Shortcodes;

use TODO_APP\Helper\Cache_Helper;

class ALL_Task {

    /**
     * Attributes.
     *
     * @since 1.0
     * @var   array
     */
    protected $attributes = [];

    /**
     * Query args.
     *
     * @since 1.0
     * @var   array
     */
    protected $query_args = [];

    /**
     * Render
     *
     * @return void
     */
    public function render( $atttibutes ) {
        $this->attributes = $this->parse_attributes( $atttibutes );
        $this->query_args = $this->parse_query_args();

        return $this->get_contents();
    }

    // get_contents
    public function get_contents() {
        $columns = absint( $this->attributes['columns'] );
        $tasks   = $this->get_query_results();
        
        ob_start();

        if ( $tasks && $tasks->ids ) {
            // Prime caches to reduce future queries.
            if ( is_callable( '_prime_post_caches' ) ) {
                _prime_post_caches( $tasks->ids );
            }
			
			$this->get_header();

            // Setup the loop.
			tdapp_setup_loop(
				array(
					'columns'      => $columns,
					'name'         => 'todo',
					'is_shortcode' => true,
					'is_search'    => false,
					'is_paginated' => tdapp_string_to_bool( $this->attributes['paginate'] ),
					'total'        => $tasks->total,
					'total_pages'  => $tasks->total_pages,
					'per_page'     => $tasks->per_page,
					'current_page' => $tasks->current_page,
				)
			);

            $original_post = $GLOBALS['post'];
            tdapp_tasks_loop_start();

            if ( count( $tasks->ids ) ) {
                foreach ( $tasks->ids as $task_id ) {
                    $GLOBALS['post'] = get_post( $task_id );
                    setup_postdata( $GLOBALS['post'] );

                    tdapp_load_template( 'shortcodes/content-all-tasks' );
                }
            }

            $GLOBALS['post'] = $original_post;
            tdapp_tasks_loop_end();

            // Fire standard shop loop hooks when paginating results so we can show result counts and so on.
            if ( wc_string_to_bool( $this->attributes['paginate'] ) ) {
                do_action( 'tdapp_after_tasks_loop' );
            }

            wp_reset_postdata();
            wc_reset_loop();
        } else {
            do_action( 'tdapp_shortcode_tasks_loop_no_results', $this->attributes );
        }

        $contents = ob_get_clean();

        return $contents;
	}

	// get_header
	public function get_header() {
		if ( $this->attributes['cache'] ) {
			echo "<div class='alert alert-success text-center my-4'>Caching is enabled</div>";
		} else {
			echo "<div class='alert alert-secondary text-center my-4'>Caching is desabled</div>";
		}
	}

    /**
     * Generate and return the transient name for this shortcode based on the query args.
     *
     * @since 1.0
     * @return string
     */
    protected function get_transient_name() {
        $transient_name = 'tdapp_task_loop_' . md5( wp_json_encode( $this->query_args ) );

        return $transient_name;
    }

    /**
     * Run the query and return an array of data, including queried ids and pagination information.
     *
     * @since  1.0
     * @return object Object with the following props; ids, per_page, found_posts, max_num_pages, current_page
     */
    protected function get_query_results() {
        $transient_name    = $this->get_transient_name();
        $transient_version = Cache_Helper::get_transient_version( 'task_query' );
        $cache             = tdapp_string_to_bool( $this->attributes['cache'] ) === true;
        $transient_value   = $cache ? get_transient( $transient_name ) : false;

        if ( isset( $transient_value['value'], $transient_value['version'] ) && $transient_value['version'] === $transient_version ) {
            $results = $transient_value['value'];
        } else {
            $query = new \WP_Query( $this->query_args );

            $paginated = ! $query->get( 'no_found_rows' );

            $results = (object) [
                'ids'          => wp_parse_id_list( $query->posts ),
                'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
                'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
                'per_page'     => (int) $query->get( 'posts_per_page' ),
                'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
            ];

            if ( $cache ) {
                $transient_value = [
                    'version' => $transient_version,
                    'value'   => $results,
                ];
                set_transient( $transient_name, $transient_value, DAY_IN_SECONDS * 30 );
            }
        }

        return $results;
    }

    /**
     * Parse attributes.
     *
     * @since  1.0
     * @param  array $attributes Shortcode attributes.
     * @return array
     */
    protected function parse_attributes( $attributes ) {
        $attributes = shortcode_atts( [
            'limit'    => 3,        // Results limit.
            'columns'  => 3,       // Number of columns.
            'rows'     => '',       // Number of rows. If defined, limit will be ignored.
            'page'     => 1,        // Page for pagination.
            'paginate' => true,     // Should results be paginated.
            'cache'    => true,     // Should shortcode output be cached.
        ], $attributes );

        return $attributes;
    }

    /**
     * Parse query args.
     *
     * @since  1.0
     * @return array
     */
    protected function parse_query_args() {
        $query_args = [
            'post_type'   => 'todo',
            'post_status' => 'publish',
        ];

        if ( tdapp_string_to_bool( $this->attributes['paginate'] ) ) {
            $this->attributes['page'] = absint( empty( $_GET['task-page'] ) ? 1 : $_GET['task-page'] );
        }

        if ( ! empty( $this->attributes['rows'] ) ) {
			$this->attributes['limit'] =  $this->attributes['columns'] * $this->attributes['rows'];
		}

        $query_args['posts_per_page'] = intval( $this->attributes['limit'] );

        if ( 1 < $this->attributes['page'] ) {
            $query_args['paged'] = absint( $this->attributes['page'] );
        }

        if ( isset( $_GET['cache'] ) ) {
            $this->attributes['cache'] = tdapp_string_to_bool( $_GET['cache'] );
        }

        // Always query only IDs.
        $query_args['fields'] = 'ids';

        return $query_args;
    }
}