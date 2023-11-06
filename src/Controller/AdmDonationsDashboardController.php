<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AdmDonationsDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_adm_dashboard_render', methods: ['GET'])]
    public function renderDashboard(Session $session): Response
    {
        if ( !$session->has('isUserLogged') ) {
            $response = $this->redirectToRoute('app_login_render');
            $response->setStatusCode(Response::HTTP_FORBIDDEN);

            return $response;
        }

        if ( $session->isStarted() && $session->get('userPermission') === 3 ) {
            return $this->render('admDashboardDonations.html.twig');
        }

        return $this->redirectToRoute('app_user_order_listing_render');
    }
}