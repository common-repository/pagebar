<?php

require_once 'class-basebar.php';

class pagebar2_Commentbar extends pagebar2_Basebar {

	function __construct( $paged, $max_page ) {
		parent::__construct( $paged, $max_page );
		if ( ! $this->pb_options = get_option( 'commentbar' ) ) {
			pagebar_activate();
			$this->pb_options = get_option( 'commentbar' );
		}
		$this->div_name = 'commentbar';
		if ( $this->pb_options['inherit'] ) {
			$tmp_pb_options = get_option( 'postbar' );
			foreach ( $tmp_pb_options as $key => $val ) {
				if ( isset( $this->pb_options[ $key ] ) ) {
					$this->pb_options[ $key ] = $tmp_pb_options[ $key ];
				}
			}
			$this->div_name = 'pagebar';
		}
		$this->action = 'commentbar';
		$this->display();
	}

	function leave() {
		// TODO: leave parameters
		if ( get_query_var( 'all' ) ) { // all parts displayed
			return 1;
		}
		if ( $this->max_page <= 1 ) {
			return 1;
		}

		return 0;
	}

	function create_link( $page ) {
		return esc_url( get_comments_pagenum_link( $page, $this->max_page ) );
	} //display()


	function div_end() {
		/*
		if ($this->pb_options['all'])
			echo esc_html($this->allPagesLink());
		*/
	}//end div_end()

	function allPagesLink() {
		global $post;
		if ( '' == get_option( 'permalink_structure' ) || 'draft' == $post->post_status ) {
			$page_link_type = '&amp;page=';
			$page_link_all  = '&amp;all=1';
		} //if
		else {
			$page_link_type = '/';
			$page_link_all  = '/all/1';
			$url            = get_permalink();
			if ( '/' == $url[ strlen( $url ) - 1 ] ) {
				$slash_yes = '/';
			}
		} //else

		return '<a href="' . untrailingslashit( get_permalink() ) .
			   $page_link_all . '">' . $this->pb_options['label_all'] . '</a></li>';

	}


}
