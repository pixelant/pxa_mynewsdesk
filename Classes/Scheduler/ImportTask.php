<?php
namespace Pixelant\PxaMynewsdesk\Scheduler ;
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
 * @author Jozef Spisiak <jozef@pixelant.se>
 * @package
 * @version $Id:$
 */


class ImportTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
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
	public function execute() {
		#$GLOBALS['TYPO3_DB']->debugOutput = true;
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $this->persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
        $this->importConfigRepository = $this->objectManager->get('\\Pixelant\\PxaMynewsdesk\\Domain\\Repository\\ImportConfigRepository');
        $this->importLogRepository = $this->objectManager->get('\\Pixelant\\PxaMynewsdesk\\Domain\\Repository\\ImportLogRepository');

		$config = $this->getImportConf();
		foreach ($config as $conf) {
			if ($conf["news_table"] == "tx_news_domain_model_news" && !\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) continue;
			if ($conf["news_table"] == "tt_news" && !\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_news')) continue;
			
			if ($conf["news_table"] == "tx_news_domain_model_news")
				/** @var \Pixelant\PxaMynewsdesk\Domain\Service\NewsImportService $importService */
				$importService = $this->objectManager->get('Pixelant\PxaMynewsdesk\Domain\Service\NewsImportService');

			$newsItems = $this->getFeed($conf["news_url"]);

			if(!is_array($newsItems["items"]["item"])) continue;

			foreach($newsItems["items"]["item"] as $newsItem ) {

				$hash = \TYPO3\CMS\Core\Utility\GeneralUtility::hmac($newsItem["id"]);
// TODO: get rid of the log table, when we make sure that tt_news contain the ID from mynewsdesk somehow
				$logCount = $this->importLogRepository->countByStringProperties(array("hash", "newstable"), array($hash, $conf["news_table"]), intval($conf["news_pid"]));
				if($logCount) continue;
				
				switch ($conf["news_table"]) {
					case 'tx_news_domain_model_news':

						//@TODO: Check if the news has media and don't add it to @insertArray if it doesn't have

						$fieldContent = str_replace(PHP_EOL,' ', $newsItem["body"]);
						$categories = explode(",", $conf["news_categories"]);
						$insertArray = array(
							'pid' => intval($conf["news_pid"]),
							'sys_language_uid' => 0, // TODO: add language possibilty
							'title' => $newsItem["header"],
							'teaser' => $newsItem["description"],
							'bodytext' => $fieldContent,
							'datetime' => strtotime($newsItem["created_at"]),
							/* 'author' => $newsItem["contact_people"]["contact_person"]["name"],
							'author_email' => $newsItem["contact_people"]["contact_person"]["email"], */
							'media' => $this->getMedia($newsItem["image"],$newsItem["header"]),
							'type' => $conf["news_type"],
							'crdate' => time(),
							'tstamp' => time(),
							'import_id' => $newsItem['id'],
							'import_source' => 'mynewsdesk'
						);

                        /**
                         * import tags is there are
                         */
						if ($conf['importTagsEnabled'] && $newsItem['tags']) {
                            $insertArray['tags'] = $newsItem['tags']['tag'];
                        }

						if(is_array($newsItem["contact_people"]["contact_person"])){
							$insertArray['author'] = $newsItem["contact_people"]["contact_person"]["name"];
                                                        $insertArray['author_email'] = $newsItem["contact_people"]["contact_person"]["email"];
						}

						$insertArray['externalurl'] = $newsItem["url"]; // link to the full article


						$newsId = true;
						if(is_array($categories) && $newsId) {
							$categoriesArray = array();
							foreach($categories as $catId) {
								$categoriesArray[] = $catId;
							}
						}
						$insertArray['categories'] = $categoriesArray;
						$importService->import(array($insertArray));
						// cleanup
						unlink(PATH_site . $insertArray["media"][0]["image"]);
						break;

					case 'tt_news':
					// TODO: check for duplicates in the tt_news based on uid
						$categories = explode(",", $conf["news_categories"]);
						$insertArray = array(
							'pid' => intval($conf["news_pid"]),
							'title' => $newsItem["header"],
							'short' => $newsItem["summary"],
							'bodytext' => $newsItem["body"],
							'datetime' => strtotime($newsItem["published_at"]),
							'author' => $newsItem["name"],
							'type' => $conf["news_type"],
							'crdate' => time(),
							'tstamp' => time()
						);
						if ($conf['news_type'] == 2) $insertArray['ext_url'] = $newsItem["url"];

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
		return TRUE;
	}

	private function getImportConf() {
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
                "importTagsEnabled" => $importConfig->isImportTagsEnabled()
			);
		}
				
		return $config;  
	}

	protected function getFeed($url) {
		$jsonPayload = \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($url); 

		return json_decode($jsonPayload, TRUE);
	}

	protected function getMedia($imageUrl,$title) {
		$filename = pathinfo($imageUrl, PATHINFO_BASENAME);
		$imagePath = 'typo3temp/mynewsdesk_' . $filename;
		copy($imageUrl,PATH_site . $imagePath);
		$media = array();
		$media[] = array(
			'title' => $title,
			'alt' => $title,
			'caption' => $title, /* @TODO: make configurable */
			'image' => $imagePath,
			'type' => 0,
			'showinpreview' => 1
		);
		return $media;
	}


}