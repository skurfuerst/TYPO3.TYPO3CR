<?php
declare(ENCODING = 'utf-8');
namespace F3\TYPO3CR\Admin\Service\View\Nodes;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3CR".                    *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * JSON view for the Nodes List action
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class ListJSON extends \F3\FLOW3\MVC\View\AbstractView {

	/**
	 * @var array
	 */
	public $nodes = array();

	/**
	 * Renders this list view
	 *
	 * @return string The rendered JSON output
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function render() {
		return json_encode($this->nodes);
	}
}
?>