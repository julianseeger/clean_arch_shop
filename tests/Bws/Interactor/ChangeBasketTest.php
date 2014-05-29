<?php

namespace Bws\Interactor;

use Bws\Entity\ArticleStub;
use Bws\Entity\Basket;
use Bws\Entity\EmptyBasketStub;
use Bws\Repository\BasketPositionRepositoryMock;
use Bws\Repository\BasketRepositoryMock;

class ChangeBasketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BasketPositionRepositoryMock
     */
    private $basketPositionRepository;

    /**
     * @var BasketRepositoryMock
     */
    private $basketRepository;

    /**
     * @var ChangeBasket
     */
    private $interactor;

    public function setUp()
    {
        $this->basketPositionRepository = new BasketPositionRepositoryMock();
        $this->basketRepository         = new BasketRepositoryMock();
        $this->interactor               = new ChangeBasket($this->basketPositionRepository, $this->basketRepository);
    }

    public function testBadBasketId()
    {
        $response = $this->interactor->execute(new ChangeBasketRequest(ArticleStub::ID, null, 2));
        $this->assertEquals(ChangeBasketResponse::BAD_BASKET_ID, $response->getCode());
    }

    public function testBadCount()
    {
        $response = $this->interactor->execute(new ChangeBasketRequest(ArticleStub::ID, 12356, null));
        $this->assertEquals(ChangeBasketResponse::BAD_COUNT, $response->getCode());
    }

    public function testBadArticleId()
    {
        $response = $this->interactor->execute(new ChangeBasketRequest(null, 12356, 2));
        $this->assertEquals(ChangeBasketResponse::BAD_ARTICLE_ID, $response->getCode());
    }

    public function testBasketNotFound()
    {
        $response = $this->interactor->execute(new ChangeBasketRequest(ArticleStub::ID, 12356, 2));
        $this->assertEquals(ChangeBasketResponse::BASKET_NOT_FOUND, $response->getCode());
    }

    public function testBasketIsEmpty()
    {
        $response = $this->interactor->execute(new ChangeBasketRequest(ArticleStub::ID, EmptyBasketStub::ID, 2));
        $this->assertEquals(ChangeBasketResponse::BASKET_IS_EMPTY, $response->getCode());
    }

    public function testArticleIsNotInBasket()
    {
        $response = $this->interactor->execute(
            new ChangeBasketRequest(9999, BasketRepositoryMock::BASKET_ID, 2)
        );
        $this->assertEquals(ChangeBasketResponse::ARTICLE_IS_NOT_IN_BASKET, $response->getCode());
    }

    public function testBasketPositionRemovedIfCountIsZero()
    {
        $response = $this->interactor->execute(
            new ChangeBasketRequest(ArticleStub::ID, BasketRepositoryMock::BASKET_ID, 0)
        );
        $this->assertEquals(ChangeBasketResponse::SUCCESS, $response->getCode());
    }

    public function testBasketPositionCountIncreased()
    {
        $response = $this->interactor->execute(
            new ChangeBasketRequest(ArticleStub::ID, BasketRepositoryMock::BASKET_ID, 2)
        );
        $this->assertEquals(ChangeBasketResponse::SUCCESS, $response->getCode());
    }
}