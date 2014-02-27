<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2013 Pixelant AB (developers@pixelant.se)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * @author Maksym Leskiv <maksym@pixelant.se>
 * @package
 * @version $Id:$
 */

class tx_pxamynewsdesk_cleanertask extends tx_scheduler_Task
{

	/**
	 * Function executed from the Scheduler.
	 *
	 * @return	void
	 */
	public function execute()
	{
		// delete records from `tx_pxamynewsdesk_domain_model_importlog` for relating missing/deleted tt_news records
		if(t3lib_extMgm::isLoaded('tt_news')) {
			$query = 'SELECT t1.uid FROM `tx_pxamynewsdesk_domain_model_importlog` t1 JOIN `tt_news` t2 ON  t1.newsid = t2.uid WHERE t1.newspid = t2.pid';
			$res = $GLOBALS['TYPO3_DB']->sql_query($query);
			$uids = array();		
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
				$uids[] = $row["uid"];
			}

			if( count($uids) ) {
				$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_pxamynewsdesk_domain_model_importlog', 'uid NOT IN (' . implode(",", $uids) . ')');
#				if ($res === FALSE) {
#					t3lib_div::sysLog('Could run query "' . $query . '": ' . $GLOBALS['TYPO3_DB']->sql_error(), 'Core', t3lib_div::SYSLOG_SEVERITY_ERROR);
#				}
			}
		}	
		// delete records from `tx_pxamynewsdesk_domain_model_importlog` for relating missing/deleted news records
#		...		
		return TRUE;
	}

}

?>