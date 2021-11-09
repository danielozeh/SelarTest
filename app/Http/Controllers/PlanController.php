<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Plan\PlanInterface;

use App\Http\Traits\Response;

class PlanController extends Controller
{
    use Response;
    protected $plan;

    public function __construct(PlanInterface $plan) {
        $this->plan = $plan;
    }

    public function getAllPlan(Request $request, $status = null) {
        try {
            return $this->plan->getAllPlan($status);

        } catch (\Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    public function editPlan(Request $request, $id) {
        try {
            return $this->plan->editPlan($request, $id);

        } catch (\Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    public function deletePlan(Request $request, $id) {
        try {
            return $this->plan->deletePlan($id);

        } catch (\Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    public function addNewPlan(Request $request) {
        try {
            return $this->plan->addNewPlan($request);

        } catch (\Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }

    public function updatePlanFeatures(Request $request, $plan_id) {
        try {
            return $this->plan->updatePlanFeatures($plan_id);

        } catch (\Exception $e) {
            return $this->internalServerError($e->getMessage());
        }
    }
}
