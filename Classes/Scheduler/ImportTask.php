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

namespace Pixelant\PxaMynewsdesk\Scheduler ;

require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pxa_mynewsdesk') . 'Classes/Domain/Repository/ImportConfigRepository.php');
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pxa_mynewsdesk') . 'Classes/Domain/Repository/ImportLogRepository.php');
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pxa_mynewsdesk') . 'Classes/Domain/Model/ImportLog.php');
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pxa_mynewsdesk') . 'Classes/Domain/Model/ImportConfig.php');


class ImportTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{

	/**
	 * @var /TYPO3/CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager;


    /**
     *  persistenceManager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     *  importConfigRepository
     *
     * @var \Pixelant\PxaMynewsdesk\Domain\Repository\importConfigRepository
     * @inject
     */

    protected $importConfigRepository;

    /**
     *  importLogRepository
     *
     * @var \Pixelant\PxaMynewsdesk\Domain\Repository\importLogRepository
     */

    protected $importLogRepository;
	
	/**
	 * Function executed from the Scheduler.
	 *
	 * @return	void
	 */

	public function execute()
	{
        $GLOBALS['TYPO3_DB']->debugOutput = true;

        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
		$this->persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
	    $this->importConfigRepository = $this->objectManager->get('\\Pixelant\\PxaMynewsdesk\\Domain\\Repository\\ImportConfigRepository');
	    $this->importLogRepository = $this->objectManager->get('\\Pixelant\\PxaMynewsdesk\\Domain\\Repository\\ImportLogRepository');
		$config = $this->getImportConf();
		foreach ($config as $conf) {
			$xmlContent = "";
			if(trim($conf["news_url"])) $xmlContent = \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl(trim($conf["news_url"]));
			if($xmlContent) {
				//  $newsItems = array();

				//$this->getArrValuesByKey(\TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($xmlContent), "item", &$newsItems);
                $newsItems = $this->getFeedWrapper(array('url' => $conf["news_url"]));



				if(is_array($newsItems)) {
					foreach($newsItems as $newsItem ) {

						$hash = \TYPO3\CMS\Core\Utility\GeneralUtility::hmac($newsItem["title"].$newsItem["pubDate"]);
						$logCount = $this->importLogRepository->countByStringProperties(array("hash", "newstable"), array($hash, $conf["news_table"]), intval($conf["news_pid"]));
						if(!$logCount) {

							switch ($conf["news_table"]) {
								case 'tx_news_domain_model_news':
									if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) {
										$categories = explode(",", $conf["news_categories"]);
										$insertArray = array(
											'pid' => intval($conf["news_pid"]),
		    								'title' => $newsItem["title"],
		    								'teaser' => $newsItem["description"],
		    								'bodytext' => $newsItem["description"],
		    								'datetime' => strtotime($newsItem["date"]),
		    								//'ext_url' => $newsItem["link"],
		    								'author' => $newsItem["dc:creator"],
		    								'type' => $conf["news_type"], // external url
								        	'crdate' => mktime(),
       										'tstamp' => mktime()
										);


                                        $GLOBALS['TYPO3_DB']->debugOutput = true;

										$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_news_domain_model_news', $insertArray);


										$newsId = $GLOBALS['TYPO3_DB']->sql_insert_id();
										if(is_array($categories) && $newsId) {
											foreach($categories as $catId) {
												$insertArray = array(
													'uid_local' => $newsId,
													'uid_foreign' => $catId,
													'sorting' => 1
												);
												$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_news_domain_model_news_category_mm', $insertArray);
											}
										}
									}
									break;

                                    case 'tt_news':
                                        if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_news')) {
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
								$newImportLog = $this->objectManager->get('Pixelant\PxaMynewsdesk\Domain\Model\ImportLog');
								$newImportLog->setHash($hash);
								$newImportLog->setNewstable($conf["news_table"]);
								$newImportLog->setNewsId($newsId);
								$newImportLog->setNewsPid($conf["news_pid"]);
								$this->importLogRepository->add($newImportLog);
								$this->persistenceManager->persistAll();
							}
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
        $configUids =  \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->pxamynewsdeskconfiguration, TRUE);
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
				"news_type" => $importConfig->getNewstype(),
			);
		}
        
      	return $config;  
    }

    public function getFeedWrapper($data=array())
    {
        foreach ($data as $service => $feed)
        {
                $rss_contents = $this->getFeed($feed);
                return unserialize($rss_contents);
        }
    }

    protected function getFeed($url)
    {
        $xml = array();
        $doc = new \DOMDocument();
        $doc->load($url);
        foreach ($doc->getElementsByTagName('item') as $node)
        {
            $rss = array (
                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                'description' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
                'dc:creator' => $node->getElementsByTagName('dc:creator')->item(0)->nodeValue
            );
            array_push($xml, $rss);
        } //endforeach element ids
        return serialize($xml);
    }

}



?>