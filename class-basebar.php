<?php
/**
 * Basic class for postbar, multipagebar and commentbar.
 *
 * @package pagebar
 */

/**
 * Class Pagebar2_Basebar
 */
class Pagebar2_Basebar {

function __construct( $page, $max_page ) {
	global $wp_query, $pb_options;
	$this->div_name   = 'pagebar';
	$this->paged      = $page;
	$this->max_page   = $max_page;
	$this->wp_query   = $wp_query;
	$this->pb_options = $pb_options;         // load options
	$this->init( $this->pb_options );        // initialize
}

function init( $pb_options ) {
}

function add_stylesheet() {
	global $pb_options;

	$url    = 'jquery.tabs.css';
	$handle = 'jquery-tabs';
	wp_register_style( $handle, $url );
	wp_enqueue_style( $handle );
	wp_print_styles();

	$url    = 'jquery.tabs.iecss';
	$handle = 'jquery-tabs.ie';
	wp_register_style( $handle, $url );
	wp_enqueue_style( $handle );
	wp_print_styles();

	if ( $pb_options['stylesheet'] === 'styleCss' ) {
		return;
	}
	$url    = get_bloginfo( 'stylesheet_directory' ) . '/' . $pb_options['cssFilename'];
	$handle = 'pagebar-stylesheet';
	wp_register_style( $handle, $url );
	wp_enqueue_style( $handle );
	wp_print_styles();

}



function display() {

if ( $this->wp_query->is_feed ) {
	return;
}

if ( is_admin() ) {
	return;
}

if ( $this->leave() ) {
	return;
}

$left   = $this->pb_options ['left'];
$center = $this->pb_options ['center'];
$right  = $this->pb_options ['right'];

if ( empty( $this->paged ) ) {
	$this->paged = 1;
}

/** Insert HTML comment for support reasons
 */
?><!-- pb270 -->
<?php

do_action( 'pagebar_before' ); // do general action.
$this->div_start();

if ( isset( $this->action ) ) {
	do_action( $this->action . '_before' );
}
?>
<span><?php echo esc_html( $this->tagReplace( $this->pb_options ['pbText'], $this->paged ) ); ?>&nbsp;</span>

<?php
// it's easy to show all page numbers:
// simply loop and  exit
if ( $this->max_page <= $left + $center + $right ) {

	$this->previous_page( $this->paged );
	for ( $i = 1; $i <= $this->max_page; $i ++ ) {
		if ( $i === $this->paged ) {
			$this->thisPage( $i );
		} else {
			$this->page( $i );
		}
	}
	$this->next_page( $this->paged, $this->max_page );

	if ( isset( $this->action ) ) {
		do_action( $this->action . '_after' );
	}  // do specific action
	do_action( 'pagebar_after' ); // do general action

	return;
} //if

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// left and right
if ( $this->paged < $left + $center ) {
	// left
	$this->previous_page( $this->paged );
	$lc = $left + $center;
	for ( $i = 1; $i <= ( $lc ); $i ++ ) {
		if ( $i === $this->paged ) {
			$this->thisPage( $i );
		} else {
			$this->page( $i );
		}
	}
	// right
	$this->transit( $right );
	for ( $i = $this->max_page - $right + 1; $i <= $this->max_page; $i ++ ) {
		$this->page( $i );
	}
} else { // left, right and center
	if ( ( $this->paged >= $left + $center ) && ( $this->paged < $this->max_page - $right - $center + 1 ) ) {
		// left
		$this->previous_page( $this->paged );
		for ( $i = 1; $i <= $left; $i ++ ) {
			$this->page( $i );
		}
		$this->transit( $left );
		// center
		$c = floor( $center / 2 );
		for ( $i = $this->paged - $c; $i <= $this->paged + $c; $i ++ ) {
			if ( $i === $this->paged ) {
				$this->thisPage( $i );
			} else {
				$this->page( $i );
			}
		}
		// right
		$this->transit( $right );
		for ( $i = $this->max_page - $right + 1; $i <= $this->max_page; $i ++ ) {
			$this->page( $i );
		}
	} else // only left and right
	{
		// left
		$this->previous_page( $this->paged );
		for ( $i = 1; $i <= $left; $i ++ ) {
			$this->page( $i );
		}
		$this->transit( $left );
		// right
		for ( $i = $this->max_page - $right - $center; $i <= $this->max_page; $i ++ ) {
			if ( $i === $this->paged ) {
				$this->thisPage( $i );
			} else {
				$this->page( $i );
			}
		}
	}
}
$this->next_page( $this->paged, $this->max_page );

if ( isset( $this->action ) ) {
	do_action( $this->action . '_after' );
} // do general action
$this->div_end();
do_action( 'pagebar_after' );

}

function leave() {

	if ( is_singular() ) {
		return 1;
	}

	if ( $this->max_page <= 1 ) {
		return 1;
	}

	return 0;
}


function div_start() {
?>
<div class="<?php echo esc_html( $this->div_name ); ?>">
	<?php
	} //function


