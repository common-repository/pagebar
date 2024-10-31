<?php
/**
 * Options for blog pagbar
 *
 * @package pagebar
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.1 403 Forbidden' );
	die( 'HTTP/1.1 403 Forbidden' );
} ?>
<script type="text/javascript">

	function pagebar2_autoSwitch() {
		const elements = ['footer', 'bef_loop', 'aft_loop', 'remove'];
		const $j = jQuery.noConflict();
		for (let i = 0; i <= elements.length; i++) {
			$j('#lbl_' + elements[i]).css({color: color});  // grey out label texts
			if (dis)  //disable/enable checkboxes
			// $j("#cb_" + elements[i]).attr("disabled", "disabled");
			else
			$j("#cb_" + elements[i]).removeAttr("disabled");
		}
		$j('#pos').css({color: color});
		$j('#integrate').css({color: color});
	}

	function pagebar2_cssSwitch(id) {
//   $j=jQuery.noConflict();
//    // double check for undefined and null for compatibilty with
//    // WP 2.3 and 2.5
//   if ( ($j('#rdo_style:checked').val() !== undefined) &&
//        ($j('#rdo_style:checked').val() !== null   )) {
//
//
//      $j("#edt_cssFile").attr("disabled","disabled");
//      $j("#edt_cssFile").css({color: '#ccc'});
//   } else {
//      $j("#edt_cssFile").attr("disabled",'');
//      $j("#edt_cssFile").css({color: '#000'});
//   }
	}
</script>

<table class="form-table">

	<?php
	$pb_options = get_option( 'Pagebar2_Postbar' );
	if ( ! $pb_options ) {
		pagebar2_activate();
		$pb_options = get_option( 'Pagebar2_Postbar' );
	}
	?>

	<?php $this->pb_basicOptions( $pb_options, 'post' ); ?>

	<tr>
		<th scope="row"><?php esc_html_e( 'Automagic insertion', 'pagebar' ); ?>:</th>
		<td>
			<?php $this->checkbox( 'Autoagically insert post into blog', 'auto', $pb_options, 'post', "pagebar2_autoSwitch('position');" ); ?>
		</td>
	</tr>


	<tr>
		<th scope="row"><?php esc_html_e( 'Positioning', 'pagebar' ) . ':'; ?></th>
		<td>
			<?php
			$this->checkbox( 'Front of postings', 'bef_loop', $pb_options, 'post' );
			$this->checkbox( 'Behind postings', 'aft_loop', $pb_options, 'post' );
			$this->checkbox( 'Footer', 'footer', $pb_options, 'post' );
			?>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php esc_html_e( 'Integration', 'pagebar' ); ?>:</th>

		<td>
			<?php $this->checkbox( 'Remove standard navigation', 'remove', $pb_options, 'post' ); ?>
		</td>
	</tr>

	<?php $this->pb_stylesheetOptions( $pb_options, 'post' ); ?>

</table>


<?php $this->pb_submitButton( 'pagebar' ); ?>

<script type="text/javascript">
	pagebar2_autoSwitch();
	pagebar2_cssSwitch();
</script>
