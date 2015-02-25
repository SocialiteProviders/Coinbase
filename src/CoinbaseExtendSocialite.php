<?php
namespace SocialiteProviders\Coinbase;

use SocialiteProviders\Manager\SocialiteWasCalled;

class CoinbaseExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
            'coinbase', __NAMESPACE__.'\Provider'
        );
    }
}
