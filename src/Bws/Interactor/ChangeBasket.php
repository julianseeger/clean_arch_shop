<?php

namespace Bws\Interactor;

use Bws\Entity\BasketPosition;
use Bws\Repository\BasketPositionRepository;
use Bws\Repository\BasketRepository;

class ChangeBasket
{
    /**
     * @var BasketRepository
     */
    private $basketRepository;

    /**
     * @var BasketPositionRepository
     */
    private $basketPositionRepository;

    /**
     * @param BasketPositionRepository $basketPositionRepository
     * @param BasketRepository         $basketRepository
     */
    public function __construct(BasketPositionRepository $basketPositionRepository, BasketRepository $basketRepository)
    {
        $this->basketPositionRepository = $basketPositionRepository;
        $this->basketRepository         = $basketRepository;
    }

    /**
     * @param ChangeBasketRequest $request
     *
     * @return ChangeBasketResponse
     */
    public function execute(ChangeBasketRequest $request)
    {
        if (null === $request->getBasketId()) {
            return new ChangeBasketResponse(ChangeBasketResponse::BAD_BASKET_ID);
        }

        if (null === $request->getCount()) {
            return new ChangeBasketResponse(ChangeBasketResponse::BAD_COUNT);
        }

        if (null === $request->getArticleId()) {
            return new ChangeBasketResponse(ChangeBasketResponse::BAD_ARTICLE_ID);
        }

        $basket = $this->basketRepository->find($request->getBasketId());

        if (null === $basket) {
            return new ChangeBasketResponse(ChangeBasketResponse::BASKET_NOT_FOUND);
        }

        $basketPosition = $this->basketPositionRepository->findByBasket($basket);

        if ($this->isBasketEmpty($basketPosition)) {
            return new ChangeBasketResponse(ChangeBasketResponse::BASKET_IS_EMPTY);
        }

        /** @var BasketPosition $position */
        foreach ($basketPosition as $position) {
            if ($this->isRequestedArticle($request, $position)) {
                $this->changeOrRemovePosition($request, $position);
                return new ChangeBasketResponse(ChangeBasketResponse::SUCCESS);
            }
        }

        return new ChangeBasketResponse(ChangeBasketResponse::ARTICLE_IS_NOT_IN_BASKET);
    }

    /**
     * @param ChangeBasketRequest $request
     * @param BasketPosition      $position
     */
    protected function changeOrRemovePosition(ChangeBasketRequest $request, BasketPosition $position)
    {
        if ($request->getCount() == 0) {
            $this->basketPositionRepository->removeFromBasket($position);
        } else {
            $position->setCount($request->getCount());
            $this->basketPositionRepository->addToBasket($position);
        }
    }

    /**
     * @param ChangeBasketRequest $request
     * @param BasketPosition      $position
     *
     * @return bool
     */
    protected function isRequestedArticle(ChangeBasketRequest $request, BasketPosition $position)
    {
        return $position->getArticle()->getId() == $request->getArticleId();
    }

    /**
     * @param array $basketPosition
     *
     * @return bool
     */
    protected function isBasketEmpty(array $basketPosition = null)
    {
        return null === $basketPosition || 0 == sizeof($basketPosition);
    }
}
 