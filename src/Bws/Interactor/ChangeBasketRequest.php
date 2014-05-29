<?php

namespace Bws\Interactor;

class ChangeBasketRequest
{
    public $basketId;
    public $articleId;
    public $count;

    public function __construct($articleId, $basketId, $count)
    {
        $this->articleId = $articleId;
        $this->basketId  = $basketId;
        $this->count     = $count;
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
    public function getBasketId()
    {
        return $this->basketId;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }


}
 