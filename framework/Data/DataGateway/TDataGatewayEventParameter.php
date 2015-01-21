<?php
/**
 * TDataGatewayCommand, TDataGatewayEventParameter and TDataGatewayResultEventParameter class file.
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @version $Id$
 * @package Prado\Data\DataGateway
 */

namespace Prado\Data\DataGateway;

/**
 * TDataGatewayEventParameter class contains the TDbCommand to be executed as
 * well as the criteria object.
 *
 * @author Wei Zhuo <weizho[at]gmail[dot]com>
 * @version $Id$
 * @package Prado\Data\DataGateway
 * @since 3.1
 */
class TDataGatewayEventParameter extends TEventParameter
{
	private $_command;
	private $_criteria;

	public function __construct($command,$criteria)
	{
		$this->_command=$command;
		$this->_criteria=$criteria;
	}

	/**
	 * The database command to be executed. Do not rebind the parameters or change
	 * the sql query string.
	 * @return TDbCommand command to be executed.
	 */
	public function getCommand()
	{
		return $this->_command;
	}

	/**
	 * @return TSqlCriteria criteria used to bind the sql query parameters.
	 */
	public function getCriteria()
	{
		return $this->_criteria;
	}
}