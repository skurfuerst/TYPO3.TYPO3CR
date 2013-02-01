<?php
namespace TYPO3\TYPO3CR\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3CR".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Testcase for the "ProxyNode" domain model
 */
class ProxyNodeTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\TYPO3CR\Domain\Model\ProxyNode
	 */
	protected $proxyNode;

	/**
	 * @var \TYPO3\TYPO3CR\Domain\Model\Node
	 */
	protected $newNode;

	/**
	 * @test
	 */
	public function aProxyNodeIsRelatedToAnOriginalNode() {
		$originalNode = $this->getMock('TYPO3\TYPO3CR\Domain\Model\NodeInterface');
		new \TYPO3\TYPO3CR\Domain\Model\ProxyNode($originalNode);
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function theOriginalNodeMustNotBeAProxyNode() {
		$originalNode = $this->getMock('TYPO3\TYPO3CR\Domain\Model\ProxyNode', array(), array(), '', FALSE);
		new \TYPO3\TYPO3CR\Domain\Model\ProxyNode($originalNode);
	}

	/**
	 */
	public function cloneOriginalNodeCallback() {
		$this->proxyNode->_set('newNode', $this->newNode);
	}

	/**
	 */
	protected function assertThatOriginalNodeIsClonedAndMethodIsCalledOnNewNode($methodName, $argument1 = NULL, $argument2 = NULL) {
		$this->newNode = $this->getMock('TYPO3\TYPO3CR\Domain\Model\NodeInterface');

		if ($argument1 === NULL) {
			$this->newNode->expects($this->at(0))->method($methodName);
			$this->newNode->expects($this->at(1))->method($methodName);
		} elseif ($argument2 === NULL) {
			$this->newNode->expects($this->at(0))->method($methodName)->with($argument1);
			$this->newNode->expects($this->at(1))->method($methodName)->with($argument1);
		} else {
			$this->newNode->expects($this->at(0))->method($methodName)->with($argument1, $argument2);
			$this->newNode->expects($this->at(1))->method($methodName)->with($argument1, $argument2);

		}

		$this->proxyNode = $this->getAccessibleMock('TYPO3\TYPO3CR\Domain\Model\ProxyNode', array('materializeOriginalNode'), array(), '', FALSE);
		$this->proxyNode->expects($this->once())->method('materializeOriginalNode')->will($this->returnCallback(array($this, 'cloneOriginalNodeCallback')));

		call_user_func_array(array($this->proxyNode, $methodName), array($argument1, $argument2));
		call_user_func_array(array($this->proxyNode, $methodName), array($argument1, $argument2));
	}

	/**
	 */
	protected function assertThatOriginalOrNewNodeIsCalled($methodName, $argument1 = NULL) {
		$originalNode = $this->getMock('TYPO3\TYPO3CR\Domain\Model\NodeInterface');
		if ($argument1 === NULL) {
			$originalNode->expects($this->once())->method($methodName)->will($this->returnValue('originalNodeResult'));
		} else {
			$originalNode->expects($this->once())->method($methodName)->with($argument1)->will($this->returnValue('originalNodeResult'));
		}

		$newNode = $this->getMock('TYPO3\TYPO3CR\Domain\Model\NodeInterface');
		if ($argument1 === NULL) {
			$newNode->expects($this->once())->method($methodName)->will($this->returnValue('newNodeResult'));
		} else {
			$newNode->expects($this->once())->method($methodName)->with($argument1)->will($this->returnValue('newNodeResult'));
		}

		$proxyNode = $this->getAccessibleMock('TYPO3\TYPO3CR\Domain\Model\ProxyNode', array('dummy'), array(), '', FALSE);
		$proxyNode->_set('originalNode', $originalNode);

		$this->assertEquals('originalNodeResult', $proxyNode->$methodName($argument1));
		$proxyNode->_set('newNode', $newNode);
		$this->assertEquals('newNodeResult', $proxyNode->$methodName($argument1));
	}

	/**
	 * @test
	 */
	public function getPathRetrievesThePathFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getPath');
	}

	/**
	 * @test
	 */
	public function getDepthRetrievesTheDepthFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getDepth');
	}

	/**
	 * @test
	 */
	public function getNameRetrievesTheNameFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getName');
	}

	/**
	 * @test
	 */
	public function getWorkspaceRetrievesTheWorkspaceFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getWorkspace');
	}

	/**
	 * @test
	 */
	public function getIdentifierReturnsTheIdentifier() {
		$originalNode = $this->getMock('TYPO3\TYPO3CR\Domain\Model\NodeInterface');
		$originalNode->expects($this->once())->method('getIdentifier')->will($this->returnValue('theidentifier'));

		$proxyNode = $this->getAccessibleMock('TYPO3\TYPO3CR\Domain\Model\ProxyNode', array('dummy'), array(), '', FALSE);
		$proxyNode->_set('originalNode', $originalNode);

		$this->assertEquals('theidentifier', $proxyNode->getIdentifier());
	}

	/**
	 * @test
	 */
	public function setIndexSetsTheIndexOnTheNewNodeAndClonesTheOriginalNodeIfNoNewNodeExistedYet() {
		$this->assertThatOriginalNodeIsClonedAndMethodIsCalledOnNewNode('setIndex', 5);
	}

	/**
	 * @test
	 */
	public function getIndexRetrievesTheIndexFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getIndex');
	}

	/**
	 * @test
	 */
	public function getParentRetrievesTheParentNodeFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getParent');
	}

	/**
	 * @test
	 */
	public function moveBeforeCallsMoveBeforeOnTheNewNodeAndClonesTheOriginalNodeIfNoNewNodeExistedYet() {
		$referenceNode = $this->getMock('TYPO3\TYPO3CR\Domain\Model\NodeInterface');
		$this->assertThatOriginalNodeIsClonedAndMethodIsCalledOnNewNode('moveBefore', $referenceNode);
	}

	/**
	 * @test
	 */
	public function moveAfterCallsMoveAfterOnTheNewNodeAndClonesTheOriginalNodeIfNoNewNodeExistedYet() {
		$referenceNode = $this->getMock('TYPO3\TYPO3CR\Domain\Model\NodeInterface');
		$this->assertThatOriginalNodeIsClonedAndMethodIsCalledOnNewNode('moveAfter', $referenceNode);
	}

	/**
	 * @test
	 */
	public function setPropertySetsThePropertyOnTheNewNodeAndClonesTheOriginalNodeIfNoNewNodeExistedYet() {
		$this->assertThatOriginalNodeIsClonedAndMethodIsCalledOnNewNode('setProperty', 'propertyName', 'value');
	}

	/**
	 * @test
	 */
	public function hasPropertyCallsHasPropertyOnTheParentNodeFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('hasProperty', 'myProperty');
	}

	/**
	 * @test
	 */
	public function getPropertyCallsGetPropertyOnTheParentNodeFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getProperty', 'myProperty');
	}

	/**
	 * @test
	 */
	public function getPropertiesCallsGetPropertiesOnTheParentNodeFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getProperties');
	}

	/**
	 * @test
	 */
	public function getPropertyNamesCallsGetPropertyNamesOnTheParentNodeFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getPropertyNames');
	}

	/**
	 * @test
	 */
	public function setContentObjectSetsTheContentObjectOnTheNewNodeAndClonesTheOriginalNodeIfNoNewNodeExistedYet() {
		$contentObject = new \stdClass();
		$this->assertThatOriginalNodeIsClonedAndMethodIsCalledOnNewNode('setContentObject', $contentObject);
	}

	/**
	 * @test
	 */
	public function getContentObjectCallsGetContentObjectOnTheParentNodeFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getContentObject');
	}

	/**
	 * @test
	 */
	public function unsetContentObjectUnsetsTheContentObjectOnTheNewNodeAndClonesTheOriginalNodeIfNoNewNodeExistedYet() {
		$this->assertThatOriginalNodeIsClonedAndMethodIsCalledOnNewNode('unsetContentObject');
	}

	/**
	 * @test
	 */
	public function setNodeTypeSetsTheNodeTypeOnTheNewNodeAndClonesTheOriginalNodeIfNoNewNodeExistedYet() {
		$nodeType = $this->getMock('TYPO3\TYPO3CR\Domain\Model\NodeType', array(), array(), '', FALSE);
		$this->assertThatOriginalNodeIsClonedAndMethodIsCalledOnNewNode('setNodeType', $nodeType);
	}

	/**
	 * @test
	 */
	public function getNodeTypeCallsGetNodeTypeOnTheParentNodeFromTheOriginalOrNewNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getNodeType');
	}

	/**
	 * @test
	 */
	public function createNodeCallsCreateNodeOnTheNewNodeOrTheOriginalNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('createNode', 'nodename');
	}

	/**
	 * @test
	 */
	public function getNodeCallsGetNodeOnTheNewNodeOrTheOriginalNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getNode', '/foo/bar');
	}

	/**
	 * @test
	 */
	public function getPrimaryChildNodeCallsGetPrimaryChildNodeOnTheNewNodeOrTheOriginalNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getPrimaryChildNode');
	}

	/**
	 * @test
	 */
	public function getChildNodesCallsGetChildNodesOnTheNewNodeOrTheOriginalNode() {
		$this->assertThatOriginalOrNewNodeIsCalled('getChildNodes', 'MyNodeType');
	}

	/**
	 * @test
	 */
	public function removeCallsRemoveOnTheNewNodeAndClonesTheOriginalNodeIfNoNewNodeExistedYet() {
		$this->assertThatOriginalNodeIsClonedAndMethodIsCalledOnNewNode('remove');
	}

	/**
	 * @test
	 */
	public function setRemovedCallsRemoveMethodIfArgumentIsTrue() {
		$node = $this->getAccessibleMock('TYPO3\TYPO3CR\Domain\Model\ProxyNode', array('remove'), array(), '', FALSE);
		$node->expects($this->once())->method('remove');
		$node->setRemoved(TRUE);
	}

	/**
	 * @test
	 */
	public function cloneOriginalNodeCreatesACloneOfTheOriginalNode() {
		$this->markTestIncomplete('Mocked $newNode needs to be replaced - Node uses new now!');

		$workspace = $this->getMock('TYPO3\TYPO3CR\Domain\Model\Workspace', array(), array(), '', FALSE);
		$context = $this->getMock('TYPO3\TYPO3CR\Domain\Service\Context', array(), array(), '', FALSE);
		$context->expects($this->once())->method('getWorkspace')->will($this->returnValue($workspace));

		$nodeTypeManager = $this->getMock('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', array(), array(), '', FALSE);
		$nodeTypeManager->expects($this->any())->method('hasNodeType')->will($this->returnValue(TRUE));

		$originalNode = $this->getAccessibleMock('TYPO3\TYPO3CR\Domain\Model\Node', array('dummy'), array('/foo', $workspace));
		$originalNode->_set('nodeTypeManager', $nodeTypeManager);

		$newNode = $this->getAccessibleMock('TYPO3\TYPO3CR\Domain\Model\Node', array('dummy'), array('/foo', $workspace));
		$newNode->_set('nodeTypeManager', $nodeTypeManager);

		$nodeRepository = $this->getMock('TYPO3\TYPO3CR\Domain\Repository\NodeRepository', array(), array(), '', FALSE);
		$nodeRepository->expects($this->once())->method('add')->with($newNode);

		$objectManager = $this->getMock('TYPO3\Flow\Object\ObjectManagerInterface');
		$objectManager->expects($this->once())->method('create')->with('TYPO3\TYPO3CR\Domain\Model\Node', '/foo', $workspace)->will($this->returnValue($newNode));

		$proxyNode = $this->getAccessibleMock('TYPO3\TYPO3CR\Domain\Model\ProxyNode', array('dummy'), array(), '', FALSE);
		$proxyNode->_set('objectManager', $objectManager);
		$proxyNode->_set('nodeRepository', $nodeRepository);
		$proxyNode->_set('context', $context);
		$proxyNode->_set('originalNode', $originalNode);

		$className = uniqid('Test');
		eval('
			class ' .$className . ' {
				public $title = "My Title";
				public $body = "My Body";
			}
		');
		$contentObject = new $className;

		$originalNode->setContentObject($contentObject);
		$originalNode->setProperty('title', 'Foo');
		$originalNode->setProperty('body', 'Bar');

		$proxyNode->_call('materializeOriginalNode');

		$this->assertEquals($newNode->getProperties(), $originalNode->getProperties());
		$this->assertEquals($newNode->getIndex(), $originalNode->getIndex());
		$this->assertEquals($newNode->getNodeType(), $originalNode->getNodeType());
		$this->assertSame($newNode->getContentObject(), $originalNode->getContentObject());
		$this->assertSame($context, $newNode->getContext());
	}
}