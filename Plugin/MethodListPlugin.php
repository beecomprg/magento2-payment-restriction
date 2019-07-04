<?php
namespace Beecom\PaymentRestriction\Plugin;

use Beecom\PaymentRestriction\Helper\Checkout;

class MethodListPlugin
{
    protected $helper;

    public function __construct(Checkout $helper)
    {
        $this->helper = $helper;
    }

    public function afterGetAvailableMethods(\Magento\Payment\Model\MethodList $methodList, $result, \Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        $storeId = $quote->getStoreId();
        if($this->helper->isEnabled($storeId) && $quote && $quote->getShippingAddress() && $quote->getShippingAddress()->getShippingMethod()){
            $shippingMethod = $quote->getShippingAddress()->getShippingMethod();
            foreach ($result as $key => $item) {
                $test = $this->helper->getPaymentRestrictionByCode($shippingMethod, $storeId);
                if(strpos($item->getCode(), $test) !== false){
                    unset($result[$key]);
                }
            }
            return $result;
        }
        return $result;
    }
}
