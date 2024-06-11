<?php
/**

* SetuBridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
*/

namespace SetuBridge\ShippingMethodValidation\Block\Adminhtml;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use MagePal\CustomShippingRate\Block\Adminhtml\System\Config\Form\Field\ShippingList;

class SetChanges extends ShippingList{

    protected function _construct()
    {
        foreach ($this->helper->getHeaderColumns() as $key => $column) {
            $this->addColumn(
                $key,
                [
                    'label' => __($column['label']),
                    'class' => $column['class'],
                    'renderer' => isset($column['renderer'])?$column['renderer']:false
                ]
            );
        }

        $this->_addAfter = false;
        AbstractFieldArray::_construct();
    }
}
