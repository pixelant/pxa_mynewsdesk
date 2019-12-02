<?php
declare(strict_types=1);

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Pixelant AB
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

namespace Pixelant\PxaMynewsdesk\Scheduler;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\Enumeration\Action;

/**
 * Class ImportAdditionalFieldsProvider
 * @package Pixelant\PxaMynewsdesk\Scheduler
 */
class ImportAdditionalFieldsProvider extends AbstractAdditionalFieldProvider
{
    /**
     * Additional fields for scheduler task
     *
     * @param array $taskInfo
     * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task
     * @param SchedulerModuleController $parentObject
     * @return array|mixed
     */
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $parentObject)
    {
        $configs = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_pxamynewsdesk_domain_model_importconfig')
            ->select(
                ['uid', 'title'],
                'tx_pxamynewsdesk_domain_model_importconfig'
            )
            ->fetchAll();

        $action = $parentObject->getCurrentAction();
        if (empty($taskInfo['configurations'])) {
            if ($action->equals(Action::ADD)) {
                $taskInfo['configurations'] = [];
            } elseif ($action->equals(Action::EDIT)) {
                // In case of editing the task, set to currently selected value
                $taskInfo['configurations'] = $task->configurations;
            }
        }

        $selectedValues = $taskInfo['configurations'];

        $optionsHtml = '';
        foreach ($configs as $config) {
            $optionsHtml .= '<option value="' . $config['uid'] . '" ' . (in_array($config['uid'], $selectedValues) ? 'selected' : '') . '>' . $config['title'] . '</option>';
        }


        $fieldCode = '<select name="tx_scheduler[pxamynewsdesk_configurations][]" multiple="multiple">' . $optionsHtml . '</select>';
        $additionalFields['pxamynewsdesk_configurations'] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_be.xlf:scheduler.configurations'
        );

        return $additionalFields;
    }

    /**
     * Validate fields
     *
     * @param array $submittedData
     * @param SchedulerModuleController $parentObject
     * @return bool
     */
    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $parentObject)
    {
        if (empty($submittedData['pxamynewsdesk_configurations'])) {
            $this->addMessage(
                $GLOBALS['LANG']->sL('LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_be.xlf:scheduler.error.empty_configuration'),
                FlashMessage::ERROR
            );

            return false;
        }

        return true;
    }

    /**
     * Save data
     *
     * @param array $submittedData
     * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task
     */
    public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task)
    {
        $task->configurations = $submittedData['pxamynewsdesk_configurations'];
    }
}
