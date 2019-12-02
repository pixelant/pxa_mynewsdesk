<?php
declare(strict_types=1);

namespace Pixelant\PxaMynewsdesk\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Import helper repository
 *
 * @package Pixelant\PxaMynewsdesk\Domain\Repository
 */
class NewsImportRepository
{
    /**
     * Count import items by id and pid
     *
     * @param string $id
     * @param string $importSource
     * @param int $pid
     * @return int
     */
    public function countByImportId(string $id, string $importSource, int $pid): int
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_news_domain_model_news');

        return $connection->count(
            'uid',
            'tx_news_domain_model_news',
            [
                'import_source' => $importSource,
                'import_id' => $id,
                'pid' => $pid,
            ]
        );
    }
}
