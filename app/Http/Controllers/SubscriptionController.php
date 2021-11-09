<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Subscription\SubscriptionInterface;

use App\Http\Traits\Response;

class SubscriptionController extends Controller
{
    use Response;
    protected $subscription;

    public function __construct(SubscriptionInterface $subscription) {
        $this->subscription = $subscription;
    }

    public function subscribe(Request $request) {
        try {
            return $this->subscription->subscribe($request);

        } catch (\Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    public function currentPlan() {
        try {
            return $this->subscription->currentPlan();

        } catch (\Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    public function subscriptionTransactions($status = null) {
        try {
            return $this->subscription->subscriptionTransactions($status);

        } catch (\Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    public function filterTransactions(Request $request) {
        try {
            return $this->subscription->filterTransactions($request);

        } catch (\Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }
}
