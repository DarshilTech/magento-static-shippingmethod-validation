<?php
/**

* SetuBridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
*/
namespace SetuBridge\ShippingMethodValidation\Model;

use MagePal\CustomShippingRate\Model\Carrier as MagepalCarrier;
use Magento\Quote\Model\Quote\Address\RateRequest;

class Carrier extends MagepalCarrier
{

    public function collectRates(RateRequest $request)
    {
        $result = $this->_rateFactory->create();

        if (!$this->getConfigFlag('active') || (!$this->isAdmin() && $this->hideShippingMethodOnFrontend())) {
            return $result;
        }

        foreach ($this->_customShippingRateHelper->getShippingType($request->getStoreId()) as $shippingType) {
            $rate = $this->_rateMethodFactory->create();
            if ($this->areaValidation($shippingType)) {
                $rate->setCarrier($this->_code);
                $rate->setCarrierTitle($this->getConfigData('title'));
                $rate->setMethod($shippingType['code']);
                $rate->setMethodTitle($shippingType['title']);
                $rate->setCost($shippingType['price']);
                $rate->setPrice($shippingType['price']);
                $result->append($rate);
            }
        }

        return $result;
    }
    public function areaValidation($value)
    {
        $state = $this->_state->getAreaCode();
        $validate = $state == 'frontend' ? true : false;
        if ($validate) {
            if ($value[$state] == "on") {
                $check = true;
            } else if ($value[$state] == "") {
                $check = false;
            }
        } else {
            $check = true;
        }
        return $check;
    }
    public function checkAvailableShipCountries(\Magento\Framework\DataObject $request)
    {
        $speCountriesAllow = $this->getConfigData('sallowspecific');
        if($this->isAdmin()){
            $speCountriesAllow = 0;
        }
        /*
         * for specific countries, the flag will be 1
         */
        if ($speCountriesAllow && $speCountriesAllow == 1) {
            $showMethod = $this->getConfigData('showmethod');
            $availableCountries = [];
            if ($this->getConfigData('specificcountry')) {
                $availableCountries = explode(',', $this->getConfigData('specificcountry'));
            }
            if ($availableCountries && in_array($request->getDestCountryId(), $availableCountries)) {
                return $this;
            } elseif ($showMethod && (!$availableCountries || $availableCountries && !in_array(
                $request->getDestCountryId(),
                $availableCountries
            ))
            ) {
                /** @var Error $error */
                $error = $this->_rateErrorFactory->create();
                $error->setCarrier($this->_code);
                $error->setCarrierTitle($this->getConfigData('title'));
                $errorMsg = $this->getConfigData('specificerrmsg');
                $error->setErrorMessage(
                    $errorMsg ? $errorMsg : __(
                        'Sorry, but we can\'t deliver to the destination country with this shipping module.'
                    )
                );

                return $error;
            } else {
                /*
                 * The admin set not to show the shipping module if the delivery country
                 * is not within specific countries
                 */
                return false;
            }
        }

        return $this;
    }
}
