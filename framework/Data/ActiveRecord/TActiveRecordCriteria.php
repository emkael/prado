<?php
/**
 * TActiveRecordCriteria class file.
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package Prado\Data\ActiveRecord
 */

namespace Prado\Data\ActiveRecord;

use Prado\Data\DataGateway\TSqlCriteria;
use Prado\Prado;


/**
 * Search criteria for Active Record.
 *
 * Criteria object for active record finder methods. Usage:
 * <code>
 * $criteria = new TActiveRecordCriteria;
 * $criteria->Condition = 'username = :name AND password = :pass';
 * $criteria->Parameters[':name'] = 'admin';
 * $criteria->Parameters[':pass'] = 'prado';
 * $criteria->OrdersBy['level'] = 'desc';
 * $criteria->OrdersBy['name'] = 'asc';
 * $criteria->Limit = 10;
 * $criteria->Offset = 20;
 * </code>
 *
 * @author Wei Zhuo <weizho[at]gmail[dot]com>
 * @package Prado\Data\ActiveRecord
 * @since 3.1
 */
class TActiveRecordCriteria extends TSqlCriteria
{

}

