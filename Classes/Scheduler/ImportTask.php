<?php

namespace Pixelant\PxaMynewsdesk\Scheduler;

use Pixelant\PxaMynewsdesk\Service\ImportService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

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
 * Import task
 */
class ImportTask extends AbstractTask
{
    /**
     * Array of configurations uids
     *
     * @var array
     */
    public $configurations = [];

    /**
     * Function executed from the Scheduler.
     *
     * @return bool
     */
    public function execute()
    {
        $importService = GeneralUtility::makeInstance(ImportService::class);
        $importService->run($this->configurations);

        return true;
    }
}
