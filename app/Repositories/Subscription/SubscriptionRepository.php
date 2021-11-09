<?php

namespace App\Repositories\Subscription;

use App\Repositories\Subscription\SubscriptionInterface;
use App\Http\Traits\Response;
use Validator;
use App\Helper;

use App\Models\PlanVariation;
use App\Models\Subscription;
use App\Models\SubscriptionTransaction;

use Carbon\Carbon;


/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */

class SubscriptionRepository implements SubscriptionInterface {

    use Response;

    public function subscribe($request) {
        $validator = Validator::make($request->all(),[
            'plan_variation_id' => 'required',
            'currency' => 'required'
        ]);

        if($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $plan = PlanVariation::with('plan')->where('id', $request->plan_variation_id)->first();

        $amount = $plan->amount;

        if($request->currency == 'USD') {
            $amount = $amount * 500; //here, I assumed that 1 dollar = NGN500
        }

        //check if use has previously subscribed to a plan
        $is_subscribed = Subscription::where('user_id', auth()->user()->id)->first();
        $today = Carbon::now();

        if($is_subscribed) {
            //check due date
            if($today > $is_subscribed->due_date) {
                $is_subscribed->plan_id = $plan->plan->id;
                $is_subscribed->amount = $amount;
                $is_subscribed->currency = $request->currency;
                $is_subscribed->payment_plan = $plan->name;
                $is_subscribed->start_date = Carbon::now();
                $is_subscribed->due_date = $today->addDays($plan->days);

                $is_subscribed->save();

                SubscriptionTransaction::create([
                    'user_id' => auth()->user()->id,
                    'plan_id' => $plan->plan->id,
                    'amount' => $amount,
                    'currency' => $request->currency,
                    'status' => 1,
                    'payment_plan' => $plan->name,
                    'payment_method' => 'paystack', //it is assumed that only paystack is used
                    'transaction_ref' => Helper::generateCode(12),
                    'payment_ref' => 'PAYSTACK|' . Helper::generateCode(8)
                ]);

                return $this->success('You have subscribed successfully', 201);
            }
            
            SubscriptionTransaction::create([
                'user_id' => auth()->user()->id,
                'plan_id' => $plan->plan->id,
                'amount' => $amount,
                'currency' => $request->currency,
                'status' => 0,
                'payment_plan' => $plan->name,
                'payment_method' => 'paystack', //it is assumed that only paystack is used
                'transaction_ref' => Helper::generateCode(12),
                'payment_ref' => ''
            ]);
            return $this->error('You already have an active subscription', 401);
        }
        else {
            Subscription::create([
                'plan_id' => $plan->plan->id,
                'user_id' => auth()->user()->id,
                'amount' => $amount,
                'currency' => $request->currency,
                'payment_plan' => $plan->name,
                'start_date' => Carbon::now(),
                'due_date' => $today->addDays($plan->days)
            ]);

            SubscriptionTransaction::create([
                'user_id' => auth()->user()->id,
                'plan_id' => $plan->plan->id,
                'amount' => $amount,
                'currency' => $request->currency,
                'status' => 1,
                'payment_plan' => $plan->name,
                'payment_method' => 'paystack', //it is assumed that only paystack is used
                'transaction_ref' => Helper::generateCode(12),
                'payment_ref' => 'PAYSTACK|' . Helper::generateCode(8)
            ]);

            return $this->success('You have subscribed successfully', 201);
        }
    }

    public function currentPlan() {
        $subscription = Subscription::where('user_id', auth()->user()->id)->first();
        return $this->success($subscription, 200);
    }

    public function subscriptionTransactions($status) {
        $all_subscriptions = '';
        if($status == null) {
            $all_subscriptions = SubscriptionTransaction::with('plan')->where('user_id', auth()->user()->id)->get();
        }
        else {
            $all_subscriptions = SubscriptionTransaction::with('plan')->where('user_id', auth()->user()->id)->where('status', $status)->get();
        }
        return $this->success($all_subscriptions, 200);
    }

    public function filterTransactions($request) {
        $validator = Validator::make($request->all(),[
            'from_date' => 'required',
            'to_date' => 'required'
        ]);

        if($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $filter = SubscriptionTransaction::where('user_id', auth()->user()->id)->where('created_at', '>=', $request->from_date)->Where('created_at', '<=', $request->to_date)->get();

        return $this->success($filter, 200);
    }

}