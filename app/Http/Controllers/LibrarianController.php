<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Session;
use App\Models\Classes;
use App\Models\user;

class LibrarianController extends Controller
{
    public function librarianDashboard()
    {
        return view('librarian.dashboard');
    }

    /**
     * Show the book list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function bookList()
    {
        $books = Book::get()->where('school_id', auth()->user()->school_id);
        return view('librarian.book.list', ['books' => $books]);
    }

    public function createBook()
    {
        return view('librarian.book.create');
    }

    public function bookCreate(Request $request)
    {
        $data = $request->all();

        $duplicate_book_check = Book::get()->where('name', $data['name']);

        if(count($duplicate_book_check) == 0) {

            $active_session = Session::where('status', 1)->first();

            $data['school_id'] = auth()->user()->school_id;
            $data['session_id'] = $active_session->id;
            $data['timestamp'] = strtotime(date('d-M-Y'));

            Book::create($data);

            return redirect()->back()->with('message','You have successfully create a book.');

        } else {
            return back()
            ->with('error','Sorry this book already exists');
        }
    }

    public function editBook($id="")
    {
        $book_details = Book::find($id);
        return view('librarian.book.edit', ['book_details' => $book_details]);
    }

    public function bookUpdate(Request $request, $id='')
    {
        $data = $request->all();

        $duplicate_book_check = Book::get()->where('name', $data['name']);

        if(count($duplicate_book_check) == 0) {
            Book::where('id', $id)->update([
                'name' => $data['name'],
                'author' => $data['author'],
                'copies' => $data['copies'],
                'timestamp' => strtotime(date('d-M-Y')),
            ]);
            
            return redirect()->back()->with('message','You have successfully update book.');
        } else {
            return back()
            ->with('error','Sorry this book already exists');
        }
    }

    public function bookDelete($id)
    {
        $book = Book::find($id);
        $book->delete();
        return redirect()->back()->with('message','You have successfully delete book.');
    }


    /**
     * Show the book list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function bookIssueList(Request $request)
    {
        $active_session = Session::where('status', 1)->first();

        if(count($request->all()) > 0) {

            $data = $request->all();

            $date = explode('-', $data['eDateRange']);
            $date_from = strtotime($date[0].' 00:00:00');
            $date_to  = strtotime($date[1].' 23:59:59');
            $book_issues = BookIssue::where('issue_date', '>=', $date_from)
                                ->where('issue_date', '<=', $date_to)
                                ->where('school_id', auth()->user()->school_id)
                                ->where('session_id', $active_session->id)
                                ->get();

            return view('librarian.book_issue.book_issue', ['book_issues' => $book_issues, 'date_from' => $date_from, 'date_to' => $date_to]);
        } else {

            $date_from = strtotime(date('d-m-Y',strtotime('first day of this month')).' 00:00:00');
            $date_to = strtotime(date('d-m-Y',strtotime('last day of this month')).' 23:59:59');
            $book_issues = BookIssue::where('issue_date', '>=', $date_from)
                                ->where('issue_date', '<=', $date_to)
                                ->where('school_id', auth()->user()->school_id)
                                ->where('session_id', $active_session->id)
                                ->get();

            return view('librarian.book_issue.book_issue', ['book_issues' => $book_issues, 'date_from' => $date_from, 'date_to' => $date_to]);

        }
    }

    public function createBookIssue()
    {
        $classes = Classes::get()->where('school_id', auth()->user()->school_id);
        $books = Book::get()->where('school_id', auth()->user()->school_id);
        return view('librarian.book_issue.create', ['classes' => $classes, 'books' => $books]);
    }

    public function bookIssueCreate(Request $request)
    {
        $data = $request->all();

        $active_session = Session::where('status', 1)->first();

        $data['status'] = 0;
        $data['issue_date'] = strtotime($data['issue_date']);
        $data['school_id'] = auth()->user()->school_id;
        $data['session_id'] = $active_session->id;
        $data['timestamp'] = strtotime(date('d-M-Y'));

        BookIssue::create($data);

        return redirect()->back()->with('message','You have successfully issued a book.');
    }

    public function editBookIssue($id="")
    {
        $book_issue_details = BookIssue::find($id);
        $classes = Classes::get()->where('school_id', auth()->user()->school_id);
        $books = Book::get()->where('school_id', auth()->user()->school_id);
        return view('librarian.book_issue.edit', ['book_issue_details' => $book_issue_details, 'classes' => $classes, 'books' => $books]);
    }

    public function bookIssueUpdate(Request $request, $id="")
    {
        $data = $request->all();

        $active_session = Session::where('status', 1)->first();

        $data['issue_date'] = strtotime($data['issue_date']);
        $data['school_id'] = auth()->user()->school_id;
        $data['session_id'] = $active_session->id;
        $data['timestamp'] = strtotime(date('d-M-Y'));

        unset($data['_token']);

        BookIssue::where('id', $id)->update($data);

        return redirect()->back()->with('message','Updated successfully.');
    }

    public function bookIssueReturn($id)
    {
        BookIssue::where('id', $id)->update([
            'status' => 1,
            'timestamp' => strtotime(date('d-M-Y')),
        ]);

        return redirect()->back()->with('message','Return successfully.');
    }

    public function bookIssueDelete($id)
    {
        $book_issue = BookIssue::find($id);
        $book_issue->delete();
        return redirect()->back()->with('message','You have successfully delete a issued book.');
    }

    function profile(){
        return view('librarian.profile.view');
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
        
        return redirect(route('librarian.profile'))->with('message', get_phrase('Profile info updated successfully'));
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

            return redirect(route('librarian.password', 'edit'))->with('message', get_phrase('Password changed successfully'));
        }

        return view('librarian.profile.password');
    }

}
