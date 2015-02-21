<?php
namespace SocialiteProviders\Coinbase;

use SocialiteProviders\Manager\SocialiteWasCalled;

class CoinbaseExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('coinbase', __NAMESPACE__.'\Provider');
    }
}
