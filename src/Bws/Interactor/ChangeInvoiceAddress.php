<?php

namespace Bws\Interactor;

use Bws\Repository\CustomerRepository;
use Bws\Repository\InvoiceAddressRepository;

class ChangeInvoiceAddress
{
    /**
     * @var \Bws\Repository\CustomerRepository
     */
    private $customerRepository;

    /**
     * @var \Bws\Repository\InvoiceAddressRepository
     */
    private $invoiceAddressRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        InvoiceAddressRepository $invoiceAddressRepository
    ) {
        $this->customerRepository       = $customerRepository;
        $this->invoiceAddressRepository = $invoiceAddressRepository;
    }

    public function execute(ChangeInvoiceAddressRequest $request)
    {
        $response = new ChangeInvoiceAddressResponse();

        if (!$customer = $this->customerRepository->find($request->customerId)) {
            $response->code = $response::CUSTOMER_NOT_FOUND;
            return $response;
        }

        $newInvoiceAddress = $this->saveNewInvoiceAddress($request, $customer);
        $this->changeCustomersInvoiceAddress($customer, $newInvoiceAddress);

        $response->code      = $response::SUCCESS;
        $response->addressId = $newInvoiceAddress->getId();

        return $response;
    }

    /**
     * @param ChangeInvoiceAddressRequest $request
     * @param                             $customer
     *
     * @return \Bws\Entity\InvoiceAddress
     */
    protected function saveNewInvoiceAddress(ChangeInvoiceAddressRequest $request, $customer)
    {
        $newInvoiceAddress = $this->invoiceAddressRepository->factory();
        $newInvoiceAddress->setFirstName($request->firstName);
        $newInvoiceAddress->setLastName($request->lastName);
        $newInvoiceAddress->setStreet($request->street);
        $newInvoiceAddress->setZip($request->zip);
        $newInvoiceAddress->setCity($request->city);
        $newInvoiceAddress->setCustomer($customer);

        $this->invoiceAddressRepository->save($newInvoiceAddress);
        return $newInvoiceAddress;
    }

    /**
     * @param $customer
     * @param $newInvoiceAddress
     */
    protected function changeCustomersInvoiceAddress($customer, $newInvoiceAddress)
    {
        $customer->changeCurrentInvoiceAddress($newInvoiceAddress);
        $this->customerRepository->save($customer);
    }
}
 