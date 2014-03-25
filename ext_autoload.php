<?php

$extensionPath = t3lib_extMgm::extPath('pxa_mynewsdesk');
// Register necessary classes with autoloader
return array(
	'tx_pxamynewsdesk_importtask' => $extensionPath .  'Classes/Scheduler/class.tx_pxamynewsdesk_importtask.php',
	'tx_pxamynewsdesk_importadditionalfieldsprovider' => $extensionPath .  'Classes/Scheduler/class.tx_pxamynewsdesk_importadditionalfieldsprovider.php',
	'tx_pxamynewsdesk_cleanertask' => $extensionPath . 'Classes/Scheduler/class.tx_pxamynewsdesk_cleanertask.php',
);

?>