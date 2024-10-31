<?php
if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.1 403 Forbidden' );
	die( 'HTTP/1.1 403 Forbidden' );
} ?>
<script type="text/javascript">

	function pagebar2_autoSwitch(id) {
		var elements = ['footer', 'bef_loop', 'aft_loop', 'remove'];
		$j = jQuery.noConflict();
		if ($j('#cb_auto:checked').val() == null) {
			color = '#ccc';
			dis = "disabled";
		} else {
			color = '#000';
			dis = "";
		}
		for (i = 0; i <= elements.length; i++) {
			$j('#lbl_' + elements[i]).css({color: color});
			$j("#cb_" + elements[i]).attr("disabled", dis);
		}
		$j('#pos').css({color: color});
		$j('#integrate').css({color: color});
	}

	function pagebar2_cssSwitch(id) {
		$j = jQuery.noConflict();
		// double check for undefined and null for compatibilty with
		// WP 2.3 and 2.5
		if (($j('#rdo_style:checked').val() !== undefined) &&
			($j('#rdo_style:checked').val() !== null)) {


			$j("#edt_cssFile").attr("disabled", "disabled");
			$j("#edt_cssFile").css({color: '#ccc'});
		} else {
			$j("#edt_cssFile").attr("disabled", '');
			$j("#edt_cssFile").css({color: '#000'});
		}
	}
</script>

<style type="text/css" media="screen">
    .blank table, caption, tbody, tfoot, thead, tr, th, td {
        margin: 0;
        padding: 0;
        border: 0;
        outline: 0;
        /*	font-size: 100%;
			vertical-align: baseline; */
        background: transparent;

    }</style>

<form method="post" id="pagebar" action="
<?php
if ( ! empty( sanitize_text_field( wp_unslash( $_SERVER ['REQUEST_URI'] ) ) ) ) {
	if ( ! empty( esc_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) ) {
		echo esc_html( sanitize_text_field( wp_unslash( $_SERVER ['REQUEST_URI'] ) ) );
	}
}
?>
">
	<table class="form-table">

		<?php $this->pb_basicOptions( $pb_options, 'page' ); ?>


		<tr>
			<th scope="row" width="33%"><?php esc_html_e( 'Automagic insertion', 'pagebar' ); ?>:</th>
			<td>
				<?php $this->checkbox( 'Insert pagebar automagic into blog', 'auto', $pb_options, "pagebar2_autoSwitch('position');" ); ?>
			</td>
		</tr>


		<tr>
			<th scope="row" valign="top"><?php esc_html_e( 'Positioning', 'pagebar' ) . ':'; ?></th>
			<td>
				<?php
				$this->checkbox( 'Front of postings', 'bef_loop', $pb_options, 'page' );
				$this->checkbox( 'Behind postings', 'aft_loop', $pb_options, 'page' );
				$this->checkbox( 'Footer', 'footer', $pb_options, 'page' );
				?>
			</td>
		</tr>

		<tr>
			<th scope="row" valign="top"><?php esc_html_e( 'Integration', 'pagebar' ); ?>:</th>

			<td>
				<?php $this->checkbox( 'Remove standard navigation', 'remove', $pb_options, 'page' ); ?>
			</td>
		</tr>


		<tr>
			<th scope="row" valign="top"><?php esc_html_e( 'Stylesheet', 'pagebar' ); ?>:</th>
			<td><label>

					<input onClick="cssSwitch();" type="radio" id="rdo_style"
						   name="stylesheet" value="styleCss"
						<?php
						if ( $pb_options ['stylesheet'] === 'styleCss' ) {
							echo esc_html( ' checked ' );
						}
						?>
					>

					<?php esc_html_e( 'style.css', 'pagebar' ); ?>

				</label><br/>

				<input onClick="cssSwitch();" type="radio" id="rdo_own"
					   name="stylesheet" value="own"
					<?php
					if ( $pb_options ['stylesheet'] === 'own' ) {
						echo esc_html( ' checked ' );
					}
					?>
				>

				<input type="text" id="edt_cssFile" name="cssFilename"
					   value="<?php echo esc_html( $pb_options ['cssFilename'] ); ?>"></td>
		</tr>

	</table>


	<?php $this->pb_submitButton( 'pagebar' ); ?>

	<script type="text/javascript">
		pagebar2_autoSwitch();
		pagebar2_cssSwitch();
	</script>
</form>
