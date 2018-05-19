<?php

namespace SocialiteProviders\Coinbase;

use SocialiteProviders\Manager\OAuth2\User;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'COINBASE';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://www.coinbase.com/oauth/authorize', $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getCodeFields($state = null)
    {
        $time = time();
        return array_merge(parent::getCodeFields($state), [
            'CB-ACCESS-SIGN' => hash_hmac( 'sha256', $time.'GET'.'oauth/authorize', $this->clientSecret ),
            'CB-VERSION' => '2017-08-07',
            'CB-ACCESS-TIMESTAMP' => $time
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://www.coinbase.com/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $time = time();
        $response = $this->getHttpClient()->get(
            'https://api.coinbase.com/v2/user', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'CB-ACCESS-SIGN' => hash_hmac( 'sha256', $time.'GET'.'v2/user', $this->clientSecret ),
                'CB-VERSION' => '2017-08-07',
                'CB-ACCESS-TIMESTAMP' => $time
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['data']['id'], 'nickname' => @$user['data']['username'],
            'name' => @$user['data']['name'], 'email' => @$user['data']['email'], 'avatar' => @$user['data']['avatar'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
