<?php

namespace Bws\Interactor;

use Bws\Entity\ArticleStub;
use Bws\Repository\ArticleRepositoryMock;

class PresentArticlesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PresentArticles
     */
    private $interactor;

    /**
     * @var ArticleRepositoryMock
     */
    private $articleRepository;

    public function setUp()
    {
        $this->articleRepository = new ArticleRepositoryMock();
        $this->interactor        = new PresentArticles($this->articleRepository);
    }

    public function testReturnsNoArticles()
    {
        $this->articleRepository->deleteAll();
        $response = $this->interactor->execute();
        $this->assertEquals(array(), $response->getArticles());
    }

    public function testReturnsArticles()
    {
        $response = $this->interactor->execute();
        $this->assertEquals(
            array(
                array(
                    'id'          => ArticleStub::ID,
                    'ean'         => ArticleStub::EAN,
                    'title'       => ArticleStub::TITLE,
                    'description' => ArticleStub::DESCRIPTION,
                    'price'       => ArticleStub::PRICE,
                    'imagePath'   => ArticleStub::IMAGE_PATH,
                )
            ),
            $response->getArticles()
        );
    }
}
 