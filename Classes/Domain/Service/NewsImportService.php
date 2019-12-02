<?php

namespace Pixelant\PxaMynewsdesk\Domain\Service;

use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Model\Tag;
use GeorgRinger\News\Domain\Repository\TagRepository;
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
        $news = parent::hydrateNewsRecord($news, $importItem, $importItemOverwrite);

        if (isset($importItem['tags']) && is_array($importItem['tags'])) {
            $tags = $this->objectManager->get(ObjectStorage::class);

            foreach ($importItem['tags'] as $tag) {
                $tagObject = $this->getTag($tag, $news->getPid());
                $tags->attach($tagObject);
            }

            $news->setTags($tags);
        }

        return $news;
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
}
