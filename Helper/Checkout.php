<?php
namespace Beecom\PaymentRestriction\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Beecom\Core\Helper\Data as FrameworkHelper;


class Checkout extends AbstractHelper
{
    const PATH_CLIENT_ENABLED = 'paymentrestriction/general/enabled';
    const PATH_PAYMENT_MAP = 'paymentrestriction/general/mapping';

    protected $logger;

    protected $scopeConfig;

    protected $frameworkHelper;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Context $context,
        \Psr\Log\LoggerInterface $logger,
        FrameworkHelper $frameworkHelper
    )
    {
        parent::__construct($context);
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->frameworkHelper = $frameworkHelper;
    }

    public function isEnabled($store = null){
        return $this->scopeConfig->isSetFlag(self::PATH_CLIENT_ENABLED, ScopeInterface::SCOPE_STORE, $store);
    }

    public function getPaymentRestrictionByCode($code, $storeId = null){
        return $this->frameworkHelper->getMapValueByValue(self::PATH_PAYMENT_MAP, $code, $storeId);
    }

    public function isValidCombination($shippingMethod, $paymentMethod, $storeId = null){
        $combinations = $this->getPaymentRestrictionByCode($shippingMethod, $storeId);
        foreach ($combinations as $combination){
            var_dump($combination);
            if(isset($combination[$paymentMethod]) && $combination[$paymentMethod] === $shippingMethod){
                return false; //combination not VALID
            }
        }
        return true;
    }

}
