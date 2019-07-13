<?php

namespace Beecom\PaymentRestriction\Block\Adminhtml\Form\Field;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Shipping\Model\Config;
use Beecom\Core\Block\Adminhtml\Form\Field\ShippingMethods as BaseShippingMethods;
/**
 * HTML select element block with customer groups options
 */
class ShippingMethods extends BaseShippingMethods
{
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->getDeliveryMethods(true) as $groupId => $groupLabel) {
                $formattedGroupLabel = sprintf("%s [%s]", $groupLabel, $groupId);
                $this->addOption($groupId, addslashes($formattedGroupLabel));
            }
        }
        return parent::_toHtml();
    }
}
