<?php

namespace App\Repositories\Plan;

use App\Repositories\Plan\PlanInterface;
use App\Http\Traits\Response;
use Validator;

use App\Helper;

use App\Models\Plan;


/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */

class PlanRepository implements PlanInterface {

    use Response;

    public function getAllPlan($status) {
        $all_plan = '';
        if($status == null) {
            $all_plan = Plan::with('variations')->with(['features' => function($q) {
                $q->leftJoin('features', function($join) {
                    $join->on('plan_features.feature_id', '=', 'features.id');
                })
                ->select('plan_features.*', 'features.title')
                ->get();
            }])->get();
        }
        else {
            $all_plan = Plan::with('variations')->with(['features' => function($q) {
                $q->leftJoin('features', function($join) {
                    $join->on('plan_features.feature_id', '=', 'features.id');
                })
                ->select('plan_features.*', 'features.title')
                ->get();
            }])
            ->where('status', $status)
            ->get();
        }

        return $this->success($all_plan, 200);

    }

}