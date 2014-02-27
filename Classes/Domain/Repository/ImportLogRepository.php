<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Maksym Leskiv <maksym@pixelant.se>, Pixelant
 *  
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
 * A repository for import config
 */
class Tx_PxaMynewsdesk_Domain_Repository_ImportLogRepository extends Tx_Extbase_Persistence_Repository {
  public function countByStringProperties($columns, $values, $pid) {
    $sql = array();
    foreach($columns as $key => $column) {
      $sql[] = "`$column` = '$values[$key]'";
    }
    $where = implode(" AND ", $sql);
    if (trim($where)) {
      $query = $this->createQuery();
      $query->statement('SELECT * FROM `tx_pxamynewsdesk_domain_model_importlog` WHERE ' . $where . '  AND newspid = ' . intval($pid));
      return count($query->execute()->toArray());
    } else {
      return flase;
    }
  }
  
}
?>