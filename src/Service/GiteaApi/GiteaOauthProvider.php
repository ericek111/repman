<?php

declare(strict_types=1);

namespace Buddy\Repman\Service\GiteaApi;

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use League\OAuth2\Client\Provider\GenericProvider;

class GiteaOauthProvider extends GenericProvider
{

    private string $giteaUrl;

    private string $apiRoot;

    public function __construct(array $options = [], array $collaborators = [])
    {
        $this->giteaUrl = $options['base'];

        if (empty($this->giteaUrl)) {
            throw new \RuntimeException('Gitea\'s base URL is empty.');
        }

        $this->apiRoot = !empty($options['apiRoot']) ? $options['apiRoot'] : ($this->giteaUrl . '/api/v1');

        parent::__construct(array_merge([
            'clientId' => '',
            'clientSecret' => '',
            'redirectUri' => '',
            'urlAuthorize' => $this->giteaUrl . '/login/oauth/authorize',
            'urlAccessToken' => $this->giteaUrl . '/login/oauth/access_token',
            'urlResourceOwnerDetails' => ''
        ], $options), $collaborators);
    }

    public function getBaseUrl(): string
    {
        return $this->giteaUrl;
    }

    public function getRestPath(string $resource = ''): string
    {
        return $this->apiRoot . $resource;
    }

    public function primaryEmail(string $accessToken): string
    {
        $request = $this->getAuthenticatedRequest(
            'GET',
            $this->getRestPath('/user'),
            $accessToken
        );

        $resp = $this->getParsedResponse($request);
        if (!isset($resp['email'])) {
            throw new \RuntimeException('Invalid response - field `email` missing.');
        }

        return $resp['email'];
    }


}
