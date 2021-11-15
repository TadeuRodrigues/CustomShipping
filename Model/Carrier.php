<?php
declare(strict_types=1);

namespace TadeuRodrigues\CustomShipping\Model;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Framework\DataObject;

class Carrier extends AbstractCarrierOnline implements CarrierInterface
{
    const CODE = 'customshipping';

    protected $_code = self::CODE;

    /**
     * @inheritDoc
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->canCollectRates()) {
            return $this->getErrorMessage();
        }

        $result = $this->_rateFactory->create();
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier('customshipping');
        // nome da transportadora
        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethod('customshipping');
        // matodo usado pela transportadora
        $method->setMethodTitle($this->getConfigData('name'));

        $amount = 10.0;
        $method->setPrice($amount);
        $method->setCust($amount);

        $result->append($method);

        return $result;
    }

    /**
     * @inheritDoc
     */
    protected function _doShipmentRequest(DataObject $request)
    {
        $this->_prepareShipmentRequest($request);
        $this->setRawRequest($request);
    }

    /**
     * @inheritDoc
     */
    public function getAllowedMethods(): array
    {
        return [$this->_code => $this->getConfigData('name')];
    }
}
