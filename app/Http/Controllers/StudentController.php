<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Session;
use App\Models\Exam;
use App\Models\ExamCategory;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Gradebook;
use App\Models\Grade;
use App\Models\ClassList;
use App\Models\Section;
use App\Models\Enrollment;
use App\Models\DailyAttendances;
use App\Models\Routine;
use App\Models\Syllabus;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Noticeboard;
use App\Models\FrontendEvent;
use App\Models\Admin;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\StudentFeeManager;
use App\Models\PaymentMethods;
use App\Models\Payments;
use Illuminate\Foundation\Auth\User as AuthUser;

class StudentController extends Controller
{
    /**
     * Show the student dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function studentDashboard()
    {
        if (auth()->user()->role_id == 7) {
            return view('student.dashboard');
        } else {
            redirect()->route('login')
                ->with('error', 'You are not logged in.');
        }
    }

    /**
     * Show the teacher list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function teacherList()
    {
        $teachers = User::where('role_id', 3)
            ->where('school_id', auth()->user()->school_id)
            ->paginate(10);
        return view('student.teacher.teacher_list', compact('teachers'));
    }

    /**
     * Show the daily attendance.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dailyAttendance()
    {
        $student_data = (new CommonController)->get_student_details_by_id(auth()->user()->id);
        return view('student.attendance.daily_attendance', ['student_data' => $student_data]);
    }

    public function dailyAttendanceFilter(Request $request)
    {
        $data = $request->all();
        $date = '01 ' . $data['month'] . ' ' . $data['year'];
        $page_data['attendance_date'] = strtotime($date);
        $page_data['class_id'] = $data['class_id'];
        $page_data['section_id'] = $data['section_id'];
        $page_data['month'] = $data['month'];
        $page_data['year'] = $data['year'];
        $classes = Classes::where('school_id', auth()->user()->school_id)->get();
        return view('student.attendance.attendance_list', ['page_data' => $page_data, 'classes' => $classes]);
    }

    /**
     * Show the routine.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function routine()
    {
        $student_data = (new CommonController)->get_student_details_by_id(auth()->user()->id);
        $class_id = $student_data['class_id'];
        $section_id = $student_data['section_id'];
        $classes = Classes::where('school_id', auth()->user()->school_id)->get();
        return view('student.routine.routine', ['class_id' => $class_id, 'section_id' => $section_id, 'classes' => $classes]);
    }

    /**
     * Show the subject list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function subjectList()
    {
        $active_session = Session::where('status', 1)->first();

        $student_data = (new CommonController)->get_student_details_by_id(auth()->user()->id);
        $subjects = Subject::where('class_id', $student_data['class_id'])
            ->where('school_id', auth()->user()->school_id)
            ->where('session_id', $active_session->id)
            ->paginate(10);

        return view('student.subject.subject_list', compact('subjects'));
    }

    /**
     * Show the syllabus.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function syllabus()
    {
        if (auth()->user()->role_id != "" && auth()->user()->role_id == 7) {
            $active_session = Session::where('status', 1)->first();
            $student_data = (new CommonController)->get_student_details_by_id(auth()->user()->id);

            $syllabuses = Syllabus::where(['class_id' => $student_data['class_id'], 'section_id' => $student_data['section_id'], 'session_id' => $active_session->id, 'school_id' => auth()->user()->school_id])->paginate(10);

            return view('student.syllabus.syllabus', compact('syllabuses'));
        } else {
            return redirect('login')->with('error', "Please login first.");
        }
    }

    /**
     * Show the grade list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function marks($value = '')
    {
        $exam_categories = ExamCategory::where('school_id', auth()->user()->school_id)->get();
        $user_id = auth()->user()->id;
        $student_details = (new CommonController)->get_student_details_by_id($user_id);

        $subjects = Subject::where(['class_id' => $student_details['class_id'], 'school_id' => auth()->user()->school_id])->get();


        return view('student.marks.index', ['exam_categories' => $exam_categories, 'student_details' => $student_details, 'subjects' => $subjects]);
    }

    public function gradeList()
    {
        $grades = Grade::where('school_id', auth()->user()->school_id)->paginate(10);
        return view('student.grade.grade_list', compact('grades'));
    }

    /**
     * Show the book list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function bookList()
    {
        $books = Book::where('school_id', auth()->user()->school_id)->paginate(10);
        return view('student.book.list', ['books' => $books]);
    }

    public function bookIssueList(Request $request)
    {
        $active_session = Session::where('status', 1)->first();

        if (count($request->all()) > 0) {

            $data = $request->all();

            $date = explode('-', $data['eDateRange']);
            $date_from = strtotime($date[0] . ' 00:00:00');
            $date_to  = strtotime($date[1] . ' 23:59:59');
            $book_issues = BookIssue::where('issue_date', '>=', $date_from)
                ->where('issue_date', '<=', $date_to)
                ->where('school_id', auth()->user()->school_id)
                ->where('session_id', $active_session->id)
                ->get();

            return view('student.book.book_issue', ['book_issues' => $book_issues, 'date_from' => $date_from, 'date_to' => $date_to]);
        } else {

            $date_from = strtotime(date('d-M-Y', strtotime(' -30 day')) . ' 00:00:00');
            $date_to = strtotime(date('d-M-Y') . ' 23:59:59');
            $book_issues = BookIssue::where('issue_date', '>=', $date_from)
                ->where('issue_date', '<=', $date_to)
                ->where('school_id', auth()->user()->school_id)
                ->where('session_id', $active_session->id)
                ->get();

            return view('student.book.book_issue', ['book_issues' => $book_issues, 'date_from' => $date_from, 'date_to' => $date_to]);
        }
    }

    /**
     * Show the noticeboard list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function noticeboardList()
    {
        $notices = Noticeboard::get()->where('school_id', auth()->user()->school_id);

        $events = array();

        foreach ($notices as $notice) {
            if ($notice['end_date'] != "") {
                if ($notice['start_date'] != $notice['end_date']) {
                    $end_date = strtotime($notice['end_date']) + 24 * 60 * 60;
                    $end_date = date('Y-m-d', $end_date);
                } else {
                    $end_date = $notice['end_date'];
                }
            }

            if ($notice['end_date'] == "" && $notice['start_time'] == "" && $notice['end_time'] == "") {
                $info = array(
                    'id' => $notice['id'],
                    'title' => $notice['notice_title'],
                    'start' => $notice['start_date']
                );
            } else if ($notice['start_time'] != "" && ($notice['end_date'] == "" && $notice['end_time'] == "")) {
                $info = array(
                    'id' => $notice['id'],
                    'title' => $notice['notice_title'],
                    'start' => $notice['start_date'] . 'T' . $notice['start_time']
                );
            } else if ($notice['end_date'] != "" && ($notice['start_time'] == "" && $notice['end_time'] == "")) {
                $info = array(
                    'id' => $notice['id'],
                    'title' => $notice['notice_title'],
                    'start' => $notice['start_date'],
                    'end' => $end_date
                );
            } else if ($notice['end_date'] != "" && $notice['start_time'] != "" && $notice['end_time'] != "") {
                $info = array(
                    'id' => $notice['id'],
                    'title' => $notice['notice_title'],
                    'start' => $notice['start_date'] . 'T' . $notice['start_time'],
                    'end' => $notice['end_date'] . 'T' . $notice['end_time']
                );
            } else {
                $info = array(
                    'id' => $notice['id'],
                    'title' => $notice['notice_title'],
                    'start' => $notice['start_date']
                );
            }
            array_push($events, $info);
        }

        $events = json_encode($events);

        return view('student.noticeboard.noticeboard', ['events' => $events]);
    }

    public function editNoticeboard($id = "")
    {
        $notice = Noticeboard::find($id);
        return view('student.noticeboard.edit', ['notice' => $notice]);
    }

    /**
     * Show the live class.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function FeeManagerList(Request $request)
    {
        $active_session = Session::where('status', 1)->first();
        $student_class_information = Enrollment::where('user_id', auth()->user()->id)->first()->toArray();

        if (count($request->all()) > 0) {


            $data = $request->all();
            $date = explode('-', $data['eDateRange']);
            $date_from = strtotime($date[0] . ' 00:00:00');
            $date_to  = strtotime($date[1] . ' 23:59:59');
            $selected_status = $data['status'];

            if ($selected_status != "all") {
                $invoices = StudentFeeManager::where('timestamp', '>=', $date_from)->where('timestamp', '<=', $date_to)->where('status', $selected_status)->where('student_id', auth()->user()->id)->where('session_id', $active_session->id)->get();
            } else if ($selected_status == "all") {
                $invoices = StudentFeeManager::where('timestamp', '>=', $date_from)->where('timestamp', '<=', $date_to)->where('school_id', auth()->user()->school_id)->where('student_id', auth()->user()->id)->where('session_id', $active_session->id)->get();
            }


            return view('student.fee_manager.student_fee_manager', ['invoices' => $invoices, 'date_from' => $date_from, 'date_to' => $date_to,  'selected_status' => $selected_status]);
        } else {

            $date_from = strtotime(date('d-M-Y', strtotime(' -30 day')) . ' 00:00:00');
            $date_to = strtotime(date('d-M-Y') . ' 23:59:59');
            $selected_status = "";

            $invoices = StudentFeeManager::where('timestamp', '>=', $date_from)->where('timestamp', '<=', $date_to)->where('student_id', auth()->user()->id)->where('school_id', auth()->user()->school_id)->where('session_id', $active_session->id)->get();

            return view('student.fee_manager.student_fee_manager', ['invoices' => $invoices, 'date_from' => $date_from, 'date_to' => $date_to,  'selected_status' => $selected_status]);
        }
    }

    public function feeManagerExport($date_from = "", $date_to = "", $selected_status = "")
    {

        $active_session = Session::where('status', 1)->first();


        if ($selected_status != "all") {
            $invoices = StudentFeeManager::where('timestamp', '>=', $date_from)->where('timestamp', '<=', $date_to)->where('status', $selected_status)->where('student_id', auth()->user()->id)->where('session_id', $active_session->id)->get();
        } else if ($selected_status == "all") {
            $invoices = StudentFeeManager::where('timestamp', '>=', $date_from)->where('timestamp', '<=', $date_to)->where('school_id', auth()->user()->school_id)->where('student_id', auth()->user()->id)->where('session_id', $active_session->id)->get();
        }

        $classes = Classes::where('school_id', auth()->user()->school_id)->get();



        $file = "student_fee-" . date('d-m-Y', $date_from) . '-' . date('d-m-Y', $date_to) . '-' . $selected_status . ".csv";

        $csv_content = get_phrase('Invoice No') . ', ' . get_phrase('Student') . ', ' . get_phrase('Class') . ', ' . get_phrase('Invoice Title') . ', ' . get_phrase('Total Amount') . ', ' . get_phrase('Created At') . ', ' . get_phrase('Paid Amount') . ', ' . get_phrase('Status');

        foreach ($invoices as $invoice) {
            $csv_content .= "\n";

            $student_details = (new CommonController)->get_student_details_by_id($invoice['student_id']);
            $invoice_no = sprintf('%08d', $invoice['id']);

            $csv_content .= $invoice_no . ', ' . $student_details['name'] . ', ' . $student_details['class_name'] . ', ' . $invoice['title'] . ', ' . currency($invoice['total_amount']) . ', ' . date('d-M-Y', $invoice['timestamp']) . ', ' . currency($invoice['paid_amount']) . ', ' . $invoice['status'];
        }
        $txt = fopen($file, "w") or die("Unable to open file!");
        fwrite($txt, $csv_content);
        fclose($txt);

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $file);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        header("Content-type: text/csv");
        readfile($file);
    }

    public function FeePayment(Request $request, $id)
    {

        $fee_details = StudentFeeManager::where('id', $id)->first()->toArray();
        $user_info = User::where('id', $fee_details['student_id'])->first()->toArray();
        return view('student.payment.payment_gateway', ['fee_details' => $fee_details, 'user_info' => $user_info]);
    }

    public function studentFeeinvoice(Request $request, $id)
    {

        $invoice_details = StudentFeeManager::find($id)->toArray();
        $student_details = (new CommonController)->get_student_details_by_id($invoice_details['student_id'])->toArray();

        return view('student.fee_manager.invoice', ['invoice_details' => $invoice_details, 'student_details' => $student_details]);
    }

   

    public function offlinePaymentStudent(Request $request, $id = "")
    {
        $data = $request->all();

        if ($data['amount'] > 0) {

            $file = $data['document_image'];

            if ($file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file

                
                $file->move(public_path('assets/uploads/offline_payment'), $filename);
                $data['document_image'] = $filename;
            } else {
                $data['document_image'] = '';
            }

            StudentFeeManager::where('id',  $id)->update([
                'status' => 'pending',
                'document_image' => $data['document_image'],
                'payment_method' => 'offline'
            ]);





            return redirect()->route('student.fee_manager.list')->with('message', 'offline payment requested successfully');
        } else {
            return redirect()->route('student.fee_manager.list')->with('message', 'offline payment requested fail');
        }
    }

    function profile(){
        return view('student.profile.view');
    }

    function profile_update(Request $request){
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        
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
        
        return redirect(route('student.profile'))->with('message', get_phrase('Profile info updated successfully'));
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

            return redirect(route('student.password', 'edit'))->with('message', get_phrase('Password changed successfully'));
        }

        return view('student.profile.password');
    }
}