<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Massimporterpro
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Massimporterpro_Model_Import_Adapter
{
    /**
     * Adapter factory. Checks for availability, loads and create instance of import adapter object.
     *
     * @param string $type Adapter type ('csv', 'xml' etc.)
     * @param mixed $options OPTIONAL Adapter constructor options
     * @throws Exception
     * @return MagePsycho_Massimporterpro_Model_Import_Adapter_Abstract
     */
    public static function factory($type, $options = null)
    {
        if (!is_string($type) || !$type) {
            Mage::throwException(Mage::helper('magepsycho_massimporterpro')->__('Adapter type must be a non empty string'));
        }
        $adapterClass = __CLASS__ . '_' . ucfirst(strtolower($type));

        if (!class_exists($adapterClass)) {
            Mage::throwException("'{$type}' file extension is not supported");
        }
        $adapter = new $adapterClass($options);

        if (! $adapter instanceof MagePsycho_Massimporterpro_Model_Import_Adapter_Abstract) {
            Mage::throwException(
                Mage::helper('magepsycho_massimporterpro')->__('Adapter must be an instance of MagePsycho_Massimporterpro_Model_Import_Adapter_Abstract')
            );
        }
        return $adapter;
    }

    /**
     * Create adapter instance for specified source file.
     *
     * @param string $source Source file path.
     * @return MagePsycho_Massimporterpro_Model_Import_Adapter_Abstract
     */
    public static function findAdapterFor($source)
    {
        return self::factory(pathinfo($source, PATHINFO_EXTENSION), $source);
    }
}
