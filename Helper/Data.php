<?php
/**

* SetuBridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
*/

declare(strict_types=1);

namespace SetuBridge\ShippingMethodValidation\Helper;

use MagePal\CustomShippingRate\Helper\Data as MagepalHelper;
use Magento\Framework\View\Element\BlockFactory;
use SetuBridge\ShippingMethodValidation\Block\Adminhtml\Element\FieldArray\TypeRenderer;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends MagepalHelper
{
    protected $blockFactory;
    protected $context;

    public function __construct(BlockFactory $blockFactory, Context $context) {
        $this->blockFactory = $blockFactory;
        parent::__construct($context);
    }

    public function getHeaderColumns()
    {
        $elementBlockClass = TypeRenderer::class;
        // For the checkbox render in the admin configuration of magepal custom_shipping_method validation
        $newField = [
            'frontend' => [
                'label' => __('Frontend Enabled'),
                'class' => '',
                'renderer' => $this->blockFactory->createBlock($elementBlockClass)->setElementType('checkbox'),
                'default' => ''
            ]
        ];
        $this->codes = array_merge($this->codes, $newField);
        return $this->codes;
    }
}