	function tagReplace( $text, $page ) {

		$text = str_replace( '{page}', $page, $text );
		$text = str_replace( '{current}', $page, $text );
		$text = str_replace( '{total}', $this->max_page, $text );

		return $text;
	}

	function previous_page( $paged ) {

		if ( 'never' === $this->pb_options ['pdisplay'] ) {
			return;
		}

		if ( ( 1 === $this->paged ) && ( 'auto' === $this->pb_options ['pdisplay'] ) ) {
			return;
		}

		$text = $this->tagReplace( $this->pb_options ['prev'], $this->paged );
		if ( 1 === $this->paged ) {
			?>
			<span class="inactive"><?php echo esc_html( $text ); ?></span>
		<?php } else { ?>
			<a href="<?php echo esc_html( $this->create_link( $this->paged - 1 ) ); ?>"
				<?php echo esc_html( $this->tooltip( $this->paged - 1 ) ); ?>>
				<?php echo esc_html( $text ); ?></a>
			<?php
		} //else
	}

	// -----------------------------------------------------------------------------

	function create_link( $page ) {
		return get_pagenum_link( $page );
	}

	// -----------------------------------------------------------------------------

	function tooltip( $page ) {
		if ( $this->pb_options ['tooltips'] ) {
			return ' title="' . $this->tagReplace( $this->pb_options ['tooltipText'], $page ) . '"';
		}

		return '';
	}

	// -----------------------------------------------------------------------------

	function thisPage( $page ) {
		?>
		<span class="this-page"><?php echo esc_html( $this->tagReplace( $this->replaceFirstLast( $page ), $page ) ); ?></span>
		<?php
	}

	// -----------------------------------------------------------------------------

	function replaceFirstLast( $page ) {
		switch ( $page ) {
			case 1:
				return $this->pb_options ['first'];
			case $this->max_page:
				return $this->pb_options ['last'];
			case $this->paged:
				return $this->pb_options['current'];
			default:
				return $this->pb_options['standard'];
		}
	}

	function page( $page ) {
		$link = $this->create_link( $page );

		?>
		<a href="<?php echo esc_html( $link ); ?>"<?php echo esc_html( $this->tooltip( $page ) ); ?>>
			<?php echo esc_html( $this->TagReplace( $this->replaceFirstLast( $page ), $page ) ); ?> </a>
		<?php
	}//end page()

	// -----------------------------------------------------------------------------

	function next_page( $page, $max_page ) {
		if ( $this->pb_options ['pdisplay'] === 'never' ) {
			return;
		}
		if ( ( $this->paged === $max_page ) && ( $this->pb_options ['ndisplay'] === 'auto' ) ) {
			return;
		}
		$text = $this->tagReplace( $this->pb_options ['next'], $page );

		if ( $this->paged === $max_page ) {
			?>
			<span class="inactive"><?php echo esc_html( $text ); ?></span>
		<?php } else { ?>
			<a href="<?php echo esc_html( $this->create_link( $this->paged + 1 ) ); ?>"
				<?php
				echo esc_html( $this->tooltip( $this->paged + 1 ) );
				?>
			><?php echo esc_html( $text ); ?></a>
			<?php
		}
	}//end nextPage()

	// -----------------------------------------------------------------------------

	function transit( $place ) {
		if ( $place > 0 ) {

			?>
			<span class="break">
			<?php

		}
		if ( $this->pb_options['connect'] !== '' ) {
			echo esc_html( $this->pb_options['connect'] );
		} else {
			echo esc_html( '...' );
		}
		?>
		</span>
		<?php
	}//end transit()

	// -----------------------------------------------------------------------------

	function div_end() {
	?>
</div>
<?php
} // function display()

} //class
