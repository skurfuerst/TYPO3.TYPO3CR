<?php
namespace TYPO3\TYPO3CR\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3CR".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * default node label generator; used if no-other is configured
 *
 * @Flow\Scope("singleton")
 */
class DefaultNodeLabelGenerator implements NodeLabelGeneratorInterface {

	/**
	 * Render a node label
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeInterface $node
	 * @return string
	 */
	public function getLabel(\TYPO3\TYPO3CR\Domain\Model\NodeInterface $node) {
		$label = $node->hasProperty('title') ? strip_tags($node->getProperty('title')) : '(' . $node->getNodeType()->getName() . ') ' . $node->getName();
		$croppedLabel = \TYPO3\Flow\Utility\Unicode\Functions::substr($label, 0, NodeInterface::LABEL_MAXIMUM_CHARACTERS);
		return $croppedLabel . (strlen($croppedLabel) < strlen($label) ? ' …' : '');
	}
}
?>