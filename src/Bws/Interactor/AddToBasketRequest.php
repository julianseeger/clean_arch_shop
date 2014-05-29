<?php

namespace Bws\Interactor;

class AddToBasketRequest
{
    private $articleId;
    private $count;
    private $basketId;

    public function __construct($articleId, $count, $basketId)
    {
        $this->articleId = $articleId;
        $this->count     = $count;
        $this->basketId  = $basketId;
    }

    /**
     * @return mixed
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return mixed
     */
    public function getBasketId()
    {
        return $this->basketId;
    }
}
 