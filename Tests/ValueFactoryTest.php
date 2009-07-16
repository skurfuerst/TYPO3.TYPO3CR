<?php
declare(ENCODING = 'utf-8');
namespace F3\TYPO3CR;

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
 * Tests for the ValueFactory implementation of TYPO3CR
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class ValueFactoryTest extends \F3\Testing\BaseTestCase {

	/**
	 * @var \F3\PHPCR\ValueFactory
	 */
	protected $valueFactory;

	/**
	 * Set up the test environment
	 */
	public function setUp() {
		$this->valueFactory = new \F3\TYPO3CR\ValueFactory($this->objectFactory, $this->getMock('F3\PHPCR\SessionInterface'));
	}

	/**
	 * Checks if createValue can guess the STRING type
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @test
	 */
	public function createValueFromStringGuessesCorrectType() {
		$value = $this->valueFactory->createValue('This is a string');
		$this->assertEquals($value->getType(), \F3\PHPCR\PropertyType::STRING, 'New Value object was not of type STRING.');
	}

	/**
	 * Checks if createValue can guess the LONG type
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @test
	 */
	public function createValueFromLongGuessesCorrectType() {
		$value = $this->valueFactory->createValue(10);
		$this->assertEquals($value->getType(), \F3\PHPCR\PropertyType::LONG, 'New Value object was not of type LONG.');
	}

	/**
	 * Checks if createValue can guess the DOUBLE type
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @test
	 */
	public function createValueFromDoubleGuessesCorrectType() {
		$value = $this->valueFactory->createValue(1.5);
		$this->assertEquals($value->getType(), \F3\PHPCR\PropertyType::DOUBLE, 'New Value object was not of type DOUBLE.');
	}

	/**
	 * Checks if createValue can guess the BOOLEAN type
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @test
	 */
	public function createValueFromBooleanGuessesCorrectType() {
		$value = $this->valueFactory->createValue(FALSE);
		$this->assertEquals($value->getType(), \F3\PHPCR\PropertyType::BOOLEAN, 'New Value object was not of type BOOLEAN.');
	}

	/**
	 * Checks if createValue can guess the DATE type
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @test
	 */
	public function createValueFromDateGuessesCorrectType() {
		$value = $this->valueFactory->createValue(new \DateTime('2007-09-22'));
		$this->assertEquals($value->getType(), \F3\PHPCR\PropertyType::DATE, 'New Value object was not of type DATE.');
	}

	/**
	 * Checks if createValue can guess the BINARY type
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @test
	 */
	public function createValueFromBinaryGuessesCorrectType() {
		$value = $this->valueFactory->createValue(new \F3\TYPO3CR\Binary());
		$this->assertEquals($value->getType(), \F3\PHPCR\PropertyType::BINARY, 'New Value object was not of type BINARY.');
	}

	/**
	 * Checks if type conversion works, if requested using createValue()
	 * @test
	 */
	public function createValueConvertsTypeToBooleanIfRequested() {
		$value = $this->valueFactory->createValue('Some test string', \F3\PHPCR\PropertyType::BOOLEAN);
		$this->assertSame($value->getType(), \F3\PHPCR\PropertyType::BOOLEAN, 'New Value object was not of type BOOLEAN.');
	}

	/**
	 * Checks if createValue can guess the REFERENCE type
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @test
	 */
	public function createValueFromNodeGuessesCorrectType() {
		$mockNode = $this->getMock('F3\PHPCR\NodeInterface');
		$mockNode->expects($this->any())->method('getIdentifier')->will($this->returnValue(\F3\FLOW3\Utility\Algorithms::generateUUID()));
		$mockSession = $this->getMock('F3\TYPO3CR\Session', array(), array(), '', FALSE);
		$mockSession->expects($this->any())->method('hasIdentifier')->will($this->returnValue(TRUE));
		$valueFactory = new \F3\TYPO3CR\ValueFactory($this->objectFactory, $mockSession);

		$value = $valueFactory->createValue($mockNode);
		$this->assertEquals($value->getType(), \F3\PHPCR\PropertyType::REFERENCE, 'New Value object was not of type REFERENCE.');
		$this->assertEquals($value->getString(), $mockNode->getIdentifier(), 'The Value did not contain the Identifier of the passed Node object.');
	}

	/**
	 * Checks if createValue returns REFERENCE type for Node value if requested
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @test
	 */
	public function createValueFromNodeWithRequestedReferenceTypeWorks() {
		$mockNode = $this->getMock('F3\PHPCR\NodeInterface');
		$mockNode->expects($this->any())->method('getIdentifier')->will($this->returnValue(\F3\FLOW3\Utility\Algorithms::generateUUID()));
		$mockSession = $this->getMock('F3\TYPO3CR\Session', array(), array(), '', FALSE);
		$mockSession->expects($this->any())->method('hasIdentifier')->will($this->returnValue(TRUE));
		$valueFactory = new \F3\TYPO3CR\ValueFactory($this->objectFactory, $mockSession);

		$value = $valueFactory->createValue($mockNode, \F3\PHPCR\PropertyType::REFERENCE);
		$this->assertEquals($value->getType(), \F3\PHPCR\PropertyType::REFERENCE, 'New Value object was not of type REFERENCE.');
		$this->assertEquals($value->getString(), $mockNode->getIdentifier(), 'The Value did not contain the Identifier of the passed Node object.');
	}

	/**
	 * Checks if createValue create a WEAKREFERENCE if $weak is TRUE
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @test
	 */
	public function createValueFromNodeObservesWeakParameter() {
		$mockNode = $this->getMock('F3\PHPCR\NodeInterface');
		$mockNode->expects($this->any())->method('getIdentifier')->will($this->returnValue(\F3\FLOW3\Utility\Algorithms::generateUUID()));
		$value = $this->valueFactory->createValue($mockNode, NULL, TRUE);
		$this->assertEquals($value->getType(), \F3\PHPCR\PropertyType::WEAKREFERENCE, 'New Value object was not of type WEAKREFERENCE.');
		$this->assertEquals($value->getString(), $mockNode->getIdentifier(), 'The Value did not contain the Identifier of the passed Node object.');
	}
}
?>