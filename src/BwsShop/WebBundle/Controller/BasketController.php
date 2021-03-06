<?php

namespace BwsShop\WebBundle\Controller;

use Bws\Interactor\ChangeBasket;
use Bws\Interactor\ChangeBasketRequest;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Bws\Interactor\AddToBasket;
use Bws\Interactor\AddToBasketRequest;
use Bws\Interactor\ViewBasket;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class BasketController extends FOSRestController
{
    public function addAction(Request $request)
    {
        $session = $request->getSession();

        /** @var AddToBasket $interactor */
        $interactor = $this->get('interactor.add_to_basket');
        $response   = $interactor->execute(
            new AddToBasketRequest(
                $request->get('articleId', null),
                $request->get('count', null),
                $session->get('basketId', 0)
            )
        );

        $session->set('basketId', $response->getBasketId());
        $session->set('total', $response->getTotal());
        $session->set('posCount', $response->getPosCount());

        return $this->redirect($this->generateUrl('bws_shop_web_homepage'));
    }

    /**
     * @View()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $session = $request->getSession();

        /** @var ViewBasket $interactor */
        $interactor = $this->get('interactor.view_basket');
        $response   = $interactor->execute($session->get('basketId', 0));

        $view = $this
            ->view($response, 200)
            ->setTemplate('BwsShopWebBundle:Basket:list.html.twig')
            ->setTemplateVar('response')
        ;

        $session->set('total', $response->getTotal());
        $session->set('posCount', $response->getPositionCount());

        return $this->handleView($view);
    }

    /**
     * @View()
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeAction(Request $request)
    {
        $session = $request->getSession();

        /** @var ChangeBasket $interactor */
        $interactor = $this->get('interactor.change_basket');
        $response   = $interactor->execute(
            new ChangeBasketRequest(
                $request->get('articleId'),
                $session->get('basketId', 0),
                $request->get('count')
            )
        );

        $view = $this
            ->view($response, 200)
            ->setTemplate('BwsShopWebBundle:Basket:change.html.twig')
            ->setTemplateVar('response')
        ;

        return $this->handleView($view);
    }
}
