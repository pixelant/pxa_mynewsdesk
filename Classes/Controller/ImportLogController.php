<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Maksym Leskiv <maksym@pixelant.se>, Pixelant AB
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
 * @package pxa_mynewsdesk
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */

namespace Pixelant\PxaMynewsdesk\Controller ;

class ImportLogController extends \TYPO3\CMS\Extbase\MVC\Controller\ActionController {

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$importLogs = $this->importLogRepository->findAll();
		$this->view->assign('importLogs', $importLogs);
	}

	/**
	 * action show
	 *
	 * @param Tx_PxaMynewsdesk_Domain_Model_ImportLog
	 * @return void
	 */
	public function showAction(Tx_PxaMynewsdesk_Domain_Model_ImportLog $importLog) {
		$this->view->assign('importLog', $importLog);
	}

	/**
	 * action new
	 *
	 * @param Tx_PxaMynewsdesk_Domain_Model_ImportLog $newImportLog
	 * @dontvalidate $newImportLog
	 * @return void
	 */
	public function newAction(Tx_PxaMynewsdesk_Domain_Model_ImportLog $newImportLog = NULL) {
		$this->view->assign('newImportLog', $newImportLog);
	}

	/**
	 * action create
	 *
	 * @param Tx_PxaMynewsdesk_Domain_Model_ImportLog $newImportLog
	 * @return void
	 */
	public function createAction(Tx_PxaMynewsdesk_Domain_Model_ImportLog $newImportLog) {
		$this->importLogRepository->add($newImportLog);
		$this->flashMessageContainer->add('Your new ImportLog was created.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param Tx_PxaMynewsdesk_Domain_Model_ImportLog $importLog
	 * @return void
	 */
	public function deleteAction(Tx_PxaMynewsdesk_Domain_Model_ImportLog $importLog) {
		$this->importLogRepository->remove($importLog);
		$this->flashMessageContainer->add('Your ImportLog was removed.');
		$this->redirect('list');
	}

}
?>