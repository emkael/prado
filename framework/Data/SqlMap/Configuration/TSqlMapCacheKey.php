<?php
/**
 * TSqlMapCacheModel, TSqlMapCacheTypes and TSqlMapCacheKey classes file.
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @package System.Data.SqlMap.Configuration
 */

/**
 * TSqlMapCacheKey class.
 *
 * Provides a hash of the object to be cached.
 *
 * @author Wei Zhuo <weizho[at]gmail[dot]com>
 * @package System.Data.SqlMap.Configuration
 * @since 3.1
 */
class TSqlMapCacheKey
{
	private $_key;

	/**
	 * @param mixed object to be cached.
	 */
	public function __construct($object)
	{
		$this->_key = $this->generateKey(serialize($object));
	}

	/**
	 * @param string serialized object
	 * @return string crc32 hash of the serialized object.
	 */
	protected function generateKey($string)
	{
		return sprintf('%x',crc32($string));
	}

	/**
	 * @return string object hash.
	 */
	public function getHash()
	{
		return $this->_key;
	}
}