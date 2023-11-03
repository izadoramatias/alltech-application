<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ListingUserOrdersController extends AbstractController
{
    #[Route('/home', name: 'app_user_order_listing_render', methods: ['GET'])]
    public function renderListing(Session $session): Response
    {
        if ( !$session->has('isUserLogged') ){
            $response = $this->redirectToRoute('app_login_render');
            $response->setStatusCode(Response::HTTP_FORBIDDEN);

            return $response;
        }

        return $this->render('userOrderList.html.twig');
    }
}