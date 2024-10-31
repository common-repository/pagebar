<?php
require_once 'class-basebar.php';

class Pagebar2Multipagebar extends Pagebar2_Basebar {

	public function __construct( $paged, $max_page ) {
		parent::__construct( $paged, $max_page );
		$this->div_name = 'multipagebar';
		if ( get_option( 'multipagebar' ) !== $this->pb_options ) {
			pagebar_activate();
			$this->pb_options = get_option( 'multipagebar' );
		}
		if ( $this->pb_options['inherit'] ) {
			$tmp_pbOptions = get_option( 'postbar' );
			foreach ( $tmp_pbOptions as $key => $val ) {
				if ( isset( $this->pb_options[ $key ] ) ) {
					$this->pb_options[ $key ] = $tmp_pbOptions[ $key ];
				}
			}
			$this->div_name = 'pagebar';
		}
		$this->action = 'multipagebar';
		parent::display();

	}

	function leave(): int {
		if ( $this->max_page <= 1 ) { // only one page.
			return 1;
		}
		if ( get_query_var( 'all' ) ) { // all parts displayed.
			return 1;
		}

		return 0;
	}

	function create_link( $page ) {
		global $post;
		if ( 1 === $page ) {
			$link = get_permalink();
		} else {
			if ( '' === get_option( 'permalink_structure' ) || in_array(
					$post->post_status,
					array(
						'draft',
						'pending',
					),
					true
				) ) {
				$link = get_permalink() . '&amp;page=' . $page;
			} else {
				$link = trailingslashit( get_permalink() ) . user_trailingslashit( $page, 'single_paged' );
			}
		} //else

		return $link;
	}

	function div_end() {
		if ( $this->pb_options['all'] ) {
			echo esc_html( $this->allPagesLink() );
		}
		echo '</div>';
	}

	function allPagesLink() {
		global $post;
		if ( '' === get_option( 'permalink_structure' ) || 'draft' === $post->post_status ) {
			$page_link_type = '&amp;page=';
			$page_link_all  = '&amp;all=1';
		} else {
			$page_link_type = '/';
			$page_link_all  = '/all/1';
			$url            = get_permalink();
			if ( '/' === $url[ strlen( $url ) - 1 ] ) {
				$slash_yes = '/';
			}
		} //else

		return '<a href="' . untrailingslashit( get_permalink() ) .
			   $page_link_all . '">' . $this->pb_options['label_all'] . '</a></li>';

	}

}