<?php
declare(strict_types=1);

namespace Pixelant\PxaMynewsdesk\Domain\Service;

use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Model\Tag;
use GeorgRinger\News\Domain\Repository\TagRepository;
use GeorgRinger\News\Service\Transliterator\Transliterator;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\DuplicationBehavior;
use TYPO3\CMS\Core\Resource\File as CoreFile;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\File;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class NewsImportService
 * @package Pixelant\PxaMynewsdesk\Domain\Service
 */
class NewsImportService extends \GeorgRinger\News\Domain\Service\NewsImportService
{
    /**
     * @var TagRepository
     */
    protected $tagsRepository;

    /**
     * Cache found tags
     *
     * @var Tag[]
     */
    protected $tagsCache = [];

    /**
     * @param TagRepository $tagRepository
     */
    public function injectTagsRepository(TagRepository $tagRepository)
    {
        $this->tagsRepository = $tagRepository;
    }

    /**
     * @param News $news
     * @param array $importItem
     * @param array $importItemOverwrite
     * @return News
     */
    protected function hydrateNewsRecord(
        News $news,
        array $importItem,
        array $importItemOverwrite
    ) {
        // Download images if missing
        $this->prepareMediaField($news, $importItem);

        // Run parent method
        $news = parent::hydrateNewsRecord($news, $importItem, $importItemOverwrite);

        // Attach tags
        $this->addNewsTags($news, $importItem);

        // Update news slug. Persis so we have UID to update
        $this->persistenceManager->persistAll();
        $this->updateSlugField($news);

        return $news;
    }

    /**
     * Set slug field for news records
     *
     * @param News $news
     */
    protected function updateSlugField(News $news): void
    {
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_news_domain_model_news')
            ->update(
                'tx_news_domain_model_news',
                [
                    'path_segment' => $this->generateSlug($news->getTitle()),
                ],
                ['uid' => $news->getUid()]
            );
    }

    /**
     * Generate slug
     *
     * @param string $string
     * @return string
     */
    protected function generateSlug(string $string): string
    {
        return Transliterator::urlize($string);
    }

    /**
     *
     * @param News $news
     * @param array $importItem
     */
    protected function prepareMediaField(News $news, array &$importItem): void
    {
        $medias = [];
        if (!empty($importItem['media'])) {
            $fileName = basename($importItem['media']);

            if (!$this->newsHasImage($news, $fileName)) {
                $medias[] = $this->createMediaArray($importItem['title'], $fileName, $importItem['media']);
            }
        }

        $importItem['media'] = $medias;
    }

    /**
     * Download file media and return array with info
     *
     * @param string $title
     * @param string $fileName
     * @param string $mediaUrl
     * @return array
     */
    protected function createMediaArray(string $title, string $fileName, string $mediaUrl): array
    {
        $tempFile = GeneralUtility::tempnam('mynewdesk_');
        copy($mediaUrl, $tempFile);

        /** @var CoreFile $newFile */
        $newFile = $this->getResourceStorage()->addFile(
            $tempFile,
            $this->getImportFolder(),
            $fileName,
            DuplicationBehavior::REPLACE
        );

        return [
            'title' => $title,
            'alt' => $title,
            'caption' => $title,
            'image' => $newFile->getCombinedIdentifier(),
            'showinpreview' => true
        ];
    }

    /**
     * Check if news record already has image with given file name
     *
     * @param News $news
     * @param string $image
     * @return bool
     */
    protected function newsHasImage(News $news, string $image): bool
    {
        $newsImages = array_map(
            function (File $file) {
                return $file->getOriginalResource()->getName();
            },
            $news->getFalMedia()->toArray()
        );

        return in_array($image, $newsImages);
    }

    /**
     * Add tags to news
     *
     * @param News $news
     * @param array $importItem
     */
    protected function addNewsTags(News $news, array $importItem): void
    {
        if (isset($importItem['tags']) && is_array($importItem['tags'])) {
            $tags = $this->objectManager->get(ObjectStorage::class);

            foreach ($importItem['tags'] as $tag) {
                $tagObject = $this->getTag($tag, $news->getPid());
                $tags->attach($tagObject);
            }

            $news->setTags($tags);
        }
    }

    /**
     * Get tag object
     *
     * @param string $tagTitle
     * @param int $pid
     * @return Tag
     */
    protected function getTag(string $tagTitle, int $pid): Tag
    {
        $hash = md5($tagTitle);

        if (array_key_exists($hash, $this->tagsCache)) {
            return $this->tagsCache[$hash];
        }

        $query = $this->tagsRepository->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching($query->logicalAnd([
            $query->equals('title', $tagTitle),
            $query->equals('pid', $pid)
        ]));

        $tag = $query->execute()->getFirst();

        if ($tag === null) {
            /** @var Tag $tag */
            $tag = $this->objectManager->get(Tag::class);
            $tag->setTitle($tagTitle);
            $tag->setPid($pid);

            $this->tagsRepository->add($tag);
        }

        $this->tagsCache[$hash] = $tag;

        return $tag;
    }

    /**
     * Create import folder if doesn't exist
     *
     * @return Folder
     */
    protected function getImportFolder()
    {
        $importFolder = $this->emSettings->getResourceFolderImporter();
        if (!$this->getResourceStorage()->hasFolder($importFolder)) {
            $this->getResourceStorage()->createFolder($importFolder);
        }

        return parent::getImportFolder();
    }
}
