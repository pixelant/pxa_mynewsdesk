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

namespace TYPO3\PxaMynewsdesk\Task ;

//require_once(t3lib_extMgm::extPath('pxa_mynewsdesk') . 'Classes/Domain/Repository/ImportConfigRepository.php');
//require_once(t3lib_extMgm::extPath('pxa_mynewsdesk') . 'Classes/Domain/Repository/ImportLogRepository.php');
//require_once(t3lib_extMgm::extPath('pxa_mynewsdesk') . 'Classes/Domain/Model/ImportLog.php');
//require_once(t3lib_extMgm::extPath('pxa_mynewsdesk') . 'Classes/Domain/Model/ImportConfig.php');


class ImportTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{



	/**
	 * @var Tx_Extbase_Object_ObjectManager
	 */
	protected $objectManager;

	/**
	 * @var Tx_Extbase_Persistence_Manager
	 */
	protected $persistenceManager;

	/**
     * @var Tx_PxaMynewsdesk_Domain_Repository_ImportConfigRepository
     */
    protected $importConfigRepository;

	/**
     * @var Tx_PxaMynewsdesk_Domain_Repository_ImportLogRepository
     */
    protected $importLogRepository;    
	
	/**
	 * Function executed from the Scheduler.
	 *
	 * @return	void
	 */
	public function execute()
	{
		$this->objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
		$this->persistenceManager = $this->objectManager->get('Tx_Extbase_Persistence_Manager');

	    $this->importConfigRepository = $this->objectManager->get('Tx_PxaMynewsdesk_Domain_Repository_ImportConfigRepository');
	    $this->importLogRepository = $this->objectManager->get('Tx_PxaMynewsdesk_Domain_Repository_ImportLogRepository');
		$config = $this->getImportConf();
		foreach ($config as $conf) {
			$xmlContent = "";
			if(trim($conf["news_url"])) $xmlContent = t3lib_div::getUrl(trim($conf["news_url"]));
			if($xmlContent) {
				$newsItems = array();
				$this->getArrValuesByKey(t3lib_div::xml2array($xmlContent), "item", &$newsItems);
				if(is_array($newsItems)) {
					foreach($newsItems as $newsItem ) {
						$hash = t3lib_div::hmac($newsItem["title"].$newsItem["pubDate"]);
						$logCount = $this->importLogRepository->countByStringProperties(array("hash", "newstable"), array($hash, $conf["news_table"]), intval($conf["news_pid"]));
#						var_dump($logCount);
						if(!$logCount) {
							switch ($conf["news_table"]) {
								case 'tt_news':
									if(t3lib_extMgm::isLoaded('tt_news')) {
										$categories = explode(",", $conf["news_categories"]);
										$insertArray = array(
											'pid' => intval($conf["news_pid"]),
		    								'title' => $newsItem["title"],
		    								'short' => $newsItem["description"],
		    								'bodytext' => $newsItem["description"],
		    								'datetime' => strtotime($newsItem["pubDate"]),
		    								'ext_url' => $newsItem["link"],
		    								'author' => $newsItem["dc:creator"],
		    								'type' => 2, // external url
								        	'crdate' => mktime(),
       										'tstamp' => mktime()
										);
										if(is_array($categories)) $insertArray["category"] = count($categories);  
										$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tt_news', $insertArray);
										$newsId = $GLOBALS['TYPO3_DB']->sql_insert_id();
										if(is_array($categories) && $newsId) {
											foreach($categories as $catId) {
												$insertArray = array(
													'uid_local' => $newsId,
													'uid_foreign' => $catId,
													'sorting' => 1
												);
												$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tt_news_cat_mm', $insertArray);
											}
										}
									}	
									break;
								default:
									// import into news ext will be here
									break;
							}
							if($newsId) {
								$newImportLog = $this->objectManager->get('Tx_PxaMynewsdesk_Domain_Model_ImportLog');
								$newImportLog->setHash($hash);
								$newImportLog->setNewstable($conf["news_table"]);
								$newImportLog->setNewsId($newsId);
								$newImportLog->setNewsPid($conf["news_pid"]);
								$this->importLogRepository->add($newImportLog);
								$this->persistenceManager->persistAll();
							}									
#							echo($newsId);
						}
					}
					 
				}
			}
		}
		return TRUE;
	}

	public function getArrValuesByKey($arr, $searchKey, &$resultArr) {
       foreach($arr as $key => $val) {
        if($key==$searchKey) {
          $resultArr[] = $val;
        } else {
          if( is_array($val) )  {
            $this->getArrValuesByKey($val, $searchKey, &$resultArr);
          }
        }  
       }
    }

	private function getImportConf()
    {
        $configUids =  t3lib_div::trimExplode(',', $this->pxamynewsdeskconfiguration, TRUE);
        $config = array();
        
        $importConfigs = $this->importConfigRepository->findAllByUids(implode(',', $configUids));
		foreach($importConfigs as $importConfig) {
			$config[] = array(
				"news_table" => $importConfig->getNewstable(),
				"news_pid" => ($importConfig->getNewpid()?$importConfig->getNewpid():$importConfig->getPid()),
				"news_categories" => $importConfig->getNewscat(),
				"news_url" => $importConfig->getNewsurl(),
				"mapping" => $importConfig->getMapping(),
				"pid" => $importConfig->getPid(),
			);
		}
        
      	return $config;  
    }

}

?>