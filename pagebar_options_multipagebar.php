<?php if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.1 403 Forbidden' );
	die( 'HTTP/1.1 403 Forbidden' );
} ?>
<script language="javascript">

	function pagebar2_js_multipage() {
		$j = jQuery.noConflict();
		if ($j("#cb_multipage_inherit").attr("checked")) {
		}
		$j("#tb_multipage_inherit").hide();
	}

	else
	{
		$j("#tb_multipage_inherit").show();
	}
	}
</script>

<table class="form-table">

	<?php
	if ( ! $pb_options = get_option( 'Pagebar2_Multipagebar' ) ) {
		pagebar2_activate();
		$pb_options = get_option( 'Pagebar2_Multipagebar' );
	}
	?>
	<tr>
		<th scope="row" valign="top"><?php esc_html_e( 'Inherit settings', 'pagebar' ); ?></th>
		<td>
			<label id="lbl_multipage_inherit">
				<input type="checkbox" id="cb_multipage_inherit" name="multipage_inherit" onClick="js_multipage()" \

					<?php
					if ( empty( $pb_options ['inherit'] ) ) {
						echo esc_html( '' );
					} else {
						echo esc_html( ' checked' );
					}
					?>
				>
				&nbsp;<?php esc_html_e( 'Inherit basic settings from postbar', 'pagebar' ); ?></label>
		</td>
	</tr>

	<tbody id="tb_multipage_inherit">
	<?php
	$this->pb_basicOptions( $pb_options, 'multipage' );
	$this->pb_stylesheetOptions( $pb_options, 'multipage' );
	?>
	</tbody>


	<tr>
		<th scope="row" valign="top"><?php echo esc_html_e( 'All pages link', 'pagebar' ); ?></th>
		<td>
			<label id="lbl_multipage_all">
				<input type="checkbox" id="cb_multipage_all" name="multipage_all"
					<?php
					if ( empty( $pb_options ['all'] ) ) {
						echo esc_html( '' );
					} else {
						echo esc_html( ' checked' );
					}
					?>
				>
				&nbsp;<?php echo esc_html_e( "Display 'All Pages' link", 'pagebar' ); ?></label>
			<?php $this->textinput( 'All Pages Label', 'text', 'label_all', $pb_options, 'multipage' ); ?>
		</td>
	</tr>


</table>
<?php $this->pb_submitButton( 'postbar' ); ?>
