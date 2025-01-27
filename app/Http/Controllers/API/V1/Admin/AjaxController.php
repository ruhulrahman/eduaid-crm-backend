<?php

namespace App\Http\Controllers\API\V1\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
// use Carbon\Carbon;

class AjaxController extends Controller
{
    public function get(Request $req, $name)
    {
        $user = Auth::user();
        $data['user'] = $user;
        $default_per_page = 10;
        $request = $req;
        $carbon = new Carbon();

        if ($name == 'get_auth_user') {

            $auth_user = model('User')::with('role')->find($user->id);

            $permissionIds = model('PermissionRole')::where('role_id', $user->role_id)->pluck('permission_id');
            $permission_codes = model('Permission')::whereIn('id', $permissionIds)->pluck('code');
            $auth_user->permission_codes = model('Permission')::whereIn('id', $permissionIds)->pluck('code');

            // if ($auth_user->media_id) {
            //     $media = model('Media')::find($auth_user->media_id);

            //     // $url = Storage::url('file.jpg');
            //     $auth_user->url = \Storage::url('app/public/'.$media->file);
            // }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'auth_user' => $auth_user,
                'user_permissions' => $permission_codes,
            ], 200);
        } else if ($name == 'get_common_dropdowns_list') {

            $dropdowns = new \stdClass;

            $dropdowns->countries = model('Country')::orderBy('name', 'asc')->get();
            foreach($dropdowns->countries as $item) {
                $item->value = $item->id;
                $item->label = $item->name;
            }

            $dropdowns->statuses = model('Status')::orderBy('id', 'asc')->get();
            foreach($dropdowns->statuses as $item) {
                $item->value = $item->id;
                $item->label = $item->name;
            }

            $dropdowns->status_groups = model('StatusGroup')::with('statuses')->get();
            foreach($dropdowns->status_groups as $item) {
                $item->value = $item->id;
                $item->label = $item->name;

                foreach($item->statuses as $status) {
                    $status->value = $status->id;
                    $status->label = $status->name;
                }
            }



            $dropdowns->services = model('Service')::where('active', 1)->orderBy('name', 'asc')->get();
            foreach($dropdowns->services as $item) {
                $item->value = $item->id;
                $item->label = $item->name;
            }

            $dropdowns->channels = model('Channel')::where('active', 1)->orderBy('name', 'asc')->get();
            foreach($dropdowns->channels as $item) {
                $item->value = $item->id;
                $item->label = $item->name;
            }

            $dropdowns->courseLevels = model('CourseLevel')::where('active', 1)->orderBy('name', 'asc')->get();
            foreach($dropdowns->courseLevels as $item) {
                $item->value = $item->id;
                $item->label = $item->name;
            }

            $dropdowns->languageTests = model('LanguageTest')::where('active', 1)->orderBy('name', 'asc')->get();
            foreach($dropdowns->languageTests as $item) {
                $item->value = $item->id;
                $item->label = $item->name;
            }

            $dropdowns->socials = model('Social')::where('active', 1)->orderBy('media_name', 'asc')->get();
            foreach($dropdowns->socials as $item) {
                $item->value = $item->id;
                $item->label = $item->media_name;
            }

            $dropdowns->serviceStatuses = model('ServiceStatus')::where('active', 1)->orderBy('name', 'asc')->get();
            foreach($dropdowns->serviceStatuses as $item) {
                $item->value = $item->id;
                $item->label = $item->name;
            }

            $dropdowns->dependentTypes = [
                [ 'id' => 'Child', 'label' => 'Child' ],
                [ 'id' => 'Parents', 'label' => 'Parents' ],
                [ 'id' => 'Sibling', 'label' => 'Sibling' ],
                [ 'id' => 'Spouse', 'label' => 'Spouse' ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'dropdowns' => $dropdowns
            ], 200);

        } else if ($name == 'get_dashboard_data') {

            $query = model('Invoice')::whereDate('start_date', $req->start_date);

            $dashboardData = new \StdClass;

            $dashboardData->total_user = model('User')::where(['active' => 1, 'user_type' => 'app_user'])->count();
            $dashboardData->total_paid_user = model('UserSubscription')::count();
            $dashboardData->total_active_paid_user = model('UserSubscription')::where(['active' => 1])->count();

            $invoiceQuery = model('Invoice')::whereNotNull('payment_invoice_id');

            $invoice_ids_today = (clone $invoiceQuery)->whereDate('created_at', Carbon::now())->pluck('id');
            $invoice_ids_this_month = (clone $invoiceQuery)->whereMonth('created_at', Carbon::now())
            ->pluck('id');
            $invoice_ids_last_6th_month = (clone $invoiceQuery)->whereDate('created_at', '<=', Carbon::now())
            ->whereDate('created_at', '>=', Carbon::now()->subMonth(6))
            ->pluck('id');
            $invoice_ids_this_year = (clone $invoiceQuery)->whereYear('created_at', Carbon::now())->pluck('id');

            $dashboardData->total_earning_today = (clone $invoiceQuery)->whereIn('id', $invoice_ids_today)->sum('payable_amount');
            $dashboardData->total_earning_this_month = (clone $invoiceQuery)->whereIn('id', $invoice_ids_this_month)->sum('payable_amount');
            $dashboardData->total_earning_last_6th_month = (clone $invoiceQuery)->whereIn('id', $invoice_ids_last_6th_month)->sum('payable_amount');
            $dashboardData->total_earning_this_year = (clone $invoiceQuery)->whereIn('id', $invoice_ids_this_year)->sum('payable_amount');
            $dashboardData->recentCustomers = (clone $invoiceQuery)->with('customer')->latest()->take(5)->get();

            $dashboardData->currentMonth = (int) date('m');

            $monthList=[];

            $count = 0;

            for($i=$dashboardData->currentMonth; $i>=1; $i--){
                if ($count == 6) {
                    break;
                }
                // $month = new \stdClass;
                // $month->month_number = $i;
                // $month->month_name = date('M', mktime(0, 0, 0, $i, 1));

                $month = [];
                $month['month_number'] = $i;
                $month['month_name'] = date('M', mktime(0, 0, 0, $i, 1));

                array_push($monthList, $month);

                $count++;
            }

            $array_temp_id = array_column($monthList,'month_number'); // which column needed to be sorted
            array_multisort($array_temp_id, SORT_ASC, $monthList);

            // $dashboardData->months = (clone $invoiceQuery)->select(
            //     DB::raw("MONTHNAME(created_at) as month_name")
            // )->whereYear('created_at', date('Y'))
            // ->groupBy('month_name')
            // ->get();

            $dashboardData->monthList = [];

            foreach($monthList as $item) {
                $data = [];
                $data['month_number'] = $item['month_number'];
                $data['month_name'] = $item['month_name'];
                $data['earning'] = (clone $invoiceQuery)->whereMonth('created_at', $item['month_number'])->sum('payable_amount');
                array_push($dashboardData->monthList, $data);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'data' => $dashboardData
            ], 200);
        } else if ($name == "get_statuses_with_groups") {

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'status_groups' => model('StatusGroup')::with('statuses')->get(),
                'statuses' => model('Status')::with('group')->orderBy('serial', 'asc')->get()
            ], 200);
        } else if ($name == "get_permission_list") {

            $query = model('Permission')::with('parent')->latest();

            if ($request->name) {
                $query = $query->where('name', 'like', "%{$request->name}%");
            }

            if ($request->type) {
                $query = $query->where('type', $request->type);
            }

            if ($request->code) {
                $query = $query->where('code', $request->code);
            }

            $list = $query->paginate($default_per_page);

            foreach ($list as $item) {
                $item->active = $item->active == 1 ? TRUE : FALSE;
            }

            if (!$list) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found!'
                ], 402);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'data' => $list
            ], 200);
        } else if ($name == "get_permission_parent_list") {

            $list = model('Permission')::whereNull('parent_id')->select('id as value', 'name as text', 'type')->get();
            // $list = model('Permission')::where('type', 'Page')->select('id as value', 'name as text', 'type')->get();

            if (!$list) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found!'
                ], 402);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'data' => $list
            ], 200);
        } else if ($name == "get_permission_parent_and_child_list") {

            $rolePermissionIds = model('PermissionRole')::where('role_id', $req->role_id)->pluck('permission_id')->toArray();

            $query = model('Permission')::query();

            $permissionParentList = (clone $query)->whereNull('parent_id')->orderBy('name', 'asc')->get();

            foreach($permissionParentList as $item) {
                $item->children_pages = (clone $query)->where([
                    'parent_id' => $item->id,
                    'type' => 'Page',
                ])->orderBy('name', 'asc')->get();

                $item->children_operations = (clone $query)->where([
                    'parent_id' => $item->id,
                    'type' => 'Feature',
                ])->orderBy('name', 'asc')->get();

                $permission_ids = (clone $query)->where([
                    'parent_id' => $item->id,
                ])->pluck('id')->toArray();

                $parent_permission_ids = [$item->id];
                $item->children_permission_ids = array_merge($permission_ids, $parent_permission_ids);

                $permissionCheck = false;

                foreach($item->children_permission_ids as $permissionId) {

                    $permissionCheck = in_array($permissionId, $rolePermissionIds);

                }

                $item->checkAll = $permissionCheck;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'data' => $permissionParentList,
                // 'userPermissionIds' => $userPermissionIds,
            ], 200);

        } else if ($name == "get_permissions_by_role_id") {

            $validator = Validator::make($req->all(), [
                'role_id' => 'required',
            ]);

            if ($validator->fails()) {

                $errors = $validator->errors()->all();
                return response(['msg' => $errors[0]], 422);
            }


            $role = model('Role')::find($req->role_id);


            $role->role_permission_ids = model('PermissionRole')::where('role_id', $req->role_id)->pluck('permission_id');

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'data' => $role,
            ], 200);

        } else if ($name == "get_role_list") {

            $query = DB::table('roles')->latest();

            if ($request->name) {
                $query = $query->where('name', 'like', "%{$request->name}%");
            }

            if ($request->type) {
                $query = $query->where('type', $request->type);
            }

            if ($request->code) {
                $query = $query->where('code', $request->code);
            }

            $list = $query->paginate($default_per_page);

            foreach ($list as $item) {
                $item->active = $item->active == 1 ? TRUE : FALSE;
            }

            if (!$list) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found!'
                ], 402);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'data' => $list
            ], 200);
        } else if ($name == 'get_user_all_list') {

            $query = DB::table('users')
                ->select('id', 'name as label', 'phone')
                ->orderBy('name', 'asc')
                ->get();

            if (!$query) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found!'
                ], 402);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'data' => $query
            ], 201);
        } else if ($name == 'get_all_role_list') {

            $query = DB::table('roles')
                ->select('id as value', 'name as text', 'active', 'code')
                ->orderBy('name', 'desc')
                ->get();

            if (!$query) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found!'
                ], 402);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'data' => $query
            ], 201);
        } else if ($name == 'get_user_list') {

            $query = model('User')::with('role')
                ->where('user_type', 'admin')
                ->latest();

            if ($request->name) {
                $query->where('name', 'Like', "%$request->name%");
            }

            if ($request->email) {
                $query->where('email', $request->email);
            }

            if ($request->phone) {
                $query->where('phone', $request->phone);
            }

            if ($request->role_id) {
                $query->where('phone', $request->role_id);
            }

            $list = $query->paginate($default_per_page);

            foreach ($list as $item) {
                $item->active = $item->active == 1 ? TRUE : FALSE;
            }

            if (!$list) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found!'
                ], 402);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'data' => $list
            ], 200);
        } else if ($name == 'get_current_profile_list') {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found!'
                ], 402);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch Sucessfully!',
                'data' => $user
            ], 200);
        } else if ($name == 'get_parent_name_wise_permission') {

            $list = model('Permission')::select('parent_id', 'type', 'name', 'code')
                ->orderBy('parent_id')
                ->orderBy('type')
                ->whereNotNull('parent_id')
                ->get();

            $arr = [];

            foreach ($list as $listItem) {
                $arr[$listItem->parent_id][$listItem->type][] = [
                    'name' => $listItem->name,
                    'code' => $listItem->code,
                ];
            }

            $collectionArr = [];

            foreach ($arr as $parentId => $typeArr) {
                $collectionArr[] = [
                    'parent_id' => $parentId,
                    'type' => key($typeArr), // Add 'type' here
                    'permission_info' => reset($typeArr), // Add 'permission_info' here
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $collectionArr
            ], 200);
        } else if ($name == 'get_service_list') {

            $query = model('Service')::with('parent', 'country', 'account');


            $parentList = (clone $query)->orderBy('name')->whereNull('parent_id')->get();
            $fullList = (clone $query)->orderBy('name')->get();

            if ($request->name) {
                $query = $query->where('name', 'like', "%{$request->name}%");
            }

            if ($request->description) {
                $query = $query->where('description', 'like', "%{$request->description}%");
            }

            if ($request->parent_id) {
                $query = $query->where('parent_id', $request->parent_id);
            }

            if ($request->country_id) {
                $query = $query->where('country_id', $request->country_id);
            }

            if ($request->account_id) {
                $query = $query->where('account_id', $request->account_id);
            }

            $list = (clone $query)->orderBy('id', 'desc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->active = $item->active ? true : false;
            }

            foreach($parentList as $item) {
                $item->label = $item->name;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
                'fullList' => $fullList,
                'parentList' => $parentList,
            ], 200);
        } else if ($name == 'get_parent_service_list') {

            $query = model('Service')::with('country')
            ->where('active', 1)
            ->whereNull('parent_id');

            // if ($request->name) {
            //     $query = $query->where('name', 'like', "%{$request->name}%");
            // }

            if ($request->description) {
                $query = $query->where('description', 'like', "%{$request->description}%");
            }

            if ($request->parent_id) {
                $query = $query->where('parent_id', $request->parent_id);
            }

            if ($request->country_id) {
                $query = $query->where('country_id', $request->country_id);
            }

            if ($request->account_id) {
                $query = $query->where('account_id', $request->account_id);
            }

            $list = (clone $query)->orderBy('id', 'desc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->question_count = model('PointQuestion')::where([
                    'service_id' => $item->id,
                    'country_id' => $item->country_id,
                ])->count();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
            ], 200);

        } else if ($name == 'get_point_question_list') {

            $query = model('PointQuestion')::with('service', 'country', 'breakdowns');

            if ($request->question) {
                $query = $query->where('question', 'like', "%{$request->question}%");
            }

            if ($request->service_id) {
                $query = $query->where('service_id', $request->service_id);
            }

            if ($request->country_id) {
                $query = $query->where('country_id', $request->country_id);
            }

            // $fullList = (clone $query)->orderBy('id', 'desc')->get();
            $list = (clone $query)->orderBy('id', 'desc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->active = $item->active ? true : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
                // 'fullList' => $fullList,
            ], 200);
        } else if ($name == 'get_point_question_list_by_service_id') {

            $service = model('Service')::with('country')->find($req->service_id);

            $query = model('PointQuestion')::with('service', 'country', 'breakdowns')->where([
                'service_id' => $service->id,
                'country_id' => $service->country_id,
            ]);

            if ($request->question) {
                $query = $query->where('question', 'like', "%{$request->question}%");
            }

            // if ($request->service_id) {
            //     $query = $query->where('service_id', $request->service_id);
            // }

            // if ($request->country_id) {
            //     $query = $query->where('country_id', $request->country_id);
            // }

            // $fullList = (clone $query)->orderBy('id', 'desc')->get();
            $list = (clone $query)->orderBy('id', 'desc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->active = $item->active ? true : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'service' => $service,
                'data' => $list,
                // 'fullList' => $fullList,
            ], 200);
        } else if ($name == 'get_course_level_list') {

            $query = model('CourseLevel')::query();

            if ($request->name) {
                $query = $query->where('name', 'like', "%{$request->name}%");
            }

            if ($request->short_name) {
                $query = $query->where('short_name', 'like', "%{$request->short_name}%");
            }

            $list = (clone $query)->orderBy('serial', 'asc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->active = $item->active ? true : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
            ], 200);

        } else if ($name == 'get_channel_list') {

            $query = model('Channel')::query();

            if ($request->name) {
                $query = $query->where('name', 'like', "%{$request->name}%");
            }

            if ($request->icon_name) {
                $query = $query->where('icon_name', 'like', "%{$request->icon_name}%");
            }

            if ($request->icon_value) {
                $query = $query->where('icon_value', 'like', "%{$request->icon_value}%");
            }

            $list = (clone $query)->orderBy('serial', 'asc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->active = $item->active ? true : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
            ], 200);

        } else if ($name == 'get_language_test_list') {

            $query = model('LanguageTest')::with('parent');

            $parentList = (clone $query)->whereNull('parent_id')->orderBy('name', 'asc')->get();

            if ($request->name) {
                $query = $query->where('name', 'like', "%{$request->name}%");
            }

            if ($request->short_name) {
                $query = $query->where('short_name', 'like', "%{$request->short_name}%");
            }

            if ($request->parent_id) {
                $query = $query->where('parent_id', $request->parent_id);
            }
            $list = (clone $query)->orderBy('id', 'desc')->paginate($default_per_page);

            foreach($parentList as $item) {
                $item->label = $item->name;
            }

            foreach($list as $item) {
                $item->active = $item->active ? true : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'parentList' => $parentList,
                'data' => $list,
            ], 200);

        } else if ($name == 'get_social_list') {

            $query = model('Social')::query();

            if ($request->media_name) {
                $query = $query->where('media_name', 'like', "%{$request->media_name}%");
            }

            if ($request->icon_name) {
                $query = $query->where('icon_name', 'like', "%{$request->icon_name}%");
            }

            if ($request->icon_value) {
                $query = $query->where('icon_value', 'like', "%{$request->icon_value}%");
            }

            $list = (clone $query)->orderBy('media_name', 'asc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->active = $item->active ? true : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
            ], 200);

        } else if ($name == 'get_lead_list') {

            $query = model('Lead')::with('country', 'mobile_country', 'supervisor');

            if ($request->first_name) {
                $query = $query->where('first_name', 'like', "%{$request->first_name}%");
            }

            if ($request->last_name) {
                $query = $query->where('last_name', 'like', "%{$request->last_name}%");
            }

            if ($request->nick_name) {
                $query = $query->where('nick_name', 'like', "%{$request->nick_name}%");
            }

            if ($request->mobile) {
                $query = $query->where('mobile', "like", "%$request->mobile%");
            }

            if ($request->email) {
                $query = $query->where('email', $request->email);
            }

            if ($request->country_id) {
                $query = $query->where('country_id', $request->country_id);
            }

            if ($request->supervisor_id) {
                $query = $query->where('supervisor_id', $request->supervisor_id);
            }

            $list = (clone $query)->orderBy('id', 'desc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->is_client = $item->is_client ? true : false;
                $item->is_married = $item->is_married ? true : false;
                $item->active = $item->active ? true : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
            ], 200);
        } else if ($name == 'get_lead_details_by_id') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            $lead = model('Lead')::with([
                'country',
                'mobile_country',
                'supervisor',
                'dependents.mobile_country',

                'candidate_socials.social',
                'dependent_socials.social',
                'dependent_socials.dependent',

                'candidate_contacts',
                'dependent_contacts.dependent',

                'dependent_emergency_contacts.dependent',
                'dependent_emergency_contacts.mobile_country',
                'candidate_emergency_contacts.mobile_country',

                'dependent_employment_histories.dependent',
                'dependent_employment_histories.country',
                'candidate_employment_histories.country',

                'services.country',
                'services.service',
                'services.service_status',
                'services.service_histories.country',
                'services.service_histories.service',
                'services.service_histories.service_status',

                'candidate_visa_histories.visa_type',
                'candidate_visa_histories.country',
                'candidate_visa_histories.media',
                'dependent_visa_histories.dependent',
                'dependent_visa_histories.visa_type',
                'dependent_visa_histories.country',
                'dependent_visa_histories.media',

                'candidate_education_histories.country',
                'candidate_education_histories.course_level',
                'candidate_education_histories.result_type',
                'dependent_education_histories.dependent',
                'dependent_education_histories.country',
                'dependent_education_histories.course_level',
                'dependent_education_histories.result_type',

                'question_points.service' => function ($q) {
                   return $q->select(['id', 'name']);
                },
                'question_points.question' => function ($q) {
                   return $q->select(['id', 'question', 'service_id']);
                },
                'question_points.qp_breakdown' => function ($q) {
                    return $q->select(['id', 'value', 'question_id']);
                },


                'candidate_english_tests.language_test' => function ($query) {
                    return $query->select(['id', 'name']);
                },
                'candidate_english_tests.level' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'dependent_english_tests.dependent' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'dependent_english_tests.language_test' => function ($query) {
                    return $query->select(['id', 'name']);
                },
                'dependent_english_tests.level' => function ($q) {
                    return $q->select(['id', 'name']);
                },


                'candidate_english_test_results.english_language_test',
                'candidate_english_test_results.language_test' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'candidate_english_test_results.child_language_test' => function ($q) {
                    return $q->select(['id', 'name']);
                },

                'dependent_english_test_results.dependent' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'dependent_english_test_results.english_language_test',
                'dependent_english_test_results.language_test' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'dependent_english_test_results.child_language_test' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'lead_payments'
            ])->find($req->id);

            if($lead) {
                $lead->is_client = $lead->is_client ? true : false;
                $lead->is_married = $lead->is_married ? true : false;
                $lead->active = $lead->active ? true : false;

                foreach($lead->candidate_socials as $item) {
                    $item->active = $item->active ? true : false;
                }

                foreach($lead->dependent_socials as $item) {
                    $item->active = $item->active ? true : false;
                }

                foreach($lead->candidate_employment_histories as $item) {
                    $item->still_working = $item->still_working ? true : false;
                    $item->is_foreign = $item->is_foreign ? true : false;
                    $item->is_primary = $item->is_primary ? true : false;
                }

                foreach($lead->dependent_employment_histories as $item) {
                    $item->still_working = $item->still_working ? true : false;
                    $item->is_foreign = $item->is_foreign ? true : false;
                    $item->is_primary = $item->is_primary ? true : false;
                }

                foreach($lead->services as $item) {
                    $item->active = $item->active ? true : false;
                }

                foreach($lead->candidate_education_histories as $item) {
                    $item->is_foreign_institute = $item->is_foreign_institute ? true : false;
                }

                foreach($lead->dependent_education_histories as $item) {
                    $item->is_foreign_institute = $item->is_foreign_institute ? true : false;
                }

                foreach($lead->candidate_english_tests as $item) {
                    $item->is_primary = $item->is_primary ? true : false;
                }

                foreach($lead->dependent_english_tests as $item) {
                    $item->is_primary = $item->is_primary ? true : false;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $lead,
            ], 200);
        } else if ($name == 'get_lead_services_by_lead_id') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            $list = model('LeadService')::with('country', 'service')->where([
                'lead_id' => $req->id,
                'active' => 1,
            ])->get();

            foreach($list as $item) {
                $item->questions = model('PointQuestion')::where([
                    'service_id' => $item->service_id,
                    'country_id' => $item->country_id,
                ])->get();

                foreach($item->questions as $question) {

                    $question->qp_breakdown_id = NULL;

                    $question->breakdowns = model('QuestionPointBreakdown')::where([
                        'question_id' => $question->id,
                    ])->select('id', 'value', 'value as label', 'point')
                    ->get();

                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
            ], 200);

        } else if ($name == 'get_lead_list_id_wise') {

            $data = model('Lead')::find($req->id);

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found!',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $data,
            ], 200);

        } else if ($name == 'get_status_group_list') {

            $query = model('StatusGroup')::with('statuses');

            if ($request->name) {
                $query = $query->where('name', 'like', "%{$request->name}%");
            }

            if ($request->code) {
                $query = $query->where('code', 'like', "%{$request->code}%");
            }

            $list = (clone $query)->orderBy('id', 'desc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->active = $item->active ? true : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
            ], 200);
        } else if ($name == 'get_statuses_list') {

            $query = model('Status')::where('status_group_id', $req->status_group_id);

            if ($request->name) {
                $query = $query->where('name', 'like', "%{$request->name}%");
            }

            if ($request->code) {
                $query = $query->where('code', 'like', "%{$request->code}%");
            }

            if ($request->color_name) {
                $query = $query->where('color_name', $request->color_name);
            }

            if ($request->serial) {
                $query = $query->where('serial', $request->serial);
            }

            $list = (clone $query)->orderBy('serial', 'asc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->active = $item->active ? true : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
            ], 200);
        } else if ($name == 'get_course_level_last_serial') {

            try {

                $maxSerial = model('CourseLevel')::select('serial')->max('serial');

                return response()->json([
                    'success' => true,
                    'message' => 'Data fetch successfully',
                    'data'  => $maxSerial + 1
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'get_lead_payment_last_serial') {

            try {

                $maxSerial = model('LeadPayment')::select('sl_no')->max('sl_no');

                return response()->json([
                    'success' => true,
                    'message' => 'Data fetch successfully',
                    'data'  => $maxSerial + 1
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'get_channel_last_serial') {

            try {

                $maxSerial = model('Channel')::select('serial')->max('serial');

                return response()->json([
                    'success' => true,
                    'message' => 'Data fetch successfully',
                    'data'  => $maxSerial + 1
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'get_status_last_serial') {

            try {

                $maxSerial = model('Status')::where('status_group_id', $req->status_group_id)->select('serial')->max('serial');

                return response()->json([
                    'success' => true,
                    'message' => 'Data fetch successfully',
                    'data'  => $maxSerial + 1
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'get_question_point') {

            $query = model('PointQuestion')::select('id', 'question as label', 'question', 'service_id', 'country_id')
                    ->orderBy('question', 'asc')
                    ->get();

            //  return $query;

              if (!$query)  {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found'
                ]);
              }

              return response()->json([
                'success' => true,
                'message' => 'Data fetch successfully',
                'data'  => $query
            ], 200);
        } else if ($name == 'get_question_point_breakdowns') {

            $query = model('QuestionPointBreakdown')::select('id', 'value as label', 'value', 'question_id', 'point')
                ->orderBy('value', 'asc')
                ->get();

            if (!$query)  {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch successfully',
                'data'  => $query
            ], 200);

        } else if ($name == 'get_language_test_dropdown_list') {

            $query = model('LanguageTest')::select('id', 'id as value', 'name as label', 'parent_id')
                ->orderBy('name', 'asc')
                ->get();

            if (!$query)  {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch successfully',
                'data'  => $query
            ], 200);

        } else if ($name == 'get_lead_english_test_dropdown_list') {

            // $language_test_ids = model('LeadEnglishTest')::where('dependent_id', $req->dependent_id)->pluck('language_test_id');
            $language_test_ids = model('LeadEnglishTest')::where('lead_id', $req->lead_id)->pluck('language_test_id');

            $query = model('LanguageTest')::select('id', 'id as value', 'name as label', 'parent_id')
                ->whereIn('id', $language_test_ids)
                ->whereNull('parent_id')
                ->orderBy('name', 'asc')
                ->get();

            if (!$query)  {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch successfully',
                'data'  => $query
            ], 200);

        } else if ($name == 'get_child_english_test_dropdown_list') {

            $query = model('LanguageTest')::select('id', 'id as value', 'name as label', 'parent_id')
                ->where('parent_id', $req->parent_id)
                ->orderBy('name', 'asc')
                ->get();

            if (!$query)  {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Not Found'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetch successfully',
                'data'  => $query
            ], 200);

        } else if ($name == 'get_service_status_list') {

            $query = model('ServiceStatus')::with('service', 'country');

            if ($request->name) {
                $query = $query->where('name', 'like', "%{$request->name}%");
            }

            if ($request->service_id) {
                $query = $query->where('service_id', $request->service_id);
            }

            if ($request->country_id) {
                $query = $query->where('country_id', $request->country_id);
            }

            $list = (clone $query)->orderBy('id', 'desc')->paginate($default_per_page);

            foreach($list as $item) {
                $item->active = $item->active ? true : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data fetched Successfully!',
                'data' => $list,
                // 'fullList' => $fullList,
            ], 200);
        }


        return response(['msg' => 'Sorry!, found no named argument.'], 403);
    }



    // Post functions are here .............
    public function post(Request $req, $name)
    {
        $user = Auth::user();
        $data['user'] = $user;
        $request = $req;
        $carbon = new Carbon();

        if ($name == 'store_permission_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $input = $request->all();
                $model = model('Permission')::create($input);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_permission_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            $permission = model('Permission')::find($req->id);

            if (!$permission) {
                return response([
                    'success' => false,
                    'message' => 'Data not found.'
                ]);
            }

            try {

                $permission->type  = $request->type;
                $permission->name  = $request->name;
                // $permission->code  = $request->code;
                $permission->parent_id = $request->parent_id;
                $permission->save();
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data updated Successfully',
                'data'    => $permission
            ], 200);
        } else if ($name == 'toggle_permission_active_status') {

            $Permission = model('Permission')::find($req->id);
            $Permission->active = $req->active == 'true' ? 1 : 0;
            $Permission->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'data'    => $Permission
            ], 200);

            // return res_msg('Permission active status updated successfully!', 200);
        } else  if ($name == 'store_role_data') {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
                'code' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $input = $request->all();
                $model = model('Role')::create($input);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_role_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
                'code' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            $role = model('Role')::find($req->id);

            if (!$role) {
                return response([
                    'success' => false,
                    'message' => 'Data not found.'
                ]);
            }

            try {
                $role->type  = $request->type;
                $role->name  = $request->name;
                $role->code  = $request->code;
                $role->save();
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data update Successfully',
                'data'    => $role
            ], 200);
        } else  if ($name == 'store_role_permission_data') {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
                'code' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $role = model('Role')::create([
                    'type' => $req->type,
                    'name' => $req->name,
                    'code' => $req->code,
                ]);

                foreach($req->role_permission_ids as $permission_id) {
                    model('PermissionRole')::create([
                        'permission_id' => (int) $permission_id,
                        'role_id' => $role->id,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Role permission inserted Successfully',
                    'data'  => $role
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else  if ($name == 'update_role_permission_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
                'code' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $role = model('Role')::find($req->id);
                $role->update([
                    // 'type' => $req->type,
                    'name' => $req->name,
                    // 'code' => $req->code,
                ]);

                model('PermissionRole')::where('role_id', $role->id)->delete();

                foreach($req->role_permission_ids as $permission_id) {
                    model('PermissionRole')::create([
                        'permission_id' => (int) $permission_id,
                        'role_id' => $role->id,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Role permission updated Successfully',
                    'data'  => $role
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'toggle_role_active_status') {

            $role = model('Role')::find($req->id);
            $role->active = $req->active == 'true' ? 1 : 0;
            $role->save();

            return response()->json([
                'success' => true,
                'message' => 'Status Changed successfully!',
                'data'    => $role
            ], 200);
        } else if ($name == 'delete_role_data') {

            $roleDelete = model('Role')::find($req->id);

            if (!$roleDelete) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found.'
                ]);
            }
            $roleDelete->delete();
            // $role->active = $req->active == 'true' ? 1 : 0;
            // $role->save();

            return response()->json([
                'success' => true,
                'message' => 'Delete successfully!'
            ], 200);
        } else if ($name == 'delete_role_permission_data') {

            $role = model('Role')::find($req->id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found.'
                ]);
            }
            model('PermissionRole')::where('role_id', $role->id)->delete();
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role permission deleted successfully!'
            ], 200);

        } else if ($name == 'store_user_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'password' => 'required|string|min:8',
                'confirm_password' => 'required|same:password',
                // 'role_id' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $input = $request->all();
                $input['user_type'] = 'admin';
                $input['password'] = bcrypt($req->password);
                $model = model('User')::create($input);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'update_user_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email,'. $req->id,
                'phone' => 'required|unique:users,phone,' . $req->id
                // 'role_id' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            $updateUser = model('User')::find($req->id);

            if (!$updateUser) {
                return response([
                    'success' => false,
                    'message' => 'Data not found.'
                ]);
            }

            try {

                $requestAll = $request->all();
                $requestAll['name'] = $request->name;
                $requestAll['email'] = $request->email;
                $requestAll['phone'] = $request->phone;
                $requestAll['active'] = $request->active == 'true' ? 1 : 0;

                if ($req->password) {
                    $validate = validate_ajax([
                        'password' => 'required|string|min:8',
                        'confirm_password' => 'required|same:password',
                    ]);

                    if ($validate->fails()) {
                        return response()->json([
                            'status' => false,
                            'message' => 'validation error',
                            'errors' => $validate->errors()
                        ], 422);
                    }

                    $requestAll['password'] = bcrypt($req->password);
                }

                $updateUser->fill($requestAll);
                $updateUser->save();

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data Update successfully',
                'data'    => $updateUser
            ], 200);

        } else if ($name == 'toggle_user_status') {

            $toggleUser = model('User')::find($req->id);

            if (!$toggleUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found.'
                ]);
            }
            $toggleUser->active = $req->active == 'true' ? 1 : 0;
            $toggleUser->save();

            return response()->json([
                'success' => true,
                'message' => 'Status Changed successfully!',
                'data'    => $toggleUser
            ], 200);
        } else if ($name == 'delete_user_data') {

            $roleDelete = model('User')::find($req->id);

            if (!$roleDelete) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found.'
                ]);
            }

            $roleDelete->delete();
            return response()->json([
                'success' => true,
                'message' => 'Delete successfully!'
            ], 200);
        } else if ($name == 'delete_permission_data') {

            try {
                $permissionDelete = model('Permission')::find($req->id);

                if (!$permissionDelete) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data not found',
                    ], 422);
                }

                $permissionDelete->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted Successfully',
                    'data'  => $permissionDelete
                ], 201);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'change_password_data') {

            // $authUser = model('User')::find($user->id);

            // if(strcmp($request->get('new_password'), $user->password) == 0){
            //     // Current password and new password same
            //     return response([
            //         'success' => false,
            //         'message' => 'New Password cannot be same as your current password'
            //     ]);
            // }

            $validator = Validator::make(
                $request->all(),
                [
                    'old_password' => 'required',
                    'new_password' => 'required|string|min:6|required_with:confirm_password|same:confirm_password',
                    'confirm_password' => 'required|min:6',
                ],
                [
                    'new_password.min' => 'New Password Should be Minimum of 6 Character',
                    'new_password.same' => 'Password & Repeat New Password not match',
                    'confirm_password.min' => 'Repeat New Password Should be Minimum of 6 Character',
                ]
            );

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ];
            }

            if (!Hash::check($req->old_password, $user->password)) {

                $errors = new \StdClass;
                $errors->old_password = 'Old Password does not match';

                return response([
                    'success' => false,
                    'errors' => $errors,
                    'message' => 'Old Password does not match'
                ]);
            }


            //Change Password
            $user->password = bcrypt($request->new_password);
            $user->save();

            return [
                'success' => true,
                'message' => 'Password successfully changed!'
            ];
        } else if ($name == 'profile_update_data') {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ];
            }

            try {

                //    info($request->all());
                $authUser = model('User')::find($user->id);

                if ($req->photo instanceof \Illuminate\Http\UploadedFile) {

                    if ($authUser->media_id) {
                        delete_media($authUser->media_id);
                    }
                    $media = upload_media($req->photo, [
                        'model' => get_class($authUser),
                        'model_id' => $authUser->id,
                    ]);
                }

                if (isset($media)) {
                    $authUser->media_id = $media ? $media->id : NULL;
                }

                $authUser->name = $req->name;
                $authUser->phone = $req->phone;
                $authUser->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully!',
                    'authUser' => $authUser
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'upload_user_photo') {

            $validator = Validator::make($request->all(), [
                'photo' => 'required',
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ];
            }

            try {

                //    info($request->all());
                $authUser = model('User')::find($user->id);

                if ($req->photo instanceof \Illuminate\Http\UploadedFile) {

                    if ($authUser->media_id) {
                        delete_media($authUser->media_id);
                    }

                    $media = upload_media($req->photo, [
                        'model' => get_class($authUser),
                        'model_id' => $authUser->id,
                    ]);
                }

                if (isset($media)) {
                    $authUser->media_id = $media ? $media->id : NULL;
                }

                $authUser->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully!',
                    'authUser' => $authUser
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_service_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'country_id' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('Service')::create([
                    'name' => $req->name,
                    'description' => $req->description,
                    'parent_id' => $req->parent_id != 'null' ? $req->parent_id : NULL,
                    'country_id' => $req->country_id,
                    'account_id' => $req->account_id != 'null' ? $req->account_id : NULL,
                    'active' => $req->active == 'true' ? 1 : 0,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_service_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'country_id' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $service = model('Service')::find($req->id);

                $service->update([
                    'name' => $req->name,
                    'description' => $req->description,
                    'parent_id' => $req->parent_id != 'null' ? $req->parent_id : NULL,
                    'country_id' => $req->country_id,
                    'account_id' => $req->account_id != 'null' ? $req->account_id : NULL,
                    'active' => $req->active == 'true' ? 1 : 0,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $service
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_service_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $service = model('Service')::find($req->id);

                $service->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $service
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'toggle_service_active_status') {

            $service = model('Service')::find($req->id);
            $service->active = $req->active == 'true' ? 1 : 0;
            $service->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $service
            ], 200);

            // return res_msg('Permission active status updated successfully!', 200);
        } else if ($name == 'store_point_question_data') {

            $validate = Validator::make($request->all(), [
                'question' => 'required',
                'service_id' => 'required',
                'country_id' => 'required',
                'total_weight' => 'required',
                'pass_mark' => 'required',
                'breakdowns' => 'array',
                'breakdowns.*.value' => 'required',
                'breakdowns.*.point' => 'required',
            ],[
                'question.required' => 'The question field is required',
                'service_id.required' => 'The service field is required',
                'country_id.required' => 'The country field is required',
                'total_weight.required' => 'The total weight field is required',
                'pass_mark.required' => 'The pass mark field is required',
                'breakdowns.*.value.required' => 'The breakdown value field is required',
                'breakdowns.*.point.required' => 'The breakdown point field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('PointQuestion')::create([
                    'question' => $req->question,
                    'service_id' => $req->service_id,
                    'country_id' => $req->country_id,
                    'pass_mark' => $req->pass_mark,
                    'total_weight' => $req->total_weight,
                    'pass_mark' => $req->pass_mark,
                    'active' => 1,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                foreach($req->breakdowns as $item) {

                    model('QuestionPointBreakdown')::create([
                        'question_id' => $model->id,
                        'value' => $item['value'],
                        'point' => (int) $item['point'],
                        'created_at' => Carbon::now(),
                    ]);

                }

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_point_question_data') {

            $validate = Validator::make($request->all(), [
                'question' => 'required',
                'service_id' => 'required',
                'country_id' => 'required',
                'total_weight' => 'required',
                'pass_mark' => 'required',
                'breakdowns' => 'array',
                'breakdowns.*.value' => 'required',
                'breakdowns.*.point' => 'required',
            ],[
                'question.required' => 'The question field is required',
                'service_id.required' => 'The service field is required',
                'country_id.required' => 'The country field is required',
                'total_weight.required' => 'The total weight field is required',
                'pass_mark.required' => 'The pass mark field is required',
                'breakdowns.*.value.required' => 'The breakdown value field is required',
                'breakdowns.*.point.required' => 'The breakdown point field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $pointQuestion = model('PointQuestion')::find($req->id);

                $pointQuestion->update([
                    'question' => $req->question,
                    'service_id' => $req->service_id,
                    'country_id' => $req->country_id,
                    'pass_mark' => $req->pass_mark,
                    'total_weight' => $req->total_weight,
                    'pass_mark' => $req->pass_mark,
                    // 'active' => $req->active == 'true' ? 1 : 0,
                    'updated_at' => Carbon::now(),
                ]);

                model('QuestionPointBreakdown')::where('question_id', $pointQuestion->id)->delete();

                foreach($req->breakdowns as $item) {

                    model('QuestionPointBreakdown')::create([
                        'question_id' => $pointQuestion->id,
                        'value' => $item['value'],
                        'point' => (int) $item['point'],
                        'created_at' => Carbon::now(),
                    ]);

                }

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $pointQuestion
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_point_question_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $pointQuestion = model('PointQuestion')::find($req->id);

                $pointQuestion->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $pointQuestion
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'toggle_point_question_active_status') {

            $pointQuestion = model('PointQuestion')::find($req->id);
            $pointQuestion->active = $req->active == 'true' ? 1 : 0;
            $pointQuestion->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $pointQuestion
            ], 200);

        } else if ($name == 'store_course_level_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'short_name' => 'required',
            ],[
                'name.required' => 'The name field is required',
                'short_name.required' => 'The short name field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('CourseLevel')::create([
                    'name' => $req->name,
                    'short_name' => $req->short_name,
                    'serial' => $req->serial,
                    // 'active' => $req->active == 'true' ? 1 : 0,
                    'active' => $req->active,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_course_level_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'short_name' => 'required',
            ],[
                'name.required' => 'The name field is required',
                'short_name.required' => 'The short name field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $CourseLevel = model('CourseLevel')::find($req->id);

                $CourseLevel->update([
                    'name' => $req->name,
                    'short_name' => $req->short_name,
                    'serial' => $req->serial,
                    // 'active' => $req->active == 'true' ? 1 : 0,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $CourseLevel
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_course_level_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $CourseLevel = model('CourseLevel')::find($req->id);

                $CourseLevel->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $CourseLevel
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'toggle_course_level_active_status') {

            $CourseLevel = model('CourseLevel')::find($req->id);
            $CourseLevel->active = $req->active == 'true' ? 1 : 0;
            $CourseLevel->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $CourseLevel
            ], 200);

        } else if ($name == 'store_channel_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'icon_name' => 'required',
                'icon_value' => 'required',
            ],[
                'name.required' => 'The name field is required',
                'icon_name.required' => 'The icon name field is required',
                'icon_value.required' => 'The icon value field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('Channel')::create([
                    'name' => $req->name,
                    'icon_name' => $req->icon_name,
                    'icon_value' => $req->icon_value,
                    'icon_color' => $req->icon_color,
                    'serial' => $req->serial,
                    // 'active' => $req->active == 'true' ? 1 : 0,
                    'active' => $req->active,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_channel_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'icon_name' => 'required',
                'icon_value' => 'required',
            ],[
                'name.required' => 'The name field is required',
                'icon_name.required' => 'The icon name field is required',
                'icon_value.required' => 'The icon value field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Channel = model('Channel')::find($req->id);

                $Channel->update([
                    'name' => $req->name,
                    'icon_name' => $req->icon_name,
                    'icon_value' => $req->icon_value,
                    'icon_color' => $req->icon_color,
                    'serial' => $req->serial,
                    // 'active' => $req->active == 'true' ? 1 : 0,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $Channel
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_channel_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Channel = model('Channel')::find($req->id);

                $Channel->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $Channel
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'toggle_channel_active_status') {

            $Channel = model('Channel')::find($req->id);
            $Channel->active = $req->active == 'true' ? 1 : 0;
            $Channel->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $Channel
            ], 200);

        } else if ($name == 'store_language_test_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'short_name' => 'required',
            ],[
                'name.required' => 'The name field is required',
                'short_name.required' => 'The short name field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LanguageTest')::create([
                    'name' => $req->name,
                    'short_name' => $req->short_name,
                    'parent_id' => $req->parent_id,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_language_test_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'short_name' => 'required',
            ],[
                'name.required' => 'The name field is required',
                'short_name.required' => 'The short name field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LanguageTest = model('LanguageTest')::find($req->id);

                $LanguageTest->update([
                    'name' => $req->name,
                    'short_name' => $req->short_name,
                    'parent_id' => $req->parent_id,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $LanguageTest
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_language_test_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LanguageTest = model('LanguageTest')::find($req->id);

                $LanguageTest->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $LanguageTest
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'toggle_language_test_active_status') {

            $LanguageTest = model('LanguageTest')::find($req->id);
            $LanguageTest->active = $req->active == 'true' ? 1 : 0;
            $LanguageTest->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $LanguageTest
            ], 200);

        } else if ($name == 'store_social_data') {

            $validate = Validator::make($request->all(), [
                'media_name' => 'required',
                'icon_name' => 'required',
                'icon_value' => 'required',
            ],[
                'media_name.required' => 'The media name field is required',
                'icon_name.required' => 'The icon name field is required',
                'icon_value.required' => 'The icon value field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('Social')::create([
                    'media_name' => $req->media_name,
                    'icon_name' => $req->icon_name,
                    'icon_value' => $req->icon_value,
                    'icon_color' => $req->icon_color,
                    'serial' => $req->serial,
                    // 'active' => $req->active == 'true' ? 1 : 0,
                    'active' => $req->active,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_social_data') {

            $validate = Validator::make($request->all(), [
                'media_name' => 'required',
                'icon_name' => 'required',
                'icon_value' => 'required',
            ],[
                'media_name.required' => 'The media name field is required',
                'icon_name.required' => 'The icon name field is required',
                'icon_value.required' => 'The icon value field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Social = model('Social')::find($req->id);

                $Social->update([
                    'media_name' => $req->media_name,
                    'icon_name' => $req->icon_name,
                    'icon_value' => $req->icon_value,
                    'icon_color' => $req->icon_color,
                    'serial' => $req->serial,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $Social
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_social_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Social = model('Social')::find($req->id);

                $Social->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $Social
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'toggle_social_active_status') {

            $Social = model('Social')::find($req->id);
            $Social->active = $req->active == 'true' ? 1 : 0;
            $Social->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $Social
            ], 200);

        } else if ($name == 'store_lead_data') {

            $validate = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'country_id' => 'required',
                'dob' => 'required',
                'is_married' => 'required',
                'mobile_country_id' => 'required',
                'mobile' => 'required',
                'email' => 'required',
            ],[
                'first_name.required' => 'The first name field is required',
                'last_name.required' => 'The last name field is required',
                'country_id.required' => 'The country field is required',
                'dob.required' => 'The date of birth field is required',
                'is_married.required' => 'The is married field is required',
                'mobile_country_id.required' => 'The mobile country id field is required',
                'mobile.required' => 'The mobile field is required',
                'email.required' => 'The email field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('Lead')::create([
                    'lead_url' => $req->lead_url,
                    'first_name' => $req->first_name,
                    'last_name' => $req->last_name,
                    'full_name' => "$req->first_name $req->last_name",
                    'nick_name' => $req->nick_name,
                    'country_id' => $req->country_id,
                    'dob' => $req->dob ? new Carbon($req->dob) : NULL,
                    'enlistment_date' => $req->enlistment_date ? new Carbon($req->dob) : NULL,
                    'is_married' => $req->is_married == 'true' ? 1 : 0,
                    'active' => $req->active == 'true' ? 1 : 0,
                    'mobile_country_id' => $req->mobile_country_id,
                    'mobile' => $req->mobile,
                    'alternative_mobile' => $req->alternative_mobile,
                    'email' => $req->email,
                    'other_email' => $req->other_email,
                    'present_address' => $req->present_address,
                    'permanent_address' => $req->permanent_address,
                    'per_city' => $req->per_city,
                    'pre_city' => $req->pre_city,
                    'per_post_code' => $req->per_post_code,
                    'pre_post_code' => $req->pre_post_code,
                    'supervisor_id' => $req->supervisor_id,
                    'description' => $req->description,
                    'slug' => $req->slug,
                    'source' => $req->source,
                    'is_client' => $req->is_client == 'true' ? 1 : 0,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_lead_data') {

            $validate = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'country_id' => 'required',
                'dob' => 'required',
                'is_married' => 'required',
                'mobile_country_id' => 'required',
                'mobile' => 'required',
                'email' => 'required',
            ],[
                'first_name.required' => 'The first name field is required',
                'last_name.required' => 'The last name field is required',
                'country_id.required' => 'The country field is required',
                'dob.required' => 'The date of birth field is required',
                'is_married.required' => 'The is married field is required',
                'mobile_country_id.required' => 'The mobile country id field is required',
                'mobile.required' => 'The mobile field is required',
                'email.required' => 'The email field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Lead = model('Lead')::find($req->id);

                $Lead->update([
                    'lead_url' => $req->lead_url,
                    'first_name' => $req->first_name,
                    'last_name' => $req->last_name,
                    'full_name' => "$req->first_name $req->last_name",
                    'nick_name' => $req->nick_name,
                    'country_id' => $req->country_id,
                    'dob' => $req->dob ? new Carbon($req->dob) : NULL,
                    'enlistment_date' => $req->enlistment_date ? new Carbon($req->dob) : NULL,
                    'is_married' => $req->is_married == 'true' ? 1 : 0,
                    'active' => $req->active == 'true' ? 1 : 0,
                    'mobile_country_id' => $req->mobile_country_id,
                    'mobile' => $req->mobile,
                    'alternative_mobile' => $req->alternative_mobile,
                    'email' => $req->email,
                    'other_email' => $req->other_email,
                    'present_address' => $req->present_address,
                    'permanent_address' => $req->permanent_address,
                    'per_city' => $req->per_city,
                    'pre_city' => $req->pre_city,
                    'per_post_code' => $req->per_post_code,
                    'pre_post_code' => $req->pre_post_code,
                    'supervisor_id' => $req->supervisor_id,
                    'description' => $req->description,
                    'slug' => $req->slug,
                    'source' => $req->source,
                    'is_client' => $req->is_client == 'true' ? 1 : 0,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $Lead
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_lead_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Lead = model('Lead')::find($req->id);

                $Lead->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $Lead
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'toggle_lead_active_status') {

            $Lead = model('Lead')::find($req->id);
            $Lead->active = $req->active == 'true' ? 1 : 0;
            $Lead->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $Lead
            ], 200);

        } else if ($name == 'store_lead_dependent_info_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'name' => 'required',
                'type' => 'required',
                'email' => 'required',
                'mobile_country_id' => 'required',
                'mobile' => 'required|numaric',
            ],[
                'lead_id.required' => 'The lead id is required',
                'name.required' => 'The name field is required',
                'type.required' => 'The type field is required',
                'email.required' => 'The email field is required',
                'mobile_country_id.required' => 'The mobile country id field is required',
                'mobile.required' => 'The mobile field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadDependent')::create([
                    'lead_id' => $req->lead_id,
                    'name' => $req->name,
                    'type' => $req->type,
                    'email' => $req->email,
                    'mobile_country_id' => $req->mobile_country_id,
                    'mobile' => $req->mobile,
                    'alt_mobile' => $req->alt_mobile,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_lead_dependent_info_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'name' => 'required',
                'type' => 'required',
                'email' => 'required',
                'mobile_country_id' => 'required',
                'mobile' => 'required|numaric',
            ],[
                'lead_id.required' => 'The lead id is required',
                'name.required' => 'The name field is required',
                'type.required' => 'The type field is required',
                'email.required' => 'The email field is required',
                'mobile_country_id.required' => 'The mobile country id field is required',
                'mobile.required' => 'The mobile field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LeadDependent = model('LeadDependent')::find($req->id);

                $LeadDependent->update([
                    'lead_id' => $req->lead_id,
                    'name' => $req->name,
                    'type' => $req->type,
                    'email' => $req->email,
                    'mobile_country_id' => $req->mobile_country_id,
                    'mobile' => $req->mobile,
                    'alt_mobile' => $req->alt_mobile,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $LeadDependent
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_lead_dependent_info_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LeadDependent = model('LeadDependent')::find($req->id);

                $LeadDependent->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $LeadDependent
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_lead_social_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'social_id' => 'required',
                'social_url' => 'required|url',
            ],[
                'lead_id.required' => 'The lead id is required',
                'social_id.required' => 'The social id field is required',
                'social_url.required' => 'The social url field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadSocial')::create([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'social_id' => $req->social_id,
                    'social_url' => $req->social_url,
                    // 'active' => $req->active == 'true' ? 1 : 0,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_lead_social_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'social_id' => 'required',
                'social_url' => 'required|url',
            ],[
                'lead_id.required' => 'The lead id is required',
                'social_id.required' => 'The social id field is required',
                'social_url.required' => 'The social url field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LeadSocial = model('LeadSocial')::find($req->id);

                $LeadSocial->update([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'social_id' => $req->social_id,
                    'social_url' => $req->social_url,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $LeadSocial
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_lead_social_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LeadSocial = model('LeadSocial')::find($req->id);

                $LeadSocial->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $LeadSocial
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'toggle_lead_social_active_status') {

            $LeadSocial = model('LeadSocial')::find($req->id);
            $LeadSocial->active = $req->active == 'true' ? 1 : 0;
            $LeadSocial->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $LeadSocial
            ], 200);

        } else if ($name == 'store_lead_contact_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'contact_time' => 'required',
                'contact_preference' => 'required',
                'note' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'contact_time.required' => 'The contact time field is required',
                'contact_preference.required' => 'The contact preference field is required',
                'note.required' => 'The note field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadContact')::create([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'contact_time' => $req->contact_time,
                    'contact_preference' => $req->contact_preference,
                    'note' => $req->note,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_lead_contact_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'contact_time' => 'required',
                'contact_preference' => 'required',
                'note' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'contact_time.required' => 'The contact time field is required',
                'contact_preference.required' => 'The contact preference field is required',
                'note.required' => 'The note field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LeadContact = model('LeadContact')::find($req->id);

                $LeadContact->update([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'contact_time' => $req->contact_time,
                    'contact_preference' => $req->contact_preference,
                    'note' => $req->note,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $LeadContact
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'delete_lead_contact_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LeadContact = model('LeadContact')::find($req->id);

                $LeadContact->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $LeadContact
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'store_lead_emergency_contact_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'name' => 'required',
                'address' => 'required',
                // 'note' => 'nullable',
                // 'dependent_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'name.required' => 'The Name field is required',
                'address.required' => 'The Address field is required'
                // 'dependent_id.required' => 'The Dependent field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadEmergencyContact')::create([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'name' => $req->name,
                    'mobile' => $req->mobile,
                    'mobile_country_id' => $req->mobile_country_id,
                    'address' => $req->address,
                    'email' => $req->email,
                    'relation' => $req->relation,
                    'note' => $req->note,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_lead_emergency_contact_data') {

           $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'name' => 'required',
                'address' => 'required',
                // 'dependent_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'name.required' => 'The Name field is required',
                'address.required' => 'The Address field is required',
                // 'dependent_id.required' => 'The Dependent field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LeadEmergencyContact = model('LeadEmergencyContact')::find($req->id);

                $LeadEmergencyContact->update([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'name' => $req->name,
                    'mobile' => $req->mobile,
                    'mobile_country_id' => $req->mobile_country_id,
                    'address' => $req->address,
                    'email' => $req->email,
                    'relation' => $req->relation,
                    'note' => $req->note,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $LeadEmergencyContact
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_lead_emergency_contact_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $deleteEmrContact = model('LeadEmergencyContact')::find($req->id);

                $deleteEmrContact->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $deleteEmrContact
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_lead_employment_history_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'organization_name' => 'required',
                'start_date' => 'required',
                // 'end_date' => 'required',
                // 'dependent_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'organization_name.required' => 'The Organization Name field is required',
                'start_date.required' => 'The Start Date field is required',
                // 'end_date.required' => 'The End Date field is required'
                // 'dependent_id.required' => 'The Dependent field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $still_working = 0;
                $end_date = NULL;

                if ($req->end_date) {
                    $still_working = 0;
                } else {
                    $still_working = 1;
                }

                if ($req->end_date) {
                    if (!$still_working) {
                        $end_date = new Carbon($req->end_date);
                    }
                }

                $model = model('LeadEmploymentHistory')::create([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'organization_name' => $req->organization_name,
                    'designation' => $req->designation,
                    'start_date' => $req->start_date ? new Carbon($req->start_date) : NULL,
                    'end_date' => $end_date,
                    // 'still_working' => $req->still_working === 'true' ? 1 : 0,
                    'still_working' => $still_working,
                    'location' => $req->location,
                    'is_foreign' => $req->is_foreign === 'true' ? 1 : 0,
                    'is_primary' => $req->is_primary === 'true' ? 1 : 0,
                    'country_id' => $req->country_id,
                    'responsibility' => $req->responsibility,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_status_group_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required',
            ],[
                'name.required' => 'The name field is required',
                'code.required' => 'The code field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('StatusGroup')::create([
                    'name' => $req->name,
                    'code' => $req->code,
                    'active' => $req->active == 'true' ? 1 : 0,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'update_status_group_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required',
            ],[
                'name.required' => 'The name field is required',
                'code.required' => 'The code field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $StatusGroup = model('StatusGroup')::find($req->id);

                $StatusGroup->update([
                    'name' => $req->name,
                    'code' => $req->code,
                    'active' => $req->active == 'true' ? 1 : 0,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $StatusGroup
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_lead_employement_history_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $deleteEmployementHistory = model('LeadEmploymentHistory')::find($req->id);

                $deleteEmployementHistory->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $deleteEmployementHistory
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_status_group_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $StatusGroup = model('StatusGroup')::find($req->id);
                $StatusGroup->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $StatusGroup
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'toggle_status_group_active_status') {

            $StatusGroup = model('StatusGroup')::find($req->id);
            $StatusGroup->active = $req->active == 'true' ? 1 : 0;
            $StatusGroup->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $StatusGroup
            ], 200);

        } else if ($name == 'store_status_data') {

            $validate = Validator::make($request->all(), [
                'status_group_id' => 'required',
                'name' => 'required',
                'code' => 'required',
            ],[
                'status_group_id.required' => 'The status group id is required',
                'name.required' => 'The name field is required',
                'code.required' => 'The code field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('Status')::create([
                    'status_group_id' => $req->status_group_id,
                    'name' => $req->name,
                    'code' => $req->code,
                    'serial' => $req->serial,
                    'color_name' => $req->color_name,
                    'active' => $req->active == 'true' ? 1 : 0,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'update_status_data') {

            $validate = Validator::make($request->all(), [
                'status_group_id' => 'required',
                'name' => 'required',
                'code' => 'required',
            ],[
                'status_group_id.required' => 'The status group id is required',
                'name.required' => 'The name field is required',
                'code.required' => 'The code field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Status = model('Status')::find($req->id);

                $Status->update([
                    'status_group_id' => $req->status_group_id,
                    'name' => $req->name,
                    'code' => $req->code,
                    'serial' => $req->serial,
                    'color_name' => $req->color_name,
                    'active' => $req->active == 'true' ? 1 : 0,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $Status
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'delete_status_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Status = model('Status')::find($req->id);
                $Status->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $Status
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'toggle_status_active_status') {

            $Status = model('Status')::find($req->id);
            $Status->active = $req->active == 'true' ? 1 : 0;
            $Status->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $Status
            ], 200);

        } else if ($name == 'update_lead_employment_history_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'organization_name' => 'required',
                'start_date' => 'required',
                // 'end_date' => 'required',
                // 'dependent_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'organization_name.required' => 'The Organization Name field is required',
                'start_date.required' => 'The Start Date field is required',
                // 'end_date.required' => 'The End Date field is required',
                // 'dependent_id.required' => 'The Dependent field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LeadEmploymentHistory = model('LeadEmploymentHistory')::find($req->id);

                $still_working = 0;
                $end_date = NULL;

                if ($req->still_working === 'true') {
                    $still_working = 1;
                } else {
                    if ($req->end_date) {
                        $still_working = 0;
                    } else {
                        $still_working = 1;
                    }
                }

                if ($req->end_date) {
                    if (!$still_working) {
                        $end_date = new Carbon($req->end_date);
                    }
                }

                $LeadEmploymentHistory->update([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'organization_name' => $req->organization_name,
                    'designation' => $req->designation,
                    'start_date' => $req->start_date ? new Carbon($req->start_date) : NULL,
                    'end_date' => $end_date,
                    'still_working' => $still_working,
                    'location' => $req->location,
                    'is_foreign' => $req->is_foreign === 'true' ? 1 : 0,
                    'is_primary' => $req->is_primary === 'true' ? 1 : 0,
                    'country_id' => $req->country_id,
                    'responsibility' => $req->responsibility,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $LeadEmploymentHistory
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'course_level_serial_up') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $CourseLevel = model('CourseLevel')::find($req->id);

                if ($CourseLevel->serial == 1) {
                    $firstSerialCourseLevel = model('CourseLevel')::where('serial', 1)->where('id', '!=', $CourseLevel->id)->first();
                    if ($firstSerialCourseLevel) {

                        $firstSerialCourseLevel->serial = $firstSerialCourseLevel->serial + 1;
                        $firstSerialCourseLevel->save();

                        return response()->json([
                            'success' => true,
                            'message' => 'Course level serial up',
                        ], 200);

                    } else {

                        return response()->json([
                            'success' => false,
                            'message' => 'This course level already in top serial',
                        ], 422);
                    }
                } else {

                    $CourseLevel->serial = $CourseLevel->serial - 1;
                    $CourseLevel->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Course level serial up',
                    ], 200);
                }

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'course_level_serial_down') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $CourseLevel = model('CourseLevel')::find($req->id);

                $CourseLevel->serial = $CourseLevel->serial + 1;
                $CourseLevel->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Course level serial down',
                    'data'  => $CourseLevel
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'channel_serial_up') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Channel = model('Channel')::find($req->id);

                if ($Channel->serial == 1) {
                    $firstSerialChannel = model('Channel')::where('serial', 1)->where('id', '!=', $Channel->id)->first();
                    if ($firstSerialChannel) {

                        $firstSerialChannel->serial = $firstSerialChannel->serial + 1;
                        $firstSerialChannel->save();

                        return response()->json([
                            'success' => true,
                            'message' => 'Channel serial up',
                        ], 200);

                    } else {

                        return response()->json([
                            'success' => false,
                            'message' => 'This Channel already in top serial',
                        ], 422);
                    }
                } else {

                    $Channel->serial = $Channel->serial - 1;
                    $Channel->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Channel serial up',
                    ], 200);
                }

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'channel_serial_down') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Channel = model('Channel')::find($req->id);

                $Channel->serial = $Channel->serial + 1;
                $Channel->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Channel serial down',
                    'data'  => $Channel
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'status_serial_up') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Status = model('Status')::find($req->id);

                if ($Status->serial == 1) {
                    $firstSerialStatus = model('Status')::where('serial', 1)->where('id', '!=', $Status->id)->first();
                    if ($firstSerialStatus) {

                        $firstSerialStatus->serial = $firstSerialStatus->serial + 1;
                        $firstSerialStatus->save();

                        return response()->json([
                            'success' => true,
                            'message' => 'Status serial up',
                        ], 200);

                    } else {

                        return response()->json([
                            'success' => false,
                            'message' => 'This Status already in top serial',
                        ], 422);
                    }
                } else {

                    $Status->serial = $Status->serial - 1;
                    $Status->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Status serial up',
                    ], 200);
                }

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'status_serial_down') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $Status = model('Status')::find($req->id);

                $Status->serial = $Status->serial + 1;
                $Status->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Status serial down',
                    'data'  => $Status
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_lead_visa_histories_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'visa_type_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'rejection_date' => 'nullable',
                // 'dependent_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'visa_type_id.required' => 'The Organization Name field is required',
                'start_date.required' => 'The Start Date field is required',
                'end_date.required' => 'The End Date field is required'
                // 'dependent_id.required' => 'The Dependent field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadVisaHistory')::create([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'visa_type_id' => $req->visa_type_id,
                    'start_date' => $req->start_date ? new Carbon($req->start_date) : NULL,
                    'end_date' => $req->end_date ? new Carbon($req->end_date) : NULL,
                    'rejection_date' => $req->rejection_date ? new Carbon($req->rejection_date) : NULL,
                    'rejection_reason' => $req->rejection_reason,
                    'country_id' => $req->country_id,
                    'purpose' => $req->purpose,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                if ($req->file instanceof \Illuminate\Http\UploadedFile) {

                    $media = upload_media($req->file, [
                        'model' => get_class($model),
                        'model_id' => $model->id,
                    ]);

                    if (isset($media)) {
                        $model->media_id = $media ? $media->id : NULL;
                        $model->save();
                    }
                }

                return response()->json([
                    'success' => true,
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_lead_service_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'country_id' => 'required',
                'service_id' => 'required',
                'service_status_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'country_id.required' => 'The country id is required',
                'service_id.required' => 'The service id is required',
                'service_status_id.required' => 'The service status id is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadService')::create([
                    'lead_id' => $req->lead_id,
                    'country_id' => $req->country_id,
                    'service_id' => $req->service_id,
                    'service_status_id' => $req->service_status_id,
                    'active' => $req->active == 'true' ? 1 : 0,
                ]);

                return response()->json([
                    'success' => true,
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_lead_visa_histories_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'visa_type_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'rejection_date' => 'nullable',
                'dependent_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'visa_type_id.required' => 'The Organization Name field is required',
                'dependent_id.required' => 'The Dependent field is required',
                'start_date.required' => 'The Start Date field is required',
                'end_date.required' => 'The End Date field is required'
            ]);
                if ($validate->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => $validate->errors()
                    ], 422);
                }

                try {

                    $LeadVisaHistoryUpdate = model('LeadVisaHistory')::find($req->id);

                    $LeadVisaHistoryUpdate->update([
                        'lead_id' => $req->lead_id,
                        'dependent_id' => $req->dependent_id,
                        'visa_type_id' => $req->visa_type_id,
                        'start_date' => $req->start_date ? new Carbon($req->start_date) : NULL,
                        'end_date' => $req->end_date ? new Carbon($req->end_date) : NULL,
                        'rejection_date' => $req->rejection_date ? new Carbon($req->rejection_date) : NULL,
                        'rejection_reason' => $req->rejection_reason,
                        'country_id' => $req->country_id,
                        'purpose' => $req->purpose,
                        'updated_at' => Carbon::now(),
                    ]);

                    if ($req->file instanceof \Illuminate\Http\UploadedFile) {

                        if ($LeadVisaHistoryUpdate->media_id) {
                            delete_media($LeadVisaHistoryUpdate->media_id);
                        }

                        $media = upload_media($req->file, [
                            'model' => get_class($LeadVisaHistoryUpdate),
                            'model_id' => $LeadVisaHistoryUpdate->id,
                        ]);

                        if (isset($media)) {
                            $LeadVisaHistoryUpdate->media_id = $media ? $media->id : NULL;
                            $LeadVisaHistoryUpdate->save();
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Data updated successfully',
                        'data'  => $LeadVisaHistoryUpdate
                    ], 200);

                } catch (\Throwable $th) {
                    return response()->json([
                        'success' => false,
                        'message' => $th->getMessage()
                    ], 500);
                }

        } else if ($name == 'update_lead_service_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'country_id' => 'required',
                'service_id' => 'required',
                'service_status_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'country_id.required' => 'The country id is required',
                'service_id.required' => 'The service id is required',
                'service_status_id.required' => 'The service status id is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {
                $LeadService = model('LeadService')::find($req->id);

                model('LeadServiceHistory')::create([
                    'lead_id' => $LeadService->lead_id,
                    'lead_service_id' => $LeadService->id,
                    'country_id' => $LeadService->country_id,
                    'service_id' => $LeadService->service_id,
                    'service_status_id' => $LeadService->service_status_id,
                    'creator_id' => $user->id,
                ]);

                $LeadService->update([
                    'country_id' => $req->country_id,
                    'service_id' => $req->service_id,
                    'service_status_id' => $req->service_status_id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $LeadService
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_lead_service_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $LeadService = model('LeadService')::find($req->id);

                model('LeadServiceHistory')::where([
                    'lead_service_id' => $LeadService->id,
                    'service_id' => $LeadService->lead_id,
                ])->delete();
                $LeadService->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'toggle_lead_service_active_status') {

            $LeadService = model('LeadService')::find($req->id);
            $LeadService->active = $req->active == 'true' ? 1 : 0;
            $LeadService->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $LeadService
            ], 200);

        } else if ($name == 'delete_lead_visa_history_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $leadVisaDelete = model('LeadVisaHistory')::find($req->id);
                $leadVisaDelete->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $leadVisaDelete
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_education_history_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'institute_country_id' => 'required',
                'institute_name' => 'required',
                'course_level_id' => 'required',
                // 'dependent_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'institute_country_id.required' => 'The institute field is required',
                'institute_name.required' => 'The institute name field is required',
                'course_level_id.required' => 'The Course level field is required'
                // 'dependent_id.required' => 'The Dependent field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadEducationHistory')::create([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'institute_country_id' => $req->institute_country_id,
                    'institute_name' => $req->institute_name,
                    'is_foreign_institute' => $req->is_foreign_institute === 'true' ? 1 : 0,
                    'course_level_id' => $req->course_level_id,
                    'course_name' => $req->course_name,
                    'result_type_id' => $req->result_type_id,
                    'marks' => $req->marks,
                    'grade' => $req->grade,
                    'grade_scale' => $req->grade_scale,
                    'year_of_passing' => $req->year_of_passing,
                    'duration' => $req->duration,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_education_history_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'dependent_id' => 'required',
                'institute_country_id' => 'required',
                'institute_name' => 'required',
                'course_level_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'dependent_id.required' => 'The Dependent field is required',
                'institute_country_id.required' => 'The institute field is required',
                'institute_name.required' => 'The institute name field is required',
                'course_level_id.required' => 'The Course level field is required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $leadEducationHisUpdate = model('LeadEducationHistory')::find($req->id);

                $leadEducationHisUpdate->update([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'institute_country_id' => $req->institute_country_id,
                    'institute_name' => $req->institute_name,
                    'is_foreign_institute' => $req->is_foreign_institute === 'true' ? 1 : 0,
                    'course_level_id' => $req->course_level_id,
                    'course_name' => $req->course_name,
                    'result_type_id' => $req->result_type_id,
                    'marks' => $req->marks,
                    'grade' => $req->grade,
                    'grade_scale' => $req->grade_scale,
                    'year_of_passing' => $req->year_of_passing,
                    'duration' => $req->duration,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $leadEducationHisUpdate
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }


        } else if ($name == 'delete_education_history_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $leadEducationHisDelete = model('LeadEducationHistory')::find($req->id);
                $leadEducationHisDelete->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $leadEducationHisDelete
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_question_point_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'service_id' => 'required',
                'question_id' => 'required',
                'qp_breakdown_id' => 'required',
                'point' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'service_id.required' => 'The Service field is required',
                'question_id.required' => 'The Question field is required',
                'qp_breakdown_id.required' => 'The Question Point BreakDowns field is required',
                'point.required' => 'The Point field is required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadQuestionPoint')::create([
                    'lead_id' => $req->lead_id,
                    'service_id' => $req->service_id,
                    'question_id' => $req->question_id,
                    'institute_name' => $req->institute_name,
                    'qp_breakdown_id' => $req->qp_breakdown_id,
                    'point' => $req->point,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_question_point_data_v2') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'service_id' => 'required',
                'questions' => 'array',
                'questions.*.id' => 'required',
                'questions.*.qp_breakdown_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'service_id.required' => 'The Service field is required',
                'questions.*.id.required' => 'The question id is missing',
                'questions.*.qp_breakdown_id.required' => 'Please answer the all question',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $total_score = 0;

                foreach($req->questions as $question) {

                    $qpBreakdown = model('QuestionPointBreakdown')::where([
                        'id' => (int) $question['qp_breakdown_id']
                    ])->first();

                    model('LeadQuestionPoint')::create([
                        'lead_id' => $req->lead_id,
                        'service_id' => $req->service_id,
                        'question_id' => $qpBreakdown->question_id,
                        'qp_breakdown_id' => $qpBreakdown->id,
                        'point' => $qpBreakdown->point,
                        'creator_id' => $user->id,
                        'created_at' => Carbon::now(),
                    ]);

                    $total_score += $qpBreakdown->point;

                }

                model('LeadService')::where('id', $req->lead_service_id)
                ->update([
                    'total_score' => $total_score,
                    'point_entry_date' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    // 'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_question_point_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'service_id' => 'required',
                'question_id' => 'required',
                'qp_breakdown_id' => 'required',
                'point' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'service_id.required' => 'The Service field is required',
                'question_id.required' => 'The Question field is required',
                'qp_breakdown_id.required' => 'The Question Point BreakDowns field is required',
                'point.required' => 'The Point field is required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $questionPointUpdate = model('LeadQuestionPoint')::find($req->id);

                $questionPointUpdate->update([
                    'lead_id' => $req->lead_id,
                    'service_id' => $req->service_id,
                    'question_id' => $req->question_id,
                    'institute_name' => $req->institute_name,
                    'qp_breakdown_id' => $req->qp_breakdown_id,
                    'point' => $req->point,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Update Successfully',
                    'data'  => $questionPointUpdate
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_question_point_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $leadQuestionPointDelete = model('LeadQuestionPoint')::find($req->id);
                $leadQuestionPointDelete->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $leadQuestionPointDelete
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_lead_english_test_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'language_test_id' => 'required',
                'level_id' => 'required'
                // 'dependent_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'language_test_id.required' => 'The Language Test field is required',
                'level_id.required' => 'The Level field is required'
                // 'dependent_id.required' => 'The Dependent field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadEnglishTest')::create([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'language_test_id' => $req->language_test_id,
                    'level_id' => $req->level_id,
                    'is_primary' => $req->is_primary === 'true' ? 1 : 0,
                    'test_date' => $req->test_date ? new Carbon($req->test_date) : NULL,
                    'expire_date' => $req->expire_date ? new Carbon($req->expire_date) : NULL,
                    'over_all_result' => $req->over_all_result,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_lead_english_test_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'language_test_id' => 'required',
                'level_id' => 'required'
                // 'dependent_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'language_test_id.required' => 'The Language Test field is required',
                'level_id.required' => 'The Level field is required'
                // 'dependent_id.required' => 'The Dependent field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $updateEnligshTest = model('LeadEnglishTest')::find($req->id);

                $updateEnligshTest->update([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'language_test_id' => $req->language_test_id,
                    'level_id' => $req->level_id,
                    'is_primary' => $req->is_primary === 'true' ? 1 : 0,
                    'test_date' => $req->test_date ? new Carbon($req->test_date) : NULL,
                    'expire_date' => $req->expire_date ? new Carbon($req->expire_date) : NULL,
                    'over_all_result' => $req->over_all_result,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Updated Successfully',
                    'data'  => $updateEnligshTest
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_english_test_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $leadTestDelete = model('LeadEnglishTest')::find($req->id);
                $leadTestDelete->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $leadTestDelete
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_lead_english_test_result_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'lead_english_test_id' => 'required',
                'child_language_test_id' => 'required'
                // 'dependent_id' => 'required',
            ],[
                'lead_id.required' => 'The lead id is required',
                'lead_english_test_id.required' => 'The Lead English Test field is required',
                'child_language_test_id.required' => 'The Child Language field is required'
                // 'dependent_id.required' => 'The Dependent field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadEnglishTestResult')::create([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'lead_english_test_id' => $req->lead_english_test_id,
                    'child_language_test_id' => $req->child_language_test_id,
                    'result' => $req->result,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

        } else if ($name == 'update_lead_english_test_result_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                // 'dependent_id' => 'required',
                'lead_english_test_id' => 'required',
                'child_language_test_id' => 'required'
            ],[
                'lead_id.required' => 'The lead id is required',
                // 'dependent_id.required' => 'The Dependent field is required',
                'lead_english_test_id.required' => 'The Lead English Test field is required',
                'child_language_test_id.required' => 'The Child Language field is required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $updateEnglishTest =  model('LeadEnglishTestResult')::find($req->id);

                $updateEnglishTest->update([
                    'lead_id' => $req->lead_id,
                    'dependent_id' => $req->dependent_id,
                    'lead_english_test_id' => $req->lead_english_test_id,
                    'child_language_test_id' => $req->child_language_test_id,
                    'result' => $req->result,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Updated Successfully',
                    'data'  => $updateEnglishTest
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_lead_english_test_result_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $deleteTestResult = model('LeadEnglishTestResult')::find($req->id);
                $deleteTestResult->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $deleteTestResult
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_lead_payment_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'stage_no' => 'required',
                'sl_no' => 'required',
                'amount' => 'required'
            ],[
                'lead_id.required' => 'The lead id is required',
                'stage_no.required' => 'The Stage No field is required',
                'sl_no.required' => 'The Serial field is required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('LeadPayment')::create([
                    'lead_id' => $req->lead_id,
                    'stage_no' => $req->stage_no,
                    'sl_no' => $req->sl_no,
                    'amount' => $req->amount,
                    'remarks' => $req->remarks,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_lead_payment_data') {

            $validate = Validator::make($request->all(), [
                'lead_id' => 'required',
                'stage_no' => 'required',
                'sl_no' => 'required',
                'amount' => 'required'
            ],[
                'lead_id.required' => 'The lead id is required',
                'stage_no.required' => 'The Stage No field is required',
                'sl_no.required' => 'The Serial field is required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $updateLeadPayment = model('LeadPayment')::find($req->id);

                $updateLeadPayment->update([
                    'lead_id' => $req->lead_id,
                    'stage_no' => $req->stage_no,
                    'sl_no' => $req->sl_no,
                    'amount' => $req->amount,
                    'remarks' => $req->remarks,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Updated Successfully',
                    'data'  => $updateLeadPayment
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_lead_payment_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $deleteLeadPayment = model('LeadPayment')::find($req->id);
                $deleteLeadPayment->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $deleteLeadPayment
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'lead_payment_serial_up') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $leadPayment = model('LeadPayment')::find($req->id);

                if ($leadPayment->sl_no == 1) {
                    $firstSerialPayment = model('LeadPayment')::where('sl_no', 1)->where('id', '!=', $leadPayment->id)->first();
                    if ($firstSerialPayment) {

                        $firstSerialPayment->sl_no = $firstSerialPayment->sl_no + 1;
                        $firstSerialPayment->save();

                        return response()->json([
                            'success' => true,
                            'message' => 'Lead Payment serial up',
                        ], 200);

                    } else {

                        return response()->json([
                            'success' => false,
                            'message' => 'This Lead Payment  already in top serial',
                        ], 422);
                    }
                } else {

                    $leadPayment->sl_no = $leadPayment->sl_no - 1;
                    $leadPayment->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Lead Payment serial up',
                    ], 200);
                }

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'lead_payment_serial_down') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $leadPayment = model('LeadPayment')::find($req->id);

                $leadPayment->sl_no = $leadPayment->sl_no + 1;
                $leadPayment->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Course level serial down',
                    'data'  => $leadPayment
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'store_service_status_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'country_id' => 'required',
                'service_id' => 'required',
                'code' => 'required',
                'color_code' => 'required',
            ],[
                'name.required' => 'The name field is required',
                'country_id.required' => 'The country field is required',
                'service_id.required' => 'The service field is required',
                'code.required' => 'The code field is required',
                'color_code.required' => 'The color code field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $model = model('ServiceStatus')::create([
                    'country_id' => $req->country_id,
                    'service_id' => $req->service_id,
                    'name' => $req->name,
                    'code' => $req->code,
                    'color_code' => $req->color_code,
                    'serial' => $req->serial,
                    'active' => 1,
                    'creator_id' => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Created Successfully',
                    'data'  => $model
                ], 201);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'update_service_status_data') {

            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'country_id' => 'required',
                'service_id' => 'required',
                'code' => 'required',
                'color_code' => 'required',
            ],[
                'name.required' => 'The name field is required',
                'country_id.required' => 'The country field is required',
                'service_id.required' => 'The service field is required',
                'code.required' => 'The code field is required',
                'color_code.required' => 'The color code field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $ServiceStatus = model('ServiceStatus')::find($req->id);

                $ServiceStatus->update([
                    'country_id' => $req->country_id,
                    'service_id' => $req->service_id,
                    'name' => $req->name,
                    'code' => $req->code,
                    'color_code' => $req->color_code,
                    'serial' => $req->serial,
                    // 'active' => $req->active == 'true' ? 1 : 0,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data updated successfully',
                    'data'  => $ServiceStatus
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'delete_service_status_data') {

            $validate = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            try {

                $ServiceStatus = model('ServiceStatus')::find($req->id);

                $ServiceStatus->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data deleted successfully',
                    'data'  => $ServiceStatus
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else if ($name == 'toggle_service_status_active_status') {

            $ServiceStatus = model('ServiceStatus')::find($req->id);
            $ServiceStatus->active = $req->active == 'true' ? 1 : 0;
            $ServiceStatus->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully!',
                'data'    => $ServiceStatus
            ], 200);

        }

        return response(['msg' => 'Sorry!, found no named argument.'], 403);
    }
}
