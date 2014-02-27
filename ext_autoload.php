<?php

// Register necessary classes with autoloader
return array(
	'tx_pxamynewsdesk_importtask' => t3lib_extMgm::extPath('pxa_mynewsdesk', 'Classes/Scheduler/ImportTask.php'),
	'tx_pxamynewsdesk_importadditionalfieldsprovider' => t3lib_extMgm::extPath('pxa_mynewsdesk', 'Classes/Scheduler/ImportAdditionalFieldsProvider.php'),
	'tx_pxamynewsdesk_cleanertask' => t3lib_extMgm::extPath('pxa_mynewsdesk', 'Classes/Scheduler/CleanerTask.php'),
);

?>