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

    protected $rates = [
        'high' => "21.0000",
        'low' => "15.0000"
    ];

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
        if(strpos($code, '_') !== false){
            $explode = explode("_", $code);
            $code = $explode[0];
        }
        return $this->frameworkHelper->getMapValueByValue(self::PATH_PAYMENT_MAP, $code, $storeId);
    }

}
