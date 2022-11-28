<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Session;
use App\Models\School;
use App\Models\Addon;
use App\Models\Subscription;
use App\Models\Exam;
use App\Models\ExamCategory;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Gradebook;
use App\Models\Grade;
use App\Models\Department;
use App\Models\ClassRoom;
use App\Models\ClassList;
use App\Models\Section;
use App\Models\Enrollment;
use App\Models\DailyAttendances;
use App\Models\Routine;
use App\Models\Syllabus;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\StudentFeeManager;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Noticeboard;
use App\Models\Package;
use App\Models\PaymentHistory;
use App\Models\GlobalSettings;
use App\Models\Currency;
use App\Models\PaymentMethods;
use App\Models\Discount;
use App\Models\DiscountBranch;
use App\Models\DiscountCourse;
use App\Models\DiscountLevel;
use App\Models\Session as EnrollmentYear;

use Illuminate\Support\Facades\Redirect;

use  Omnipay\Omnipay;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use Stripe;
use PaytmWallet;


class SuperAdminController extends Controller
{
    /**
     * Show the superadmin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    private $publicly_user_id;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->id = Auth::user()->id;
            $this->publicly_user_id = $this->id;
            $this->school_id = Auth::user()->school_id;

    
            return $next($request);
        });
    }


    public function superadminDashboard()
    {
        return view('superadmin.dashboard');
    }

    /**
     * Show the school list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function schoolList()
    {
        $schools = School::where('status', 1)
            ->orwhere('status', 0)
            ->get();
        return view('superadmin.school.list', ['schools' => $schools]);
    }

    public function editSchool($id)
    {
        $school = School::find($id);
        return view('superadmin.school.edit_school', ['school' => $school]);
    }

    public function schoolUpdate(Request $request, $id)
    {
        $data = $request->all();

        unset($data['_token']);

        School::where('id', $id)->update($data);

        return redirect()->back()->with('message', 'You have successfully update school.');
    }

    public function schoolDelete($id)
    {
        $school = School::find($id);
        $school->delete();
        return redirect()->back()->with('message', 'You have successfully delete a school.');
    }

    public function schoolAdd()
    {
        return view('superadmin.school.add_school');
    }

    public function createSchool(Request $request)
    {
        $data = $request->all();

        $school = School::create([
            'title' => $data['school_name'],
            'email' => $data['school_email'],
            'phone' => $data['school_phone'],
            'address' => $data['school_address'],
            'school_info' => $data['school_info'],
            'school_code' => $data['school_code'],
            'postal_code' => $data['postal_code'],
            'status' => $data['status']
        ]);

        // if (isset($school->id) && $school->id != "") {
        //     if (!empty($data['photo'])) {

        //         $imageName = time() . '.' . $data['photo']->extension();

        //         $data['photo']->move(public_path('assets/uploads/user-images/'), $imageName);

        //         $photo  = $imageName;
        //     } else {
        //         $photo = '';
        //     }
        //     $info = array(
        //         'gender' => $data['gender'],
        //         'blood_group' => $data['blood_group'],
        //         'birthday' => isset($data['birthday'])? strtotime($data['birthday']):"",
        //         'phone' => $data['admin_phone'],
        //         'address' => $data['admin_address'],
        //         'photo' => $photo
        //     );
        //     $data['user_information'] = json_encode($info);
        //     User::create([
        //         'name' => $data['admin_name'],
        //         'email' => $data['admin_email'],
        //         'password' => Hash::make($data['admin_password']),
        //         'role_id' => '2',
        //         'school_id' => $school->id,
        //         'user_information' => $data['user_information'],
        //     ]);
        // }


        return to_route('superadmin.school.list');
    }

    public function schoolClassroomList($schoolId){
        
        $class_rooms = ClassRoom::where('school_id', $schoolId)
                        ->get();
        $school= School::find($schoolId);
        return view('superadmin.class_room.class_room_list', ['class_rooms' => $class_rooms, 'schoolId'=>$schoolId, 'school'=>$school]);
    }

    public function editClassRoom($id)
    {
        $class_room = ClassRoom::find($id);
        return view('superadmin.class_room.edit_class_room', ['class_room' => $class_room]);
    }
    
    public function createClassRoom($schoolId)
    {
        return view('superadmin.class_room.add_class_room', ['schoolId'=>$schoolId]) ;
    }
    
    public function classRoomCreate(Request $request)
    {
        $data = $request->all();

        $duplicate_class_room_check = ClassRoom::get()->where('name', $data['name']);

        if(count($duplicate_class_room_check) == 0) {
            ClassRoom::create([
                'name' => $data['name'],
                'code' => $data['code'],
                'capacity' => $data['capacity'],
                'status' => $data['status'],
                'school_id' => $data['school_id'],
            ]);

            return redirect()->back()->with('message','You have successfully create a new class room.');

        } else {
            return back()
            ->with('error','Sorry this class room already exists');
        }
    }

    public function classRoomUpdate(Request $request, $id)
    {
        $data = $request->all();
        $duplicate_class_room_check = ClassRoom::get()->where('name', $data['name'])->where('id' , '<>', $id);
        if(count($duplicate_class_room_check) == 0) {
            ClassRoom::where('id', $id)->update([
                'name' => $data['name'],
                'code' => $data['code'],
                'capacity' => $data['capacity'],
                'status' => $data['status'],
            ]);
            
            return redirect()->back()->with('message','You have successfully update class room.');
        } else {
            return back()
            ->with('error','Sorry this class room already exists');
        }
    }

    public function classRoomDelete($id)
    {
        $department = ClassRoom::find($id);
        $department->delete();
        return redirect()->back()->with('message','You have successfully delete class room.');
    }


    public function classList()
    {
        //$class_lists = Classes::get()->where('school_id', auth()->user()->school_id);
        $class_lists = Classes::get();
        return view('superadmin.class.class_list', ['class_lists' => $class_lists]);
    }

    public function createClass()
    {
        return view('superadmin.class.add_class');
    }

    public function classCreate(Request $request)
    {
        $data = $request->all();

        $duplicate_class_check = Classes::get()->where('name', $data['name']);

        if(count($duplicate_class_check) == 0) {
            $id = Classes::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'status' => $data['status'],
                'code' => $data['code']
            ])->id;

            // Section::create([
            //     'name' => 'A',
            //     'class_id' => $id,
            // ]);

            return redirect()->back()->with('message','You have successfully create a new class.');

        } else {
            return back()
            ->with('error','Sorry this class already exists');
        }
    }

    public function editClass($id)
    {
        $class = Classes::find($id);
        return view('superadmin.class.edit_class', ['class' => $class]);
    }

    public function classUpdate(Request $request, $id)
    {
        $data = $request->all();

        $duplicate_class_check = Classes::get()->where('name', $data['name'])->where('id' , '<>', $id);

        if(count($duplicate_class_check) == 0) {
            Classes::where('id', $id)->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'status' => $data['status'],
                'code' => $data['code']
            ]);
            
            return redirect()->back()->with('message','You have successfully update class.');
        } else {
            return back()
            ->with('error','Sorry this class already exists');
        }
    }

    public function classDelete($id)
    {
        $class = Classes::find($id);
        $class->delete();
        $sections = Section::get()->where('class_id', $id);
        $sections->map->delete();
        return redirect()->back()->with('message','You have successfully delete class.');
    }


    public function pendingSchool()
    {
        $schools = School::where('status', 2)
            ->get();
        return view('superadmin.school.pending_school', ['schools' => $schools]);
    }

    public function approveSchool($id)
    {
        $school = School::find($id);
        School::where('id', $id)->update([
            'status' => '1',
        ]);
        return redirect()->back()->with('message', 'School request approved.');
    }


    /**
     * Show the subject list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function subjectList(Request $request)
    {
        $classes = Classes::where('status', 'active')->get();

        if(count($request->all()) > 0){

            $data = $request->all();
            $class_id = $data['class_id'];
            if($class_id==""){
                $subjects = Subject::get();
            }else{
                $subjects = Subject::get()->where('class_id', $class_id);
            }
            

        } else {
            $subjects = Subject::get();
            $class_id = '';
        }

        return view('superadmin.subject.subject_list', ['subjects' => $subjects, 'classes' => $classes, 'class_id' => $class_id]);
    }

    public function createSubject()
    {
        $classes = Classes::where('status', 'active')->get();
        $departments = Department::get();
        $schools = School::get();
        $arrEnrollmentTypes=array('F2F','Online', 'Ad-hoc', 'All');
        $arrWeekDays=array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        return view('superadmin.subject.add_subject', ['arrEnrollmentTypes'=>$arrEnrollmentTypes ,'arrWeekDays'=>$arrWeekDays, 'classes' => $classes, 'departments'=>$departments, 'schools'=>$schools]);
    }

    public function subjectCreate(Request $request)
    {
        $data = $request->all();

        //####### Generate Class Code Branchcode_EnrollmentYear_Level_Course+Class#_Day_StartTime
            $school=School::find($data['school_id']);
            $class=Classes::find($data['class_id']);
            $department=Department::find($data['department_id']);
            $classroom=ClassRoom::find($data['class_room_id']);
            $day=implode("-",$data['day']);
            $datetime=date("h:iA", strtotime($data['start_time']));
            $code=$school->school_code."-".$data['enrollment_year']."-".$class->code."-".$department->subject_code."-".$classroom->code."-".$data['class_number']."-".$day."-".$datetime;
        //##############################################
        $duplicate_check = Subject::get()->where('code', $code);

        if(count($duplicate_check) == 0) {
            Subject::create([
                'name' => $code,
                'class_number' => $data['class_number'],//Class Number
                'class_id' => $data['class_id'], //Level
                'stream' => $data['stream'],
                'school_id' => $data['school_id'],//Branch
                'class_room_id' => $data['class_room_id'],
                'f2f_enrollment_size' => $data['f2f_enrollment_size'],
                'online_enrollment_size' => $data['online_enrollment_size'],
                'enrollment_year' => $data['enrollment_year'],
                'remarks' => $data['remarks'],
                'start_date' => $data['start_date'],
                'start_time' => $data['start_time'],
                'end_date' => $data['end_date'],
                'end_time' => $data['end_time'],
                'code' => $code, 
                'status' => $data['status'],
                'department_id'=>$data['department_id'], //Course
                'day'=>json_encode($data['day']),
                'enrollment_type'=>$data['enrollment_type']
            ]);
            
            return redirect('/superadmin/subject?class_id='.$data['class_id'])->with('message','You have successfully created the course.');
        }else {
            return back()
            ->with('error','Sorry this course code already exists');
        }
    }

    public function editSubject($id)
    {
        $classes = Classes::where('status', 'active')->get();
        $departments = Department::get();
        $schools = School::get();

        $subject = Subject::find($id);
        $arrSubjectDays=json_decode($subject->day);
        if(is_null($arrSubjectDays)){
            $arrSubjectDays=array();
        }
        $arrEnrollmentTypes=array('F2F','Online', 'Ad-hoc', 'All');
        $arrWeekDays=array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        $departments = Department::get();
        return view('superadmin.subject.edit_subject', ['arrEnrollmentTypes'=>$arrEnrollmentTypes ,'arrWeekDays'=>$arrWeekDays, 'schools'=>$schools, 'subject' => $subject, 'classes' => $classes, 'departments'=>$departments, 'arrSubjectDays'=> $arrSubjectDays]);
    }

    public function subjectUpdate(Request $request, $id)
    {
        $data = $request->all();
        //####### Generate Class Code Branchcode_EnrollmentYear_Level_Course+Class#_Day_StartTime
        $school=School::find($data['school_id']);
        $class=Classes::find($data['class_id']);
        $department=Department::find($data['department_id']);
        $classroom=ClassRoom::find($data['class_room_id']);
        $day=implode("-",$data['day']);
        $datetime=date("h:iA", strtotime($data['start_time']));
        $code=$school->school_code."-".$data['enrollment_year']."-".$class->code."-".$department->subject_code."-".$classroom->code."-".$data['class_number']."-".$day."-".$datetime;
        //##############################################
       
        $duplicate_check = Subject::get()->where('code', $code)->where('id', '<>', $id );
        if(count($duplicate_check) == 0) {
            Subject::where('id', $id)->update([
                'name' => $code,
                'class_number' => $data['class_number'],//Class Number
                'class_id' => $data['class_id'], //Level
                'stream' => $data['stream'],
                'school_id' => $data['school_id'],//Branch
                'class_room_id' => $data['class_room_id'],
                'f2f_enrollment_size' => $data['f2f_enrollment_size'],
                'online_enrollment_size' => $data['online_enrollment_size'],
                'enrollment_year' => $data['enrollment_year'],
                'remarks' => $data['remarks'],
                'start_date' => $data['start_date'],
                'start_time' => $data['start_time'],
                'end_date' => $data['end_date'],
                'end_time' => $data['end_time'],
                'code' => $code, 
                'status' => $data['status'],
                'department_id'=>$data['department_id'], //Course
                'day'=>json_encode($data['day']),
                'enrollment_type'=>$data['enrollment_type']
            ]);
        
        
            return redirect('/superadmin/subject?class_id='.$data['class_id'])->with('message','You have successfully updated the course.');
        }else {
            return back()
            ->with('error','Sorry this course code already exists');
        }
    }

    public function subjectDelete($id)
    {
        $subject = Subject::find($id);
        $subject->delete();
        $subjects = Subject::get()->where('school_id', auth()->user()->school_id);
        return redirect()->back()->with('message','You have successfully delete course.');
    }

    public function getClassRooms($school_id)
    {
        $classrooms= ClassRoom::get()->where('school_id', $school_id);
        $arrClassrooms=array();
        if(!$classrooms->isEmpty()){
            foreach($classrooms as $classroom){
                $arrClassrooms[]=['id'=>$classroom->id, 'name'=>$classroom->name, 'capacity'=>$classroom->capacity];
            }
        }
       return response()->json($arrClassrooms);
    }
    /**
     * Show the department list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function departmentList()
    {
        $departments = Department::get();
        return view('superadmin.department.department_list', ['departments' => $departments]);
    }

    public function createDepartment()
    {
        return view('superadmin.department.add_department');
    }

    public function departmentCreate(Request $request)
    {
        $data = $request->all();

        $duplicate_department_check = Department::get()->where('name', $data['name']);

        if(count($duplicate_department_check) == 0) {
            Department::create([
                'name' => $data['name'],
                'subject_code' => $data['subject_code']
            ]);

            return redirect()->back()->with('message','You have successfully create a new department.');

        } else {
            return back()
            ->with('error','Sorry this department already exists');
        }
    }

    public function editDepartment($id)
    {
        $department = Department::find($id);
        return view('superadmin.department.edit_department', ['department' => $department]);
    }

    public function departmentUpdate(Request $request, $id)
    {
        $data = $request->all();

        $duplicate_department_check = Department::get()->where('name', $data['name'])->where('id' , '<>', $id);

        if(count($duplicate_department_check) == 0) {
            Department::where('id', $id)->update([
                'name' => $data['name'],
                'subject_code' => $data['subject_code']
            ]);
            
            return redirect()->back()->with('message','You have successfully update subject.');
        } else {
            return back()
            ->with('error','Sorry this department already exists');
        }
    }

    public function departmentDelete($id)
    {
        $department = Department::find($id);
        $department->delete();
        return redirect()->back()->with('message','You have successfully delete department.');
    }


    /**
     * Show the package list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function superadminPackage()
    {
        $packages = Package::all();
        return view('superadmin.package.package', ['packages' => $packages]);
    }

    public function createPackage()
    {
        return view('superadmin.package.add_package');
    }

    public function packageCreate(Request $request)
    {
        $data = $request->all();

        Package::create($data);

        return redirect()->back()->with('message', 'You have successfully create a packgae.');
    }

    public function editPackage($id)
    {
        $package = Package::find($id);
        return view('superadmin.package.edit_package', ['package' => $package]);
    }

    public function packageUpdate(Request $request, $id)
    {
        $data = $request->all();

        unset($data['_token']);

        Package::where('id', $id)->update($data);

        return redirect()->back()->with('message', 'You have successfully update package.');
    }

    public function packageDelete($id)
    {
        $package = Package::find($id);
        $package->delete();
        return redirect()->back()->with('message', 'You have successfully delete a package.');
    }

    /**
     * Show the subscription.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function subscriptionReport(Request $request)
    {
        if (count($request->all()) > 0) {
            $data = $request->all();
            $date = explode('-', $data['eDateRange']);
            $date_from = strtotime($date[0] . ' 00:00:00');
            $date_to  = strtotime($date[1] . ' 23:59:59');
            $subscriptions = Subscription::where('date_added', '>=', $date_from)
                ->where('date_added', '<=', $date_to)
                ->get();
        } else {
            $date_from = strtotime('first day of january this year');
            $date_to = strtotime('last day of december this year');
            $subscriptions = Subscription::where('date_added', '>=', $date_from)
                ->where('date_added', '<=', $date_to)
                ->get();
        }

        return view('superadmin.subscription.subscription_report', ['subscriptions' => $subscriptions, 'date_from' => $date_from, 'date_to' => $date_to]);
    }

    public function subscriptionPendingPayment()
    {
        $payment_histories = PaymentHistory::where('paid_by', 'offline')
            ->where('status', 'pending')
            ->get();
        return view('superadmin.subscription.pending', ['payment_histories' => $payment_histories]);
    }

    public function subscriptionPaymentStatus($status = "", $id = "")
    {

       
        
        if ($status == 'approve') {

            $payment_history = PaymentHistory::find($id);
            $package = Package::find($payment_history->package_id);
            

            if(strtolower($package->interval)=='days')
            {
               $expire_date = strtotime('+'.$package->days.' days', strtotime(date("Y-m-d H:i:s")) );

            }
             elseif(strtolower($package->interval)=='monthly')
            {
                $monthly=$package->days*30;

            $expire_date = strtotime('+'.$monthly.' days', strtotime(date("Y-m-d H:i:s")) );

            }
             elseif(strtolower($package->interval)=='yearly')
            {
                $yearly=$package->days*365;
             $expire_date = strtotime('+'.$yearly.' days', strtotime(date("Y-m-d H:i:s")) );

            }
          

            $last_package = Subscription::where('school_id', auth()->user()->school_id)->orderBy('id', 'desc')->first();



            Subscription::create([
                'package_id' => $payment_history->package_id,
                'school_id' => $payment_history->school_id,
                'paid_amount' => $payment_history->amount,
                'payment_method' => ucwords($payment_history->paid_by),
                'transaction_keys' => json_encode(array()),
                'expire_date' => $expire_date,
                'date_added' => strtotime(date('Y-m-d')),
                'active' => '1',
                'status' => '1',
            ]);

             PaymentHistory::where('id', $id)->update([
                'status' => $status,
            ]);

            if (!empty($last_package)) {
                $last_package = $last_package->toArray();

                Subscription::where('id',  $last_package['id'])->update([
                    'active' => 0,
                ]);
            }
        }

        return redirect()->back()->with('message', 'You have successfully update status.');
    }

    public function subscriptionApprovePayment()
    {
        $payment_histories = PaymentHistory::where('paid_by', 'offline')
            ->where('status', 'approve')
            ->get();
        return view('superadmin.subscription.approve', ['payment_histories' => $payment_histories]);
    }

    public function subscriptionPaymentDelete($id)
    {
        $payment_history = PaymentHistory::find($id);
        $payment_history->delete();
        return redirect()->back()->with('message', 'You have successfully delete a payment history.');
    }


    /**
     * Show the addon list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addonList()
    {
        $addons = Addon::all();
        return view('superadmin.addons.list', ['addons' => $addons]);
    }

    public function addonInstall()
    {
        return view('superadmin.addons.create');
    }

    public function addonCreate(Request $request)
    {
        // code...
    }

    public function addonStatus($id = '')
    {
        $addon = Addon::find($id);
        if ($addon->status == 1) {
            Addon::where('id', $id)->update([
                'status' => '0',
            ]);
        } else {
            Addon::where('id', $id)->update([
                'status' => '1',
            ]);
        }

        return to_route('superadmin.addon.list');
    }

    public function addonDelete($id)
    {
        $addon = Addon::find($id);
        $addon->delete();
        return redirect()->back()->with('message', 'You have successfully delete a addon.');
    }


    /**
     * Show the system settings.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function systemSettings()
    {
        return view('superadmin.settings.system_settings');
    }

    public function systemUpdate(Request $request)
    {
        $data = $request->all();

        unset($data['_token']);
        foreach ($data as $key => $value) {
            GlobalSettings::where('key', $key)->update([
                'key' => $key,
                'value' => $value,
            ]);
        }

        return redirect()->back()->with('message', 'System settings updated successfully.');
    }


    /**
     * Show the smtp settings.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function smtpSettings()
    {
        return view('superadmin.settings.smtp_settings');
    }

    public function smtpUpdate(Request $request)
    {
        $data = $request->all();

        unset($data['_token']);
        foreach($data as $key => $value){
            if($key == 'smtp_protocol'){
                set_config('MAIL_MAILER', $value);
            }elseif($key == 'smtp_crypto'){
                set_config('MAIL_ENCRYPTION', $value);
            }elseif($key == 'smtp_host'){
                set_config('MAIL_HOST', $value);
            }elseif($key == 'smtp_port'){
                set_config('MAIL_PORT', $value);
            }elseif($key == 'smtp_user'){
                set_config('MAIL_USERNAME', $value);
            }elseif($key == 'smtp_pass'){
                set_config('MAIL_PASSWORD', $value);
            }
            GlobalSettings::where('key', $key)->update([
                'key' => $key,
                'value' => $value,
            ]);
        }

        return redirect()->back()->with('message','Smtp settings updated successfully.');
    }


    
    public function about()
    {

        $purchase_code = get_settings('purchase_code');
        $returnable_array = array(
            'purchase_code_status' => get_phrase('Not found'),
            'support_expiry_date'  => get_phrase('Not found'),
            'customer_name'        => get_phrase('Not found')
        );

        $personal_token = "gC0J1ZpY53kRpynNe4g2rWT5s4MW56Zg";
        $url = "https://api.envato.com/v3/market/author/sale?code=" . $purchase_code;
        $curl = curl_init($url);

        //setting the header for the rest of the api
        $bearer   = 'bearer ' . $personal_token;
        $header   = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:' . $purchase_code . '.json';
        $ch_verify = curl_init($verify_url . '?code=' . $purchase_code);

        curl_setopt($ch_verify, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch_verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_verify, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_verify, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $cinit_verify_data = curl_exec($ch_verify);
        curl_close($ch_verify);

        $response = json_decode($cinit_verify_data, true);

        if (is_array($response) && isset($response['verify-purchase']) && count($response['verify-purchase']) > 0) {

            //print_r($response);
            $item_name         = $response['verify-purchase']['item_name'];
            $purchase_time       = $response['verify-purchase']['created_at'];
            $customer         = $response['verify-purchase']['buyer'];
            $licence_type       = $response['verify-purchase']['licence'];
            $support_until      = $response['verify-purchase']['supported_until'];
            $customer         = $response['verify-purchase']['buyer'];

            $purchase_date      = date("d M, Y", strtotime($purchase_time));

            $todays_timestamp     = strtotime(date("d M, Y"));
            $support_expiry_timestamp = strtotime($support_until);

            $support_expiry_date  = date("d M, Y", $support_expiry_timestamp);

            if ($todays_timestamp > $support_expiry_timestamp)
                $support_status    = 'expired';
            else
                $support_status    = 'valid';

            $returnable_array = array(
                'purchase_code_status' => $support_status,
                'support_expiry_date'  => $support_expiry_date,
                'customer_name'        => $customer,
                'product_license'      => 'valid',
                'license_type'         => $licence_type
            );
        } else {
            $returnable_array = array(
                'purchase_code_status' => 'invalid',
                'support_expiry_date'  => 'invalid',
                'customer_name'        => 'invalid',
                'product_license'      => 'invalid',
                'license_type'         => 'invalid'
            );
        }


        $data['application_details'] = $returnable_array;
        return view('superadmin.settings.about', $data);
    }


    function curl_request($code = '')
    {

        $purchase_code = $code;

        $personal_token = "FkA9UyDiQT0YiKwYLK3ghyFNRVV9SeUn";
        $url = "https://api.envato.com/v3/market/author/sale?code=" . $purchase_code;
        $curl = curl_init($url);

        //setting the header for the rest of the api
        $bearer   = 'bearer ' . $personal_token;
        $header   = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:' . $purchase_code . '.json';
        $ch_verify = curl_init($verify_url . '?code=' . $purchase_code);

        curl_setopt($ch_verify, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch_verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_verify, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_verify, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $cinit_verify_data = curl_exec($ch_verify);
        curl_close($ch_verify);

        $response = json_decode($cinit_verify_data, true);

        if (is_array($response) && count($response['verify-purchase']) > 0) {
            return true;
        } else {
            return false;
        }
    }


    //Don't remove this code for security reasons
    function save_valid_purchase_code($action_type, Request $request){

        if($action_type == 'update'){
            $data['value'] = $request->purchase_code;

            $status = $this->curl_request($data['value']);
            if($status){  
                GlobalSettings::where('key', 'purchase_code')->update($data);
                session()->flash('message', get_phrase('Purchase code has been updated'));
                echo 1;
            }else{
                echo 0;
            }
        }else{
            return view('superadmin.settings.save_purchase_code_form');
        }
        
    }

    public function payment_settings()
    {


        $global_currency = GlobalSettings::where('key', 'system_currency')->first()->toArray();
        $global_currency = $global_currency['value'];
        $global_currency_position = GlobalSettings::where('key', 'currency_position')->first()->toArray();
        $global_currency_position = $global_currency_position['value'];

        $currencies = Currency::all()->toArray();

        $paypal = "";
        $stripe = "";
        $razorpay = "";
        $paytm = "";

        $paypal = GlobalSettings::where('key', 'paypal')->first();
        if (!empty($paypal)) {
            
            $paypal = json_decode($paypal['value'], true);
        }

        $stripe = GlobalSettings::where('key', 'stripe')->first();
        if (!empty($stripe)) {
          
            $stripe = json_decode($stripe['value'], true);
        }

        $razorpay = GlobalSettings::where('key', 'razorpay')->first();
        if (!empty($razorpay)) {
         
            $razorpay = json_decode($razorpay['value'], true);
        }

        $paytm = GlobalSettings::where('key', 'paytm')->first();
        if (!empty($paytm)) {

            $paytm = json_decode($paytm['value'], true);
        }




        return view('superadmin.payment_credentials.payment_settings', ['paytm' => $paytm, 'razorpay' => $razorpay, 'stripe' => $stripe, 'paypal' => $paypal, 'global_currency' => $global_currency, 'global_currency_position' => $global_currency_position, 'currencies' => $currencies]);
    }



    public function update_payment_settings(Request $request)
    {

        $data = $request->all();
        $update_id = $data['method'];



        if ($data['method'] == 'currency') {

            GlobalSettings::where('key', 'system_currency')->update([
                'value' =>  $data['global_currency'],
            ]);
            GlobalSettings::where('key', 'currency_position')->update([
                'value' =>  $data['currency_position'],
            ]);
        } elseif ($data['method'] == 'paypal') {
            $keys = array();
            $paypal = GlobalSettings::where('key', 'paypal')->first();
            $keys['status'] = $data['status'];
            $keys['mode'] = $data['mode'];
            $keys['test_client_id'] = $data['test_client_id'];
            $keys['test_secret_key'] = $data['test_secret_key'];
            $keys['live_client_id'] = $data['live_client_id'];
            $keys['live_secret_key'] = $data['live_secret_key'];
            $paypal['value'] = json_encode($keys);
            $paypal->save();
        } elseif ($data['method'] == 'stripe') {
            $keys = array();
            $stripe = GlobalSettings::where('key', 'stripe')->first();
            $keys['status'] = $data['status'];
            $keys['mode'] = $data['mode'];
            $keys['test_key'] = $data['test_key'];
            $keys['test_secret_key'] = $data['test_secret_key'];
            $keys['public_live_key'] = $data['public_live_key'];
            $keys['secret_live_key'] = $data['secret_live_key'];
            $stripe['value'] = json_encode($keys);
            $stripe->save();
        } elseif ($data['method'] == 'razorpay') {
            $keys = array();
            $razorpay = GlobalSettings::where('key', 'razorpay')->first();
            $keys['status'] = $data['status'];
            $keys['mode'] = $data['mode'];
            $keys['test_key'] = $data['test_key'];
            $keys['test_secret_key'] = $data['test_secret_key'];
            $keys['live_key'] = $data['live_key'];
            $keys['live_secret_key'] = $data['live_secret_key'];
            $keys['theme_color'] = $data['theme_color'];
            $razorpay['value'] = json_encode($keys);
            $razorpay->save();
        } elseif ($data['method'] == 'paytm') {
            $keys = array();
            $paytm = GlobalSettings::where('key', 'paytm')->first();
            $keys['status'] = $data['status'];
            $keys['mode'] = $data['mode'];
            $keys['test_merchant_id'] = $data['test_merchant_id'];
            $keys['test_merchant_key'] = $data['test_merchant_key'];
            $keys['live_merchant_id'] = $data['live_merchant_id'];
            $keys['live_merchant_key'] = $data['live_merchant_key'];
            $keys['environment'] = $data['environment'];
            $keys['merchant_website'] = $data['merchant_website'];
            $keys['channel'] = $data['channel'];
            $keys['industry_type'] = $data['industry_type'];
            $paytm['value'] = json_encode($keys);
            $paytm->save();
        }


        return redirect()->route('superadmin.payment_settings')->with('message', 'key has been updated');
    }


    function profile(){
        return view('superadmin.profile.view');
    }

    function profile_update(Request $request){
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['designation'] = $request->designation;
        
        $user_info['birthday'] = strtotime($request->eDefaultDateRange);
        $user_info['gender'] = $request->gender;
        $user_info['phone'] = $request->phone;
        $user_info['address'] = $request->address;


        if(empty($request->photo)){
            $user_info['photo'] = $request->old_photo;
        }else{
            $file_name = random(10).'.png';
            $user_info['photo'] = $file_name;

            $request->photo->move(public_path('assets/uploads/user-images/'), $file_name);
        }

        $data['user_information'] = json_encode($user_info);

        User::where('id', auth()->user()->id)->update($data);
        
        return redirect(route('superadmin.profile'))->with('message', get_phrase('Profile info updated successfully'));
    }

    function password($action_type = null, Request $request){



        if($action_type == 'update'){

            

            if($request->new_password != $request->confirm_password){
                return back()->with("error", "Confirm Password Doesn't match!");
            }


            if(!Hash::check($request->old_password, auth()->user()->password)){
                return back()->with("error", "Current Password Doesn't match!");
            }

            $data['password'] = Hash::make($request->new_password);
            User::where('id', auth()->user()->id)->update($data);

            return redirect(route('superadmin.password', 'edit'))->with('message', get_phrase('Password changed successfully'));
        }

        return view('superadmin.profile.password');
    }

    //logo update
    function update_logo(Request $request){
        $dark_logo = time().'1.png';
        $light_logo = time().'2.png';
        $favicon = time().'3.png';

        if(!empty($request->dark_logo)){
            $request->dark_logo->move(public_path('assets/uploads/logo/'), $dark_logo);
            GlobalSettings::where('key', 'dark_logo')->update(['value' => $dark_logo]);
        }
        if(!empty($request->light_logo)){
            $request->light_logo->move(public_path('assets/uploads/logo/'), $light_logo);
            GlobalSettings::where('key', 'light_logo')->update(['value' => $light_logo]);
        }
        if(!empty($request->favicon)){
            $request->favicon->move(public_path('assets/uploads/logo/'), $favicon);
            GlobalSettings::where('key', 'favicon')->update(['value' => $favicon]);
        }

        return redirect('superadmin/settings/system')->with('message', "Logo updated successfully");

    }


    /**
     * Show the session manager.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function sessionManager()
    {
        $sessions = Session::where('school_id', auth()->user()->school_id)->get();
        return view('superadmin.session.session_manager', ['sessions' => $sessions]);
    }

    public function activeSession($id)
    {
        $previous_session_id = get_school_settings(auth()->user()->school_id)->value('running_session');

        Session::where('id', $previous_session_id)->update([
            'status' => '0',
        ]);

        $session = Session::where('id', $id)->update([
            'status' => '1',
        ]);

        School::where('id', auth()->user()->school_id)->update([
            'running_session' => $id,
        ]);

        $response = array(
            'status' => true,
            'notification' => get_phrase('Session has been activated')
        );
        $response = json_encode($response);

        echo $response;
    }

    public function createSession()
    {
        return view('superadmin.session.create');
    }

    public function sessionCreate(Request $request)
    {
        $data = $request->all();

        $duplicate_session_check = Session::get()->where('session_title', $data['session_title'])->where('school_id', auth()->user()->school_id);

        if (count($duplicate_session_check) == 0) {

            $data['status'] = '0';
            $data['school_id'] = auth()->user()->school_id;
            $data['starting_date'] = date('Y-m-d', strtotime($request->starting_date));
            $data['ending_date'] = date('Y-m-d', strtotime($request->ending_date));

            Session::create($data);

            return redirect()->back()->with('message', 'You have successfully create a session.');
        } else {
            return redirect()->back()->with('error', 'Sorry this session already exists');
        }
    }

    public function editSession($id = '')
    {
        $session = Session::find($id);
        return view('superadmin.session.edit', ['session' => $session]);
    }

    public function sessionUpdate(Request $request, $id)
    {
        $data = $request->all();

        unset($data['_token']);

        $data['starting_date'] = date('Y-m-d', strtotime($request->starting_date));
        $data['ending_date'] = date('Y-m-d', strtotime($request->ending_date));

        Session::where('id', $id)->update($data);

        return redirect()->back()->with('message', 'You have successfully update session.');
    }

    public function sessionDelete($id = '')
    {
        $previous_session_id = get_school_settings(auth()->user()->school_id)->value('running_session');

        if($previous_session_id != $id){
            $session = Session::find($id);
            $session->delete();
            return redirect()->back()->with('message', 'You have successfully delete a session.');
        } else {
            return redirect()->back()->with('error', 'Can not delete active session.');
        }
    }

    public function discountManager(){
        $discounts = Discount::get();
        return view('superadmin.discount.list', ['discounts' => $discounts]);
    }

    public function discountAdd()
    {
        $enrolment_years = EnrollmentYear::where('status',1)->get();
        $branches = School::where('status',1)->get();
        $levels = Classes::where('status','active')->get();
        $courses = Department::get();
        return view('superadmin.discount.add', ['enrolment_years' => $enrolment_years, 'branches' => $branches, 'levels' => $levels, 'courses' => $courses]);
    }

    public function createDiscount(Request $request)
    {
        $data = $request->all();

        if($request->earliest_commencement_date_check && $request->earliest_commencement_date != ''){
            $earliest_commencement_date = explode(' - ',$request->earliest_commencement_date);
            $earliest_commencement_start_date = date('Y-m-d', strtotime($earliest_commencement_date[0]));
            $earliest_commencement_end_date = date('Y-m-d', strtotime($earliest_commencement_date[1]));
        }else{
            $earliest_commencement_start_date = NULL;
            $earliest_commencement_end_date = NULL;
        }

        if($request->course_commencement_date_check && $request->course_commencement_date != ''){
            $course_commencement_date = explode(' - ',$request->course_commencement_date);
            $course_commencement_start_date = date('Y-m-d', strtotime($course_commencement_date[0]));
            $course_commencement_end_date = date('Y-m-d', strtotime($course_commencement_date[1]));
        }else{
            $course_commencement_start_date = NULL;
            $course_commencement_end_date = NULL;
        }

        if($request->invoice_commencement_date_check && $request->invoice_commencement_date != ''){
            $invoice_commencement_date = explode(' - ',$request->invoice_commencement_date);
            $invoice_commencement_start_date = date('Y-m-d', strtotime($invoice_commencement_date[0]));
            $invoice_commencement_end_date = date('Y-m-d', strtotime($invoice_commencement_date[1]));
        }else{
            $invoice_commencement_start_date = NULL;
            $invoice_commencement_end_date = NULL;
        }

        $discount = Discount::create([
            'title' => $data['title'],
            'discount_type' => $data['discount_type'],
            'discount_rule' => $data['discount_rule'],
            'discount_amount' => $data['discount_amount'],
            'frequency' => $data['frequency'],
            'enrolment_year' => $data['enrolment_year'],
            'earliest_commencement_start_date' => $earliest_commencement_start_date,
            'earliest_commencement_end_date' => $earliest_commencement_end_date,
            'course_commencement_start_date' => $course_commencement_start_date,
            'course_commencement_end_date' => $course_commencement_end_date,
            'invoice_commencement_start_date' => $invoice_commencement_start_date,
            'invoice_commencement_end_date' => $invoice_commencement_end_date,
            'remarks' => $data['remarks'],
            'status' => $data['status']
        ]);

        if($discount){
            foreach ($request->branches as $key => $branch) {
                DiscountBranch::create([
                    'discount_id' => $discount->id,
                    'branch_id' => $branch,
                ]);
            }

            foreach ($request->levels as $key => $level) {
                DiscountLevel::create([
                    'discount_id' => $discount->id,
                    'level_id' => $level,
                ]);
            }

            foreach ($request->courses as $key => $course) {
                DiscountCourse::create([
                    'discount_id' => $discount->id,
                    'course_id' => $course,
                ]);
            }
        }

        return to_route('superadmin.settings.discount_manager');
    }

    public function editDiscount($id){
        $discount = Discount::where('id', $id)->with('branches:branch_id,discount_id','levels:level_id,discount_id','courses:course_id,discount_id')->first();

        $selected_branches = array();
        $selected_levels = array();
        $selected_courses = array();

        $enrolment_years = EnrollmentYear::where('status',1)->get();
        $branches = School::where('status',1)->get();
        $levels = Classes::where('status','active')->get();
        $courses = Department::get();

        if($discount->branches->isNotEmpty()){
            $selected_branches = $discount->branches->pluck('branch_id')->toArray();
        }

        if($discount->levels->isNotEmpty()){
            $selected_levels = $discount->levels->pluck('level_id')->toArray();
        }

        if($discount->courses->isNotEmpty()){
            $selected_courses = $discount->courses->pluck('course_id')->toArray();
        }

        return view('superadmin.discount.edit', ['discount' => $discount, 'branches' => $branches, 'levels' => $levels, 'courses' => $courses, 'enrolment_years' => $enrolment_years, 'selected_branches' => $selected_branches, 'selected_levels' => $selected_levels, 'selected_courses' => $selected_courses]);
    }

    public function updateDiscount(Request $request){
        $data = $request->all();

        $discount = Discount::where('id', $request->id)->first();

        if($discount){
            if($request->earliest_commencement_date_check && $request->earliest_commencement_date != ''){
                $earliest_commencement_date = explode(' - ',$request->earliest_commencement_date);
                $earliest_commencement_start_date = date('Y-m-d', strtotime($earliest_commencement_date[0]));
                $earliest_commencement_end_date = date('Y-m-d', strtotime($earliest_commencement_date[1]));
            }else{
                $earliest_commencement_start_date = NULL;
                $earliest_commencement_end_date = NULL;
            }

            if($request->course_commencement_date_check && $request->course_commencement_date != ''){
                $course_commencement_date = explode(' - ',$request->course_commencement_date);
                $course_commencement_start_date = date('Y-m-d', strtotime($course_commencement_date[0]));
                $course_commencement_end_date = date('Y-m-d', strtotime($course_commencement_date[1]));
            }else{
                $course_commencement_start_date = NULL;
                $course_commencement_end_date = NULL;
            }

            if($request->invoice_commencement_date_check && $request->invoice_commencement_date != ''){
                $invoice_commencement_date = explode(' - ',$request->invoice_commencement_date);
                $invoice_commencement_start_date = date('Y-m-d', strtotime($invoice_commencement_date[0]));
                $invoice_commencement_end_date = date('Y-m-d', strtotime($invoice_commencement_date[1]));
            }else{
                $invoice_commencement_start_date = NULL;
                $invoice_commencement_end_date = NULL;
            }

            $discount->update([
                'title' => $data['title'],
                'discount_type' => $data['discount_type'],
                'discount_rule' => $data['discount_rule'],
                'discount_amount' => $data['discount_amount'],
                'frequency' => $data['frequency'],
                'enrolment_year' => $data['enrolment_year'],
                'earliest_commencement_start_date' => $earliest_commencement_start_date,
                'earliest_commencement_end_date' => $earliest_commencement_end_date,
                'course_commencement_start_date' => $course_commencement_start_date,
                'course_commencement_end_date' => $course_commencement_end_date,
                'invoice_commencement_start_date' => $invoice_commencement_start_date,
                'invoice_commencement_end_date' => $invoice_commencement_end_date,
                'remarks' => $data['remarks'],
                'status' => $data['status']
            ]);

            if($discount){
                DiscountBranch::where('discount_id', $discount->id)->delete();
                DiscountLevel::where('discount_id', $discount->id)->delete();
                DiscountCourse::where('discount_id', $discount->id)->delete();
                foreach ($request->branches as $key => $branch) {
                    DiscountBranch::create([
                        'discount_id' => $discount->id,
                        'branch_id' => $branch,
                    ]);
                }

                foreach ($request->levels as $key => $level) {
                    DiscountLevel::create([
                        'discount_id' => $discount->id,
                        'level_id' => $level,
                    ]);
                }

                foreach ($request->courses as $key => $course) {
                    DiscountCourse::create([
                        'discount_id' => $discount->id,
                        'course_id' => $course,
                    ]);
                }
            }
        }

        return to_route('superadmin.settings.discount_manager', ['id' => 1]);
    }

    public function discountDelete($id)
    {
        $discount = Discount::find($id);

        if($discount){
            DiscountBranch::where('discount_id', $discount->id)->delete();
            DiscountLevel::where('discount_id', $discount->id)->delete();
            DiscountCourse::where('discount_id', $discount->id)->delete();
            $discount->delete();
        }

        return redirect()->back()->with('message', 'You have successfully deleted a discount.');
    }
}
