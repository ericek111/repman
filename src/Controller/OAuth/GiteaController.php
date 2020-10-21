<?php

declare(strict_types=1);

namespace Buddy\Repman\Controller\OAuth;

use Buddy\Repman\Entity\User\OAuthToken;
use Buddy\Repman\Security\Model\User;
use Buddy\Repman\Service\GiteaApi\GiteaOauthProvider;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class GiteaController extends OAuthController
{
    /**
     * @Route("/auth/gitea", name="auth_gitea_start", methods={"GET"})
     */
    public function auth(ClientRegistry $clientRegistry): Response
    {
        return $this->oauth->getClient('gitea')->redirect(['email'], ['redirect_uri' => $this->generateUrl('login_gitea_check', [], UrlGeneratorInterface::ABSOLUTE_URL)]);
    }

    /**
     * @Route("/register/gitea", name="register_gitea_start", methods={"GET"})
     */
    public function register(): Response
    {
        $this->ensureOAuthRegistrationIsEnabled();

        return $this->oauth->getClient('gitea')->redirect(['email'], []);
    }

    /**
     * @Route("/register/gitea/check", name="register_gitea_check", methods={"GET"})
     */
    public function registerCheck(Request $request): Response
    {
        $this->ensureOAuthRegistrationIsEnabled();

        return $this->createAndAuthenticateUser(
            OAuthToken::TYPE_GITEA,
            function (): string {
                /** @var GiteaOauthProvider $gitea */
                $gitea = $this->oauth->getClient('gitea')->getOAuth2Provider();

                return $gitea->primaryEmail($this->oauth->getClient('gitea')->getAccessToken()->getToken());
            },
            $request
        );
    }

    /**
     * @Route("/user/token/gitea/check", name="package_gitea_check", methods={"GET"})
     */
    public function storeGiteaRepoToken(): Response
    {
        return $this->storeRepoToken(
            OAuthToken::TYPE_GITEA,
            fn () => $this->oauth->getClient('gitea')->getAccessToken([
                'redirect_uri' => $this->generateUrl('package_gitea_check', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]),
            'organization_package_new'
        );
    }
}
