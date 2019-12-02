<?php
declare(strict_types=1);

namespace Pixelant\PxaMynewsdesk\Service;

use Pixelant\PxaMynewsdesk\Domain\Model\ImportConfig;
use Pixelant\PxaMynewsdesk\Domain\Repository\ImportConfigRepository;
use Pixelant\PxaMynewsdesk\Domain\Repository\NewsImportRepository;
use Pixelant\PxaMynewsdesk\Domain\Service\NewsImportService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class ImportService
 * @package Pixelant\PxaMynewsdesk\Service
 */
class ImportService
{
    /**
     * Import source name
     */
    const SOURCE_NAME = 'mynewsdesk';

    /**
     * @var ObjectManager
     */
    protected $objectManager = null;

    /**
     * @var ImportConfigRepository
     */
    protected $importConfigRepository = null;

    /**
     * @var NewsImportRepository
     */
    protected $newsImportRepository = null;

    /**
     * @var NewsImportService
     */
    protected $newsImportService = null;

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->newsImportRepository = GeneralUtility::makeInstance(NewsImportRepository::class);

        $this->importConfigRepository = $this->objectManager->get(ImportConfigRepository::class);
        $this->newsImportService = $this->objectManager->get(NewsImportService::class);
    }

    /**
     * Import logic
     *
     * @param array $configs Uids of configurations
     */
    public function run(array $configs): void
    {
        $configurations = $this->importConfigRepository->findAllByUids($configs);

        /** @var ImportConfig $configuration */
        foreach ($configurations as $configuration) {
            $feedItems = $this->fetchFeedItems($configuration);

            $insertItems = [];
            foreach ($feedItems as $feedItem) {
                // TODO. Should it run update?
                if (!$this->importItemExist($configuration, $feedItem['id'])) {
                    $insertItems[] = $this->createImportItemArray($configuration, $feedItem);
                }
            }

            if (!empty($insertItems)) {
                $this->newsImportService->import($insertItems);
            }
        }
    }

    /**
     * Check if import item already exist
     *
     * @param ImportConfig $importConfig
     * @param string $id
     * @return bool
     */
    protected function importItemExist(ImportConfig $importConfig, string $id): bool
    {
        return $this->newsImportRepository->countByImportId($id, self::SOURCE_NAME, $importConfig->getStorage()) > 0;
    }

    /**
     * Create import array for single feed item
     *
     * @param ImportConfig $importConfig
     * @param array $feedItem
     * @return array
     */
    protected function createImportItemArray(ImportConfig $importConfig, array $feedItem): array
    {
        $time = time();
        $insertArray = [
            'tstamp' => $time,
            'crdate' => $time,
            'pid' => $importConfig->getStorage(),
            'categories' => GeneralUtility::intExplode(',', $importConfig->getCategories()),
            'type' => $importConfig->getType(),
            'sys_language_uid' => 0, // TODO: add language possibility
            'title' => $feedItem['header'],
            'teaser' => $feedItem['description'] ?? '',
            'bodytext' => str_replace(PHP_EOL, ' ', $feedItem['body'] ?? ''),
            'datetime' => strtotime($feedItem['created_at']),
            'media' => $feedItem['image'],
            'externalurl' => $feedItem['url'],
            'import_id' => $feedItem['id'],
            'import_source' => self::SOURCE_NAME
        ];
        if ($importConfig->isImportTags() && $feedItem['tags']) {
            $insertArray['tags'] = $feedItem['tags']['tag'];
        }

        if (isset($feedItem['contact_people']['contact_person'])
            && is_array($feedItem['contact_people']['contact_person'])
        ) {
            list('name' => $name, 'email' => $email) = $feedItem['contact_people']['contact_person'];

            $insertArray['author'] = $name;
            $insertArray['author_email'] = $email;
        }

        return $insertArray;
    }

    /**
     * Load feed items
     *
     * @param ImportConfig $importConfig
     * @return array
     */
    protected function fetchFeedItems(ImportConfig $importConfig): array
    {
        $json = GeneralUtility::getUrl($importConfig->getSourceUrl());

        $data = json_decode($json, true);

        return $data['items']['item'] ?? [];
    }
}
