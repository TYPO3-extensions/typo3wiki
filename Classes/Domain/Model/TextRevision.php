<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Bastian Bringenberg <typo3@bastian-bringenberg.de>, Bastian Bringenberg
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package typo3wiki
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Typo3wiki_Domain_Model_TextRevision extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * writeDate
	 *
	 * @var DateTime
	 * @validate NotEmpty
	 */
	protected $writeDate;

	/**
	 * unrenderedText
	 *
	 * @var string
	 */
	protected $unrenderedText;

	/**
	 * renderedText
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $renderedText;

	/**
	 * changes
	 *
	 * @var string
	 */
	protected $changes;

	/**
	 * owner
	 *
	 * @var Tx_Typo3wiki_Domain_Model_User
	 * @lazy
	 */
	protected $owner;

	/**
	 * Returns the writeDate
	 *
	 * @return DateTime $writeDate
	 */
	public function getWriteDate() {
		return $this->writeDate;
	}

	/**
	 * Sets the writeDate
	 *
	 * @param DateTime $writeDate
	 * @return void
	 */
	public function setWriteDate($writeDate) {
		$this->writeDate = $writeDate;
	}

	/**
	 * Returns the unrenderedText
	 *
	 * @return string $unrenderedText
	 */
	public function getUnrenderedText() {
		return $this->unrenderedText;
	}

	/**
	 * Sets the unrenderedText
	 *
	 * @param string $unrenderedText
	 * @return void
	 */
	public function setUnrenderedText($unrenderedText) {
		$this->unrenderedText = $unrenderedText;
	}

	/**
	 * Returns the renderedText
	 *
	 * @return string $renderedText
	 */
	public function getRenderedText() {
		return $this->renderedText;
	}

	/**
	 * Sets the renderedText
	 *
	 * @param string $renderedText
	 * @return void
	 */
	public function setRenderedText($renderedText) {
		$this->renderedText = $renderedText;
	}

	/**
	 * Returns the changes
	 *
	 * @return string $changes
	 */
	public function getChanges() {
		return $this->changes;
	}

	/**
	 * Sets the changes
	 *
	 * @param string $changes
	 * @return void
	 */
	public function setChanges($changes) {
		$this->changes = $changes;
	}

	/**
	 * Returns the owner
	 *
	 * @return Tx_Typo3wiki_Domain_Model_User $owner
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * Sets the owner
	 *
	 * @param Tx_Typo3wiki_Domain_Model_User $owner
	 * @return void
	 */
	public function setOwner(Tx_Typo3wiki_Domain_Model_User $owner) {
		$this->owner = $owner;
	}

}
?>