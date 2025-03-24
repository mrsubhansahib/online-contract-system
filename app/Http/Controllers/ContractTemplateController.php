<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContractTemplate;
use App\Models\User;
use Illuminate\Http\Request;

class ContractTemplateController extends Controller
{

    public function index()
    {
        $templates = ContractTemplate::latest()->get();
        $categories = Category::get();
        return view('contract-templates.index', compact('templates', 'categories'));
    }
    public function add()
    {
        $categories = Category::get();
        $users = User::where('id', '!=', auth()->user()->id)->get();
        $selectUsers = [...$users, auth()->user()];
        return view('contract-templates.add', compact('selectUsers', 'categories'));
    }
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'details' => 'required',
            'category' => 'required',
        ], [
            'details.required' => "Please Save Your Form Data"
        ]);
        $data = json_decode($request->details);
        if ($request->category == "other") {
            ContractTemplate::create([
                'title' => $request->title,
                'form_data' => $data,
                'category_id' => null,
                'status' => 'activated'
            ]);
        } else {
            ContractTemplate::create([
                'title' => $request->title,
                'form_data' => $data,
                'category_id' => $request->category,
                'status' => 'activated'
            ]);
        }

        $templates = ContractTemplate::latest()->get();
        $categories = Category::get();
        return redirect()->route('template')->with('success', 'Contract Template Created Successfully', compact('templates', 'categories'));
    }
    public function edit($id)
    {
        $template = ContractTemplate::findOrFail($id);
        $template->form_data = json_encode($template->form_data);
        $users = User::where('id', '!=', auth()->user()->id)->get();
        $selectUsers = [...$users, auth()->user()];
        $categories = Category::get();
        return view('contract-templates.edit', compact('template',  'categories', 'selectUsers'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'details' => 'required',
            'category' => 'required'
        ]);
        $data = json_decode($request->details);
        if ($request->category == "other") {
            ContractTemplate::find($request->id)->update([
                'title' => $request->title,
                'form_data' => $data,
                'category_id' => null
            ]);
        }else{
            ContractTemplate::find($request->id)->update([
                'title' => $request->title,
                'form_data' => $data,
                'category_id' => $request->category
            ]);
        }
        $templates = ContractTemplate::latest()->get();
        $categories = Category::get();
        return redirect()->route('template')->with('success', 'Contract Template Updated Successfully', compact('templates', 'categories'));
    }

    public function delete($id)
    {
        ContractTemplate::findOrFail($id)->delete();
        return back()->with('danger', 'Contract Template Deleted !!!');
    }
    public function template($id)
    {
        // return json_encode( ContractTemplate::findOrFail($id)->form_data );
        return ContractTemplate::findOrFail($id)->form_data;
    }
}
