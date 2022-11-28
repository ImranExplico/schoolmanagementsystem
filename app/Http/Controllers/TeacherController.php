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
use App\Models\Noticeboard;
use App\Models\FrontendEvent;
use App\Models\Admin;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\StudentFeeManager;
use App\Models\TeacherPermission;
use Illuminate\Foundation\Auth\User as AuthUser;
use Stripe\Exception\PermissionException;

class TeacherController extends Controller
{
    /**
     * Show the teacher dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function teacherDashboard()
    {
        return view('teacher.dashboard');
    }


    /**
     * Show the grade list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function marks($value = '')
    {
        $exam_categories = ExamCategory::where('school_id', auth()->user()->school_id)->get();
        $classes = Classes::where('school_id', auth()->user()->school_id)->get();
        return view('teacher.marks.index', ['exam_categories' => $exam_categories, 'classes' => $classes]);
    }

    public function marksFilter(Request $request)
    {
        $data = $request->all();

        $page_data['exam_category_id'] = $data['exam_category_id'];
        $page_data['class_id'] = $data['class_id'];
        $page_data['section_id'] = $data['section_id'];
        $page_data['subject_id'] = $data['subject_id'];

        $page_data['class_name'] = Classes::find($data['class_id'])->name;
        $page_data['section_name'] = Section::find($data['section_id'])->name;
        $page_data['subject_name'] = Subject::find($data['subject_id'])->name;

        $enroll_students = Enrollment::where('class_id', $page_data['class_id'])
            ->where('section_id', $page_data['section_id'])
            ->get();

        $page_data['exam_categories'] = ExamCategory::where('school_id', auth()->user()->school_id)->get();
        $page_data['classes'] = Classes::where('school_id', auth()->user()->school_id)->get();

        return view('teacher.marks.marks_list', ['enroll_students' => $enroll_students, 'page_data' => $page_data]);
    }

    /**
     * Show the exam list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function offlineExamList()
    {
        $id = "all";
        $exams = Exam::get()->where('exam_type', 'offline');
        $classes = Classes::where('school_id', auth()->user()->school_id)->get();
        return view('teacher.examination.offline_exam_list', ['exams' => $exams, 'classes' => $classes, 'id' => $id]);
    }

    public function offlineExamExport($id = "")
    {
        if ($id != "all") {
            $exams = Exam::where([
                'exam_type' => 'offline',
                'class_id' => $id
            ])->get();
        } else {
            $exams = Exam::get()->where('exam_type', 'offline');
        }
        $classes = Classes::where('school_id', auth()->user()->school_id)->get();
        return view('teacher.examination.offline_exam_export', ['exams' => $exams, 'classes' => $classes]);
    }

    public function classWiseOfflineExam($id)
    {
        $exams = Exam::where([
            'exam_type' => 'offline',
            'class_id' => $id
        ])->get();
        $classes = Classes::where('school_id', auth()->user()->school_id)->get();
        return view('teacher.examination.exam_list', ['exams' => $exams, 'classes' => $classes, 'id' => $id]);
    }

    /**
     * Show the routine.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function routine()
    {
        $classes = Classes::where('school_id', auth()->user()->school_id)->get();
        return view('teacher.routine.routine', ['classes' => $classes]);
    }

    public function routineList(Request $request)
    {
        $data = $request->all();

        $class_id = $data['class_id'];
        $section_id = $data['section_id'];
        $classes = Classes::where('school_id', auth()->user()->school_id)->get();

        return view('teacher.routine.routine_list', ['class_id' => $class_id, 'section_id' => $section_id, 'classes' => $classes]);
    }


    /**
     * Show the subject list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function subjectList(Request $request)
    {
        $classes = Classes::where('school_id', auth()->user()->school_id)->get();

        if (count($request->all()) > 0) {

            $data = $request->all();
            $class_id = $data['class_id'];
            $subjects = Subject::get()->where('class_id', $class_id);
        } else {
            $subjects = Subject::get()->where('school_id', auth()->user()->school_id);
            $class_id = '';
        }

        return view('teacher.subject.subject_list', ['subjects' => $subjects, 'classes' => $classes, 'class_id' => $class_id]);
    }

    /**
     * Show the gradebook.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function gradebook(Request $request)
    {

        $classes = Classes::get()->where('school_id', auth()->user()->school_id);
        $exam_categories = ExamCategory::get()->where('school_id', auth()->user()->school_id);

        $active_session = Session::where('status', 1)->first();

        if (count($request->all()) > 0) {

            $data = $request->all();

            $filter_list = Gradebook::where(['class_id' => $data['class_id'], 'section_id' => $data['section_id'], 'exam_category_id' => $data['exam_category_id'], 'school_id' => auth()->user()->school_id, 'session_id' => $active_session->id])->get();

            $class_id = $data['class_id'];
            $section_id = $data['section_id'];
            $exam_category_id = $data['exam_category_id'];
            $subjects = Subject::where(['class_id' => $class_id, 'school_id' => auth()->user()->school_id])->get();
        } else {
            $filter_list = [];

            $class_id = '';
            $section_id = '';
            $exam_category_id = '';
            $subjects = '';
        }

        return view('teacher.gradebook.gradebook', ['filter_list' => $filter_list, 'class_id' => $class_id, 'section_id' => $section_id, 'exam_category_id' => $exam_category_id, 'classes' => $classes, 'exam_categories' => $exam_categories, 'subjects' => $subjects]);
    }

    public function gradebookList(Request $request)
    {
        $data = $request->all();

        $active_session = Session::where('status', 1)->first();

        $exam_wise_student_list = Gradebook::where(['class_id' => $data['class_id'], 'section_id' => $data['section_id'], 'exam_category_id' => $data['exam_category_id'], 'school_id' => auth()->user()->school_id, 'session_id' => $active_session->id])->get();
        echo view('teacher.gradebook.list', ['exam_wise_student_list' => $exam_wise_student_list, 'class_id' => $data['class_id'], 'section_id' => $data['section_id'], 'exam_category_id' => $data['exam_category_id'], 'school_id' => auth()->user()->school_id, 'session_id' => $active_session->id]);
    }

    public function subjectWiseMarks(Request $request, $student_id = "")
    {
        $data = $request->all();

        $active_session = Session::where('status', 1)->first();

        $subject_wise_mark_list = Gradebook::where(['class_id' => $data['class_id'], 'section_id' => $data['section_id'], 'exam_category_id' => $data['exam_category_id'], 'student_id' => $student_id, 'school_id' => auth()->user()->school_id, 'session_id' => $active_session->id])->first();
        
        echo view('teacher.gradebook.subject_marks', ['subject_wise_mark_list' => $subject_wise_mark_list]);
    }

    public function list_of_syllabus(Request $request)
    {
        $data=$request->all();
        $permissions=TeacherPermission::where('teacher_id',auth()->user()->id)->select('class_id')->distinct()->get()->toArray();
        $permitted_classes=array();

        foreach ($permissions  as  $key => $distinct_class) {

            $class_details = Classes::where('id', $distinct_class['class_id'])->first()->toArray();
            $permitted_classes[$key] = $class_details;
        }


        return view('teacher.syllabus.index', ['permitted_classes' => $permitted_classes]);
    }

    public function class_wise_section_for_syllabus(Request $request)
    {
        $data=$request->all();
        $permissions=TeacherPermission::where('class_id',$data['classId'])->where('teacher_id',auth()->user()->id)->get()->toArray();
        $permitted_sections=array();

        foreach ($permissions as $key => $distinct_section) {


            $section_details = Section::where('id', $distinct_section['section_id'])->first()->toArray();
            $permitted_sections[$key] = $section_details;
        }

        $options = '<option value="">' . 'Select a section' . '</option>';
        foreach ($permitted_sections as $section) :
            $options .= '<option value="' . $section['id'] . '">' . $section['name'] . '</option>';
        endforeach;
        echo $options;
    }

    public function syllabus_details(Request $request)
    {
        $data = $request->all();
        $syllabuses = Syllabus::where('class_id', $data['class_id'])
            ->where('section_id', $data['section_id'])
            ->where('school_id', auth()->user()->school_id)
            ->get()->toArray();




        return view('teacher.syllabus.list', ['syllabuses' => $syllabuses]);
    }

    public function show_syllabus_modal(Request $request)
    {
        $data = $request->all();

        $permissions=TeacherPermission::where('teacher_id',auth()->user()->id)->select('class_id')->distinct()->get()->toArray();
        $classes=array();

        foreach ($permissions  as  $key => $distinct_class) {
            $class_details = Classes::where('id', $distinct_class['class_id'])->first()->toArray();
            $classes[$key] = $class_details;
        }

        return view('teacher.syllabus.create', ['classes' => $classes]);
    }
    public function show_syllabus_modal_post(Request $request)
    {
        $data = $request->all();

        $active_session = Session::where('status', 1)->first();

        $file = $data['syllabus_file'];

        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file

            $file->move(public_path('assets/uploads/syllabus/'), $filename);

            $filepath = asset('public/assets/uploads/syllabus/' . $filename);
        }

        Syllabus::create([
            'title' => $data['title'],
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'subject_id' => $data['subject_id'],
            'file' => $filename,
            'school_id' => auth()->user()->school_id,
            'session_id' => $active_session->id,
        ]);

        return redirect()->back()->with('message', 'You have successfully create a syllabus.');
    }

    public function syllabusDelete($id = '')
    {
        $syllabus = Syllabus::find($id);
        $syllabus->delete();
        return redirect()->back()->with('message', 'You have successfully delete syllabus.');
    }

    function profile(){
        return view('teacher.profile.view');
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
        
        return redirect(route('teacher.profile'))->with('message', get_phrase('Profile info updated successfully'));
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

            return redirect(route('teacher.password', 'edit'))->with('message', get_phrase('Password changed successfully'));
        }

        return view('teacher.profile.password');
    }
}
