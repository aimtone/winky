<?php

namespace App\Http\Controllers;
use Webpatser\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Plan;
use App\Planitem;
use App\Plan_Planitem;

class Plan_PlanitemController extends Controller
{
  public function getPlan_Planitem($plan_uuid) { 
    $plan = (Plan::where('uuid', '=', $plan_uuid)->where('status', '=', 1)->get())[0];
    $plan['items'] = Plan_Planitem::where('plan_uuid', '=', $plan_uuid)->where('status', '=', 1)->get();
    foreach ($plan['items'] as $item) {
      $item['description'] = (Planitem::where('uuid', '=', $item->planitem_uuid)->where('status', '=', 1)->get())[0]->description;
    }
    return $plan;
  }
}
