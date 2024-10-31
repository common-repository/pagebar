<?php

if ( ! class_exists( 'Pagebar2Options' ) ) {
	class Pagebar2Options {
		function __construct() {
			$page = add_action( 'admin_menu', array( &$this, 'adminmenu' ) );

			if ( ! empty( sanitize_text_field( wp_unslash( $_POST['pagebar2update'] ) ) ) ) {

				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( esc_html__( 'Cheatin&#8217; uh?', 'pagebar' ) );
				}
				check_admin_referer( '_pagebar_options' );

				$all_opts = array(
					'left',
					'center',
					'right',
					'pbText',
					'remove',
					'standard',
					'current',
					'first',
					'last',
					'connect',
					'prev',
					'next',
					'tooltipText',
					'tooltips',
					'pdisplay',
					'ndisplay',
					'stylesheet',
					'cssFilename',
					'inherit',
				);

				$additionalPostbarOpts      = array( 'auto', 'bef_loop', 'aft_loop', 'footer' );
				$additionalCommentbarOpts   = array( 'all', 'where_all', 'label_all' );
				$additionalMultipagebarOpts = array( 'all', 'label_all' );

				$postbaroptions      = array_merge( $all_opts, $additionalPostbarOpts );
				$commentbaroptions   = array_merge( $all_opts, $additionalCommentbarOpts );
				$multipagebaroptions = array_merge(
					$all_opts,
					$additionalMultipagebarOpts
				);

				$pbOptionsPostbar = array();
				foreach ( $postbaroptions as $param ) {

					$pbOptionsPostbar[ $param ] = empty( sanitize_text_field( wp_unslash( $_POST[ 'post_' . $param ] ) ) )
						? ''
						: sanitize_text_field( wp_unslash( $_POST[ 'post_' . $param ] ) );
				}

				$pbOptionsCommentbar = array();
				foreach ( $commentbaroptions as $param ) {
					$pbOptionsCommentbar[ $param ] = empty( sanitize_text_field( wp_unslash( $_POST[ 'comment_' . $param ] ) ) )
						? ''
						: sanitize_text_field( wp_unslash( $_POST[ 'comment_' . $param ] ) );
				}

				$pbOptionsMultipagebar = array();
				foreach ( $multipagebaroptions as $param ) {
					$pbOptionsMultipagebar[ $param ] = empty( sanitize_text_field( wp_unslash( $_POST[ 'multipage_' . $param ] ) ) )
						? ''
						: sanitize_text_field( wp_unslash( ( $_POST[ 'multipage_' . $param ] ) ) );
				}

				$text1 = update_option( 'pagebar2_postbar', $pbOptionsPostbar );
				$text2 = update_option( 'Pagebar2_Multipagebar', $pbOptionsMultipagebar );
				$text3 = update_option( 'pagebar2_commentbar', $pbOptionsCommentbar );

				$text =
					$text1 || $text2 || $text3
						? esc_html__( 'Options', 'pagebar' )
						: esc_html__( 'No options', 'pagebar' );
				?>
				<div id="message" class="updated fade"><p>
						<?php
						esc_html_e( $text . ' ' . 'updated', 'pagebar' );
						?>
					</p></div>
				<?php
			} //if
		} //Pagebar2Options()

		/* -------------------------------------------------------------------------- */
		function textinput( $text, $type, $var, $pbOptions, $prefix ) {
			?>
			<tr>
				<th scope="row"><label for="<?php echo esc_html( $var ); ?>">
						<?php esc_html_e( $text, 'pagebar' ); ?>
					</label></th>

				<td><input type="<?php echo esc_html( $type ); ?>"
						   name="<?php echo esc_html( $prefix . '_' . $var ); ?>"
						   value="<?php echo esc_html( $pbOptions[ $var ] ); ?>"></td>
			</tr>
			<?php
		}

		/* -------------------------------------------------------------------------- */
		function checkbox( $text, $var, $pbOptions, $prefix, $onClick = '' ) {
			?>
			<label id="lbl_<?php echo esc_html( $var ); ?>">
				<input type="checkbox" id="cb_<?php echo esc_html( $var ); ?>"
					   name="<?php echo esc_html( $prefix . '_' . $var ); ?>"
					<?php echo esc_html( $pbOptions[ $var ] ? ' checked' : '' ); ?>/>
				<?php esc_html_e( $text, 'pagebar' ); ?></label><br/>
			<?php
		}

		/* -------------------------------------------------------------------------- */
		function radiobutton( $name, $value, $text, $pbOptions, $prefix, $onClick = '' ) {
			?>
			<label><input type="radio" name="<?php echo esc_html( $prefix . '_' . $name ); ?>"
						  value=" <?php echo esc_html( $value ); ?>"
					<?php
					if ( $pbOptions[ $name ] === $value ) {
						echo esc_html( ' checked' );
					}
					?>
				>&nbsp;
				<?php esc_html_e( $text, 'pagebar' ); ?></label>
			<?php
		}

		/* -------------------------------------------------------------------------- */
		function pb_basicOptions( $pbOptions, $prefix ) {
			?>
			<tr>
				<?php
				$this->textinput( 'Left', 'number', 'left', $pbOptions, $prefix );
				$this->textinput( 'Center', 'number', 'center', $pbOptions, $prefix );
				$this->textinput( 'Right', 'number', 'right', $pbOptions, $prefix );
				$this->textinput( 'Leading text', 'text', 'pbText', $pbOptions, $prefix );
				$this->textinput( 'Standard page', 'text', 'standard', $pbOptions, $prefix );
				$this->textinput( 'Current Page', 'text', 'current', $pbOptions, $prefix );
				$this->textinput( 'First page', 'text', 'first', $pbOptions, $prefix );
				$this->textinput( 'Last page', 'text', 'last', $pbOptions, $prefix );
				$this->textinput( 'Connector', 'text', 'connect', $pbOptions, $prefix );
				?>
			</tr>

			<tr>
				<th scope="row"><?php esc_html_e( 'Previous', 'pagebar' ); ?></th>
				<td>
					<input type="text" id="previous" name="<?php echo esc_html( $prefix ); ?>_prev"
						   value="<?php echo esc_html( $pbOptions['prev'] ); ?>">
					<?php $this->radiobutton( 'pdisplay', 'auto', 'auto', $pbOptions, $prefix ); ?>&nbsp;&nbsp;
					<?php $this->radiobutton( 'pdisplay', 'always', 'always', $pbOptions, $prefix ); ?>&nbsp;&nbsp;
					<?php $this->radiobutton( 'pdisplay', 'never', 'never', $pbOptions, $prefix ); ?>&nbsp;&nbsp;
				</td>
			</tr>

			<tr>
				<th scope="row" valign="top">
					<?php esc_html_e( 'Next', 'pagebar' ); ?>:
				</th>
				<td>
					<input type="text" id="next" name="<?php echo esc_html( $prefix ); ?>_next"
						   value="<?php echo esc_html( $pbOptions['next'] ); ?>">
					<?php $this->radiobutton( 'ndisplay', 'auto', 'auto', $pbOptions, $prefix ); ?>&nbsp;&nbsp;
					<?php $this->radiobutton( 'ndisplay', 'always', 'always', $pbOptions, $prefix ); ?>&nbsp;&nbsp;
					<?php $this->radiobutton( 'ndisplay', 'never', 'never', $pbOptions, $prefix ); ?>&nbsp;&nbsp;
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_html_e( 'Tooltip text', 'pagebar' ); ?>:</th>
				<td>
					<input type="text" name="<?php echo esc_html( $prefix ); ?>_tooltipText"
						   value="<?php echo esc_html( $pbOptions['tooltipText'] ); ?>">&nbsp;
					<?php $this->checkbox( 'Display', 'tooltips', $pbOptions, $prefix ); ?>&nbsp;
				</td>
			</tr>
			<?php
		} //pb_BasicOptions

		/* -------------------------------------------------------------------------- */
		function pb_stylesheetOptions( $pbOptions, $prefix ) {
			?>
			<tr>
				<th scope="row" valign="top"><?php esc_html_e( 'Stylesheet', 'pagebar' ); ?>:</th>
				<td>
					<label>
						<input onClick="cssSwitch();" type="radio" id="rdo_style"
							   name="<?php echo esc_html( $prefix ) . '_'; ?>stylesheet" value="styleCss"
							<?php
							if ( $pbOptions['stylesheet'] === 'styleCss' ) {
								echo esc_html( ' checked ' );
							}
							?>
						>
						<?php esc_html_e( 'style.css', 'pagebar' ); ?>

					</label>
					<br/>

					<input onClick="cssSwitch();" type="radio" id="rdo_own"
						   name="<?php echo esc_html( $prefix . '_' ); ?>stylesheet" value="own"
						<?php
						if ( $pbOptions['stylesheet'] === 'own' ) {
							echo esc_html( ' checked ' );
						}
						?>
					>

					<input type="text" id="edt_cssFile" name="
					<?php
					echo esc_html(
						$prefix .
						'_'
					);
					?>
					cssFilename" value="<?php echo esc_html( $pbOptions['cssFilename'] ); ?>">
				</td>
			</tr>

			<?php
		}

		/* -------------------------------------------------------------------------- */
		function pb_submitButton( $prefix ) {
			?>
			<p class="submit"><input type="submit" name="pagebar2update" class="button-primary"
									 value="<?php esc_html_e( 'Update Options', 'pagebar' ); ?>"/></p>
			<?php
		}

		/* -------------------------------------------------------------------------- */
		function adminmenu() {
			global $pbOptions;
			if ( function_exists( 'add_options_page' ) ) {
				$this->hook = add_options_page(
					'Pagebar',
					'Pagebar',
					'manage_options',
					'pagebar_options',
					array( &$this, 'pboptions' )
				);
			}

			// add contextual help
			if ( $this->hook ) {
				add_action( 'load-' . $this->hook, array( &$this, 'load_help' ) );
			}
		} //admin_menu()

		/* -------------------------------------------------------------------------- */
		function load_help() {
			if ( method_exists( get_current_screen(), 'add_help_tab' ) ) {
				// WP >= v3.3
				get_current_screen()->add_help_tab(
					array(
						'id'      => 'pagebar_info',
						'title'   => 'Manual',
						'content' => 'Currently not available',
					)
				);
			} else {
				if ( function_exists( 'add_contextual_help' ) ) {
					add_contextual_help(
						$this->hook,
						'Currently not available'
					);
				}
			}
		}

		/* -------------------------------------------------------------------------- */
		function pb_load_jquery() {
			global $wp_scripts;

			// tell WordPress to load jQuery UI tabs
			wp_enqueue_script( 'jquery-ui-tabs' );

			// get registered script object for jquery-ui
			$ui = $wp_scripts->query( 'jquery-ui-core' );

			wp_enqueue_style( 'jquery-ui-smoothness', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', false, null );
		}

		/* -------------------------------------------------------------------------- */

		function pboptions() {
			global $pbOptions;
			?>

			<div class="wrap" id="top">
				<h2>
					<?php esc_html_e( 'Pagebar', 'pagebar' ); ?>
				</h2>

				<script>
					$j = jQuery.noConflict();
					$j(document).ready(function () {
						$j("#optiontabs").tabs();
						pagebar2_js_comment();
						pagebar2_js_multipage();
					});
				</script>

				<form method="post" id="pagebar"
					  action="
					    <?php
					  if ( ! empty( esc_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) ) {
						  echo esc_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
					  }
					  ?>">
					<?php settings_fields( 'pagebar-options' ); ?>

					<div id="optiontabs">
						<ul>
							<li><a href="#postbar"><span>Postbar</span></a></li>
							<li><a href="#multipagebar"><span>Multipagebar</span></a></li>
							<li><a href="#commentbar"><span>Commentbar</span></a></li>
						</ul>

						<div id="postbar"><br/>
							<p><?php require 'pagebar_options_postbar.php'; ?></p>
						</div>
						<div id="multipagebar" class="ui-tabs-hide"><br/>
							<p><?php require 'pagebar_options_multipagebar.php'; ?></p>
						</div>
						<div id="commentbar" class="ui-tabs-hide"><br/>
							<p><?php require 'pagebar_options_commentbar.php'; ?></p>
						</div>
					</div>
					<?php wp_nonce_field( '_pagebar_options' ); ?>
				</form>

			</div>


			<?php
		} //pboptions()
	} //if classexists
} //class
$pagebaroptions = new Pagebar2Options();
