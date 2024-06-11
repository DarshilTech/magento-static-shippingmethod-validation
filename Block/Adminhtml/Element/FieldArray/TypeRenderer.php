<?php
/**

* SetuBridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
*/

declare(strict_types=1);

namespace SetuBridge\ShippingMethodValidation\Block\Adminhtml\Element\FieldArray;

use Magento\Framework\View\Element\AbstractBlock;


class TypeRenderer extends AbstractBlock
{

    protected function _toHtml()
    {

        $this->getInputId();
        $this->getInputName();
        $this->getElementOptions();
        $this->getElementType();
        if($this->getElementType() == 'select')
        {
           $elementHtml= $this->renderSelectElement();
        }else
        $elementHtml = '<input name="' . $this->getInputName() . '" type="' . $this->getElementType() . '" id="' . $this->getInputId() . '">';

        return $elementHtml;
    }
    protected function renderSelectElement()
    {
        $elementHtml = '<select name="' . $this->getInputName() . '" id="' . $this->getInputId() . '">';
        foreach ($this->getElementOptions() as $key => $value) {
            $elementHtml .= '<option value="' . $key . '">' . $value . '</option>';
        }
        $elementHtml .= '</select>';
        return $elementHtml;
    }
}
