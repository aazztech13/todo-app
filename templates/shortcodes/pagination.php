<?php
    defined( 'ABSPATH' ) || exit;

    $total   = isset( $data['total'] ) ? $data['total'] : tdapp_get_loop_prop( 'total_pages' );
    $current = isset( $data['current'] ) ? $data['current'] : tdapp_get_loop_prop( 'current_page' );
    $base    = isset( $data['base'] ) ? $data['base'] : esc_url_raw( str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ) );
    $format  = isset( $data['format'] ) ? $data['format'] : '';

    $pages = tdapp_get_page_links( compact('total', 'current', 'base', 'format') );
    
    if ( is_array( $pages ) ) { ?>
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
        <?php foreach ( $pages as $page ) { 
          $active_class = (strpos($page, 'current') !== false ? ' active' : '');
          ?>
          <li class="page-item<?php echo $active_class ?>">
            <?php echo str_replace('page-numbers', 'page-link', $page); ?>
          </li>
          <?php } ?>
        </ul>
      </nav>
    <?php
  }
?>