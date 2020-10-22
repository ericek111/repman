<?php

declare(strict_types=1);

namespace Buddy\Repman\Tests\Doubles;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;

final class GiteaOAuth
{
    public static function mockAccessTokenResponse(string $email, ContainerInterface $container): void
    {
        $container->get(HttpClientStub::class)->setNextResponses([new Response(200, [], '{
          "access_token": "eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJnbnQiOjIsInR0IjowLCJleHAiOjE2MDMyNzAxODQsImlhdCI6MTYwMzI2NjU4NH0.NcRs-8gcIDmWMOa7ScyQO2Lb4EQkNnG2pFXtcJmqPb9QsA82RHVCUaOBYyjZto5F3Kv8Oo_hW6fwYD7j1f_GjQ",
          "token_type":"bearer",
          "expires_in":3600,
          "refresh_token":"eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJnbnQiOjIsInR0IjoxLCJleHAiOjE2MDU4OTQ1ODQsImlhdCI6MTYwMzI2NjU4NH0.xnznuFU9hNXr-hP6O97f31IKzYkY-7MupOUchyl9Phyo__aDtx6fYgzS4FM08tUGt-zxBE7mx78u0AWPBiU4WQ"
        }')]);
    }

    public static function mockUserResponse(string $email, ContainerInterface $container): void
    {
        $container->get(HttpClientStub::class)->setNextResponses([new Response(200, [], self::getUserJson($email))]);
    }

    private static function getUserJson(string $email): string
    {
        return '{
          "id": 1,
          "login": "john_smith",
          "full_name":"John Smith",
          "email":"'.$email.'",
          "avatar_url":"https://localhost:3000/user/avatar/john_smith/-1",
          "language":"en-US",
          "is_admin":true,
          "last_login":"2012-06-01T13:41:01+02:00",
          "created":"2012-05-23T10:00:58+02:00",
          "username":"john_smith"
        }';
    }
}
