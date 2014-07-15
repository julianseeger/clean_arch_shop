<?php

namespace Bws\Interactor;

use Bws\Repository\CustomerRepository;

class PresentLastUsedAddress
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param int $customerId
     *
     * @return \Bws\Interactor\PresentLastUsedAddressResponse
     */
    public function getInvoice($customerId)
    {
        return $this->getLastUsedAddress($customerId, 'invoice');
    }

    /**
     * @param int $customerId
     *
     * @return \Bws\Interactor\PresentLastUsedAddressResponse
     */
    public function getDelivery($customerId)
    {
        return $this->getLastUsedAddress($customerId, 'delivery');
    }

    /**
     * @param int $customerId
     *
     * @param string $type
     *
     * @return PresentLastUsedAddressResponse
     */
    protected function getLastUsedAddress($customerId, $type)
    {
        $response = new PresentLastUsedAddressResponse();

        if (!$customer = $this->customerRepository->find($customerId)) {
            $response->code = $response::CUSTOMER_NOT_FOUND;
            return $response;
        }

        if ($type == 'delivery') {
            if (!$address = $customer->getLastUsedDeliveryAddress()) {
                $response->code = $response::CUSTOMER_HAS_NO_LAST_USED_DELIVERY_ADDRESS;
                return $response;
            }
        } else {
            if (!$address = $customer->getLastUsedInvoiceAddress()) {
                $response->code = $response::CUSTOMER_HAS_NO_LAST_USED_INVOICE_ADDRESS;
                return $response;
            }
        }

        $presentable = array(
            'firstName' => $address->getFirstName(),
            'lastName'  => $address->getLastName(),
            'street'    => $address->getStreet(),
            'zip'       => $address->getZip(),
            'city'      => $address->getCity()
        );

        $response->code = $response::SUCCESS;
        $response->address = $presentable;

        return $response;
    }
}