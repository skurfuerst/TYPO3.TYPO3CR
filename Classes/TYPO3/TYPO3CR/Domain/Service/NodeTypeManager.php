<?php
namespace TYPO3\TYPO3CR\Domain\Service;

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
 * Manager for node types
 *
 * @Flow\Scope("singleton")
 */
class NodeTypeManager {

	/**
	 * Node types, indexed by name
	 *
	 * @var array
	 */
	protected $cachedNodeTypes = array();

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Return all node types which have a certain $superType, without
	 * the $superType itself.
	 *
	 * @param string $superTypeName
	 * @return array<\TYPO3\TYPO3CR\Domain\Model\NodeType> all node types registered in the system
	 */
	public function getSubTypes($superTypeName) {
		if ($this->cachedNodeTypes === array()) {
			$this->loadNodeTypes();
		}

		$filteredNodeTypes = array();
		foreach ($this->cachedNodeTypes as $nodeTypeName => $nodeType) {
			if ($nodeType->isOfType($superTypeName) && $nodeTypeName !== $superTypeName) {
				$filteredNodeTypes[$nodeTypeName] = $nodeType;
			}
		}
		return $filteredNodeTypes;
	}

	/**
	 * Returns the specified node type
	 *
	 * @param string $nodeTypeName
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeType or NULL
	 * @throws \TYPO3\TYPO3CR\Exception\NodeTypeNotFoundException
	 */
	public function getNodeType($nodeTypeName) {
		if ($this->cachedNodeTypes === array()) {
			$this->loadNodeTypes();
		}
		if (!isset($this->cachedNodeTypes[$nodeTypeName])) {
			throw new \TYPO3\TYPO3CR\Exception\NodeTypeNotFoundException('The node type "' . $nodeTypeName . '" is not available.', 1316598370);
		}
		return $this->cachedNodeTypes[$nodeTypeName];
	}

	/**
	 * Checks if the specified node type exists
	 *
	 * @param string $nodeTypeName Name of the node type
	 * @return boolean TRUE if it exists, otherwise FALSE
	 */
	public function hasNodeType($nodeTypeName) {
		if ($this->cachedNodeTypes === array()) {
			$this->loadNodeTypes();
		}
		return isset($this->cachedNodeTypes[$nodeTypeName]);
	}

	/**
	 * Creates a new node type
	 *
	 * @param string $nodeTypeName Unique name of the new node type. Example: "TYPO3.Neos:Page"
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeType
	 * @throws \TYPO3\TYPO3CR\Exception
	 */
	public function createNodeType($nodeTypeName) {
		throw new \TYPO3\TYPO3CR\Exception('Creation of node types not supported so far; tried to create "' . $nodeTypeName . '".', 1316449432);
	}

	/**
	 * Return the full configuration of all node types. This is just an internal
	 * method we need for exporting the schema to JavaScript for example.
	 *
	 * @return array
	 */
	public function getFullConfiguration() {
		if ($this->cachedNodeTypes === array()) {
			$this->loadNodeTypes();
		}
		$fullConfiguration = array();
		foreach ($this->cachedNodeTypes as $nodeTypeName => $nodeType) {
			$fullConfiguration[$nodeTypeName] = $nodeType->getConfiguration();
		}
		return $fullConfiguration;
	}

	/**
	 * Loads all node types into memory.
	 *
	 * @return void
	 */
	protected function loadNodeTypes() {
		foreach (array_keys($this->settings['contentTypes']) as $nodeTypeName) {
			$this->loadNodeType($nodeTypeName);
		}
	}

	/**
	 * Load one node type, if it is not loaded yet.
	 *
	 * @param string $nodeTypeName
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeType
	 * @throws \TYPO3\TYPO3CR\Exception
	 */
	protected function loadNodeType($nodeTypeName) {
		if (isset($this->cachedNodeTypes[$nodeTypeName])) {
			return $this->cachedNodeTypes[$nodeTypeName];
		}

		if (!isset($this->settings['contentTypes'][$nodeTypeName])) {
			throw new \TYPO3\TYPO3CR\Exception('Node type "' . $nodeTypeName . '" does not exist', 1316451800);
		}

		$nodeTypeConfiguration = $this->settings['contentTypes'][$nodeTypeName];

		$mergedConfiguration = array();
		$superTypes = array();
		if (isset($nodeTypeConfiguration['superTypes'])) {
			foreach ($nodeTypeConfiguration['superTypes'] as $superTypeName) {
				$superType = $this->loadNodeType($superTypeName);
				$superTypes[] = $superType;
				$mergedConfiguration = \TYPO3\Flow\Utility\Arrays::arrayMergeRecursiveOverrule($mergedConfiguration, $superType->getConfiguration());
			}
			unset($mergedConfiguration['superTypes']);
		}
		$mergedConfiguration = \TYPO3\Flow\Utility\Arrays::arrayMergeRecursiveOverrule($mergedConfiguration, $nodeTypeConfiguration);

		$nodeType = new \TYPO3\TYPO3CR\Domain\Model\NodeType($nodeTypeName, $superTypes, $mergedConfiguration);

		$this->cachedNodeTypes[$nodeTypeName] = $nodeType;
		return $nodeType;
	}
}
?>