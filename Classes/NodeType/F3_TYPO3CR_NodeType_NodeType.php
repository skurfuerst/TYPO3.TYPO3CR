<?php
declare(ENCODING = 'utf-8');

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * @package TYPO3CR
 * @subpackage NodeType
 * @version $Id$
 */

/**
 * A NodeType
 *
 * @package TYPO3CR
 * @subpackage NodeType
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class F3_TYPO3CR_NodeType_NodeType extends F3_TYPO3CR_NodeType_NodeTypeDefinition implements F3_PHPCR_NodeType_NodeTypeInterface {

	/**
	 * @var F3_FLOW3_Component_Manager
	 */
	protected $componentManager;

	/**
	 * @var F3_TYPO3CR_Storage_BackendInterface
	 */
	protected $storageAccess;

}

?>