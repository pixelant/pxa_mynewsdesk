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
class Tx_PxaMynewsdesk_Domain_Repository_ImportConfigRepository extends Tx_Extbase_Persistence_Repository {
  public function findAllByUids($uids) {

    if ($uids) {
      $query = $this->createQuery();
      $query->statement('SELECT * FROM `tx_pxamynewsdesk_domain_model_importconfig` WHERE deleted=0 AND hidden=0 and uid IN (' . $uids . ')');
      return $query->execute()->toArray();
    } else {
      return false;
    }
  }
  
}
?>