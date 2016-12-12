<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2013 Pixelant AB (maksym@pixelant.se)
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


namespace Pixelant\PxaMynewsdesk\Scheduler ;


class ImportAdditionalFieldsProvider implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface
{
    public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) {

        $pxamynewsdeskImportConfig = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_pxamynewsdesk_domain_model_importconfig', 'deleted=0 AND hidden=0
                AND (starttime=0 OR starttime<=' . $GLOBALS['EXEC_TIME'] . ')');
        
        $fieldCodeCfgRec = '';
        
        if (empty($taskInfo['pxamynewsdeskconfiguration'])) {
            if($parentObject->CMD == 'edit') {
                $taskInfo['pxamynewsdeskconfiguration'] = $task->pxamynewsdeskconfiguration;
            } else {
                $taskInfo['pxamynewsdeskconfiguration'] = '';
            }
        }

        $cfgValues =  explode(",", $taskInfo['pxamynewsdeskconfiguration']);

        foreach ($pxamynewsdeskImportConfig as $cfgRec) {
          $fieldCodeCfgRec .= '<option value="'.$cfgRec['uid'].'" ' . (in_array($cfgRec['uid'], $cfgValues)?'selected':''). '>' . $cfgRec['title'] . '</option>';
        }

        
        $fieldID = 'task_pxamynewsdeskconfiguration';
        $fieldCode = '<select name="tx_scheduler[pxamynewsdeskconfiguration][]" id="' . $fieldID . '" multiple="multiple">' . $fieldCodeCfgRec . '</select>';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => 'LLL:EXT:pxa_mynewsdesk/Resources/Private/Language/locallang_db.xml:scheduler.tx_pxamynewsdesk_configuration'
        );
        
        return $additionalFields;
    }

    public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) {
        $submittedData['pxamynewsdeskconfiguration'] = trim(implode(",", $submittedData['pxamynewsdeskconfiguration']));
        return true;
    }

    public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task) {
        $task->pxamynewsdeskconfiguration = $submittedData['pxamynewsdeskconfiguration'];
    }
}
