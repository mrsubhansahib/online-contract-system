<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\ContractNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        return view('users.index', compact('users'));
    }
    public function add()
    {
        return view('users.add');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'contact' => 'required',
            'role' => 'required',
        ]);
        $details = $request->only('name', 'email', 'contact', 'password', 'role');
        $details['password'] = Hash::make($details['password']);
        $user = User::create($details);
        $Data = [
            'title' => 'User Registration – Whitefield Estate Agents',
            'body' => 'You have successfully been registered with Whitefield Estate Agents to sign the agreement on your behalf or as a witness/guarantor. Please visit paperless.whitefieldestateagents.co.uk, to sign the form as soon as possible.'
        ];
        Mail::to($user->email)->send(new SendMail($Data));
        $role = 'Client';
        if ($request->role == 'admin') {
            $role = 'Admin';
        }
        ContractNotification::create([
            'content' => $request->name . ' is recently registered as ' . $role . '.',
            'user_id' => auth()->user()->id
        ]);
        $users = User::get();

        return redirect()->route('user')->with('success', 'User Created Successfully', compact('users'));
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $request->id,
            // 'password'=>'required',
            'contact' => 'required',
            'role' => 'required',
        ]);

        User::findOrFail($request->id)
            ->update(
                $request->only('name', 'email', 'contact', 'role')
            );


        if ($request->password) {
            User::findOrFail($request->id)->update([
                'password' => Hash::make($request->password)
            ]);
        }
        return redirect('/users')->with('success', 'User Updated Successfully');
    }

    public function delete($id)
    {

        $user = User::findOrFail($id);
        if ($user->role == 'admin') {
            // dd(1);
            return back()->with('danger', "Sorry You Can't Delete Admins.");
        } else if (!$user) {
            // dd(2    );
            return back()->with('danger', 'User Not Found !!!');
        } else {
            // dd(3);
            $user->delete();
            return back()->with('danger', 'User Record Deleted !!!');
        }
    }
    public function profile()
    {
        $id = auth()->user()->id;
        $user = User::select('name')->findOrFail($id);
        return view('users.profile', compact('user'));
    }
    public function update_profile(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        User::findOrFail(auth()->user()->id)->update($request->only('name'));
        dd('stop');
        if ($request->password) {
            User::findOrFail(auth()->user()->id)->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return back()->with('success', 'Profile Updated Successfully');
    }
}
