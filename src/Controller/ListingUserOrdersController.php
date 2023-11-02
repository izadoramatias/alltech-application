<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListingUserOrdersController extends AbstractController
{
    #[Route('/home', name: 'app_user_order_listing_render', methods: ['GET'])]
    public function renderListing(): Response
    {
        return $this->render('userOrderList.html.twig');
    }
}