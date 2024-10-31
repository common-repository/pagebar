<?php
/**
 * Create pagebar for blog posts.
 *
 * @package pagebar
 */

require_once 'class-basebar.php';

/**
 * Class Pagebar2_Postbar
 */
final class Pagebar2_Postbar extends Pagebar2_Basebar {

	/**
	 * Test.
	 *
	 * @var string $action Tell the super class to perform what action
	 **/
	protected string $action;

	/**
	 * Initialize class
	 *
	 * @param int $paged Crrent page.
	 * @param int $max_page Total pages.
	 */
	public function __construct( $paged, $max_page ) {
		parent::__construct( $paged, $max_page );
		$this->div_name = 'pagebar';
		$this->action   = 'postbar';
		$this->display();
	}

	/**
	 * Only one page -> don't display postbar
	 *
	 * @return int 1: one page, 0: multiple pages
	 */
	public function leave(): int {
		if ( $this->max_page <= 1 ) {
			return 1;
		}

		return 0;
	}

}
