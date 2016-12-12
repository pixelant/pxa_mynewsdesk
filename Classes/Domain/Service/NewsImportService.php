<?php
/**
 * Created by PhpStorm.
 * User: anjey
 * Date: 12.12.16
 * Time: 11:17
 */

namespace Pixelant\PxaMynewsdesk\Domain\Service;

/**
 * Class NewsImportService
 * @package Pixelant\PxaMynewsdesk\Domain\Service
 */
class NewsImportService extends \Tx_News_Domain_Service_NewsImportService
{
    /**
     * @var \Tx_News_Domain_Repository_TagRepository
     */
    protected $tagsRepository;

    /**
     * @var array
     */
    protected $tagsCache = array();

    public function __construct()
    {
        parent::__construct();
        $this->tagsRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_News_Domain_Repository_TagRepository');
    }

    /**
     * @param \Tx_News_Domain_Model_News $news
     * @param array $importItem
     * @param array $importItemOverwrite
     * @return void
     */
    protected function hydrateNewsRecord(
        \Tx_News_Domain_Model_News $news,
        array $importItem,
        array $importItemOverwrite
    ) {
        parent::hydrateNewsRecord($news, $importItem, $importItemOverwrite);

        if (isset($importItem['tags']) && is_array($importItem['tags'])) {
            $tags = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\ObjectStorage') ;

            foreach ($importItem['tags'] as $tag) {
                $tagObject = $this->getTag($tag, $news->getPid());
                $tags->attach($tagObject);
            }

            $news->setTags($tags);
        }
    }

    /**
     * @param string $tagTitle
     * @param int $pid
     * @return \Tx_News_Domain_Model_Tag
     */
    protected function getTag($tagTitle, $pid)
    {
        $hash = md5($tagTitle);

        if(!array_key_exists($hash, $this->tagsCache)) {
            $query = $this->tagsRepository->createQuery();
            $query->getQuerySettings()->setRespectStoragePage(false);

            $query->matching($query->logicalAnd(
                $query->equals('title', $tagTitle),
                $query->equals('pid', $pid)
            ));

            $tag = $query->execute()->getFirst();

            if ($tag === null) {
                /** @var \Tx_News_Domain_Model_Tag $tag */
                $tag = $this->objectManager->get('Tx_News_Domain_Model_Tag');
                $tag->setTitle($tagTitle);
                $tag->setPid($pid);

                $this->tagsRepository->add($tag);
            }

            $this->tagsCache[$hash] = $tag;

        } else {
            $tag = $this->tagsCache[$hash];
        }

        return $tag;
    }
}