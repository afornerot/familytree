<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/auth/callback', name: 'app_auth_callback')]
    /**
     * Retour du fournisseur OIDC (OIDC_REDIRECTURI).
     *
     * Le routeur s'exécute avant le firewall : la route est indispensable pour
     * éviter un 404 avant que DynamicAuthenticator ne traite le code. Cette
     * action n'est atteinte qu'après une authentification réussie.
     */
    public function callback(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}
