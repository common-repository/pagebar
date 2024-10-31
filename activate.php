<?php
/**
 * Inialize options and store them in database.
 *
 * @package pagebar
 */

$stylesheet = get_stylesheet();

/* default values */
$left     = 3;
$center   = 7;
$right    = 3;
$pdisplay = 'auto';
$ndisplay = 'auto';

/* twentyten */
$left     = 2;
$center   = 5;
$right    = 2;
$pdisplay = 'never';
$ndisplay = 'never';

// twentyeleven default.

/* twentytwelve default */


/* --------------------------------------------------------------------------------------- */

$all_opts = array(
	'left'        => $left,
	'center'      => $center,
	'right'       => $right,
	'pbText'      => 'Pages:',
	'remove'      => 'on',
	'standard'    => '{page}',
	'current'     => '{page}',
	'first'       => '{page}',
	'last'        => '{page}',
	'connect'     => '...',
	'next'        => 'Next',
	'prev'        => 'Prev',
	'tooltipText' => 'Page {page}',
	'tooltips'    => 'on',
	'pdisplay'    => $pdisplay,
	'ndisplay'    => $ndisplay,
	'stylesheet'  => 'styleCss',
	'cssFilename' => 'pagebar.css',
	'inherit'     => 'on',
);

$additional_postbar_opts      = array(
	'auto'     => 'on',
	'bef_loop' => '',
	'aft_loop' => 'on',
	'footer'   => '',
);
$additional_commentbar_opts   = array(
	'all'       => 'on',
	'where_all' => 'front',
	'label_all' => 'All',
);
$additional_multipagebar_opts = array(
	'all'       => 'on',
	'label_all' => 'All',
);

if ( ! get_option( 'Pagebar2_Postbar' ) ) {
	add_option( 'Pagebar2_Postbar', array_merge( $all_opts, $additional_postbar_opts ) );
}
if ( ! get_option( 'Pagebar2_Multipagebar' ) ) {
	add_option( 'Pagebar2_Multipagebar', array_merge( $all_opts, $additional_multipagebar_opts ) );
}
if ( ! get_option( 'pagebar2_commentbar' ) ) {
	add_option( 'pagebar2_commentbar', array_merge( $all_opts, $additional_commentbar_opts ) );
}


