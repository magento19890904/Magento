<?php

/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class MagePsycho_Massimporterpro_Model_Import_Adapter_Abstract implements SeekableIterator
{
    /**
     * Column names array.
     *
     * @var array
     */
    protected $_colNames;

    /**
     * Quantity of columns in first (column names) row.
     *
     * @var int
     */
    protected $_colQuantity;

    /**
     * Current row.
     *
     * @var array
     */
    protected $_currentRow = null;

    /**
     * Current row number.
     *
     * @var int
     */
    protected $_currentKey = null;

    /**
     * Source file path.
     *
     * @var string
     */
    protected $_source;

    /**
     * Adapter object constructor.
     *
     * @param string $source Source file path.
     * @throws Mage_Core_Exception
     * @return void
     */
    final public function __construct($options = array())
    {
        $source = $options['source'];
        if (!is_string($source)) {
            Mage::throwException(Mage::helper('magepsycho_massimporterpro')->__('Source file path must be a string'));
        }
        if (!is_readable($source)) {
            Mage::throwException(Mage::helper('magepsycho_massimporterpro')->__("%s file does not exists or is not readable", $source));
        }
        $this->_source = $source;

        $this->_init($options);

        // validate column names consistency
        if (is_array($this->_colNames) && !empty($this->_colNames)) {
            $this->_colQuantity = count($this->_colNames);

            if (count(array_unique($this->_colNames)) != $this->_colQuantity) {
                Mage::throwException(Mage::helper('magepsycho_massimporterpro')->__('Column names have duplicates'));
            }
        } else {
            Mage::throwException(Mage::helper('magepsycho_massimporterpro')->__('Column names is empty or is not an array'));
        }
    }

    /**
     * Method called as last step of object instance creation. Can be overridden in child classes.
     *
     * @return MagePsycho_Massimporterpro_Model_Import_Adapter_Abstract
     */
    protected function _init()
    {
        return $this;
    }

    /**
     * Return the current element.
     *
     * @return mixed
     */
    public function current()
    {
        return array_combine(
            $this->_colNames,
            count($this->_currentRow) != $this->_colQuantity
                ? array_pad($this->_currentRow, $this->_colQuantity, '')
                : $this->_currentRow
        );
    }

    /**
     * Column names getter.
     *
     * @return array
     */
    public function getColNames()
    {
        return $this->_colNames;
    }

    /**
     * Return the key of the current element.
     *
     * @return int More than 0 integer on success, integer 0 on failure.
     */
    public function key()
    {
        return $this->_currentKey;
    }

    /**
     * Seeks to a position.
     *
     * @param int $position The position to seek to.
     * @return void
     */
    public function seek($position)
    {
        Mage::throwException(Mage::helper('magepsycho_massimporterpro')->__('Not implemented yet'));
    }

    /**
     * Checks if current position is valid.
     *
     * @return boolean Returns true on success or false on failure.
     */
    public function valid()
    {
        return !empty($this->_currentRow);
    }

    /**
     * Check source file for validity.
     *
     * @return MagePsycho_Massimporterpro_Model_Import_Adapter_Abstract
     */
    public function validateSource()
    {
        return $this;
    }
}