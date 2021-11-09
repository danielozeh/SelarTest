<?php

namespace App\Repositories\Subscription;

/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */
interface SubscriptionInterface {

    public function subscribe($request);

    public function currentPlan();

    public function subscriptionTransactions($status);

    public function filterTransactions($request);
}