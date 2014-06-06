<?php
/**
 * BWS WebShop
 *
 * @author Christian Bergau <cbergau86@gmail.com>
 */

namespace Bws\Repository;

use Bws\Entity\PaymentMethod;

interface PaymentMethodRepository
{
    /**
     * @return PaymentMethod[]
     */
    public function findAll();
}
