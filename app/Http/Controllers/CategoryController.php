<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contract;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){

        $categories = Category::get();
        return view('category.index',compact('categories'));
        
    }
    public function add(){
        return view('category.add');
    }
    public function store( Request $request ){
        $request->validate([
            'name'=>'required | unique:categories,name',
        ]);
        // return $request->name;
        Category::create([
            'name'=>$request->name,
            'added_by'=>auth()->user()->id,
        ]);
        return redirect('/contracts/category')->with('success',"Category Created Successfully");
    }
    public function edit( $id ){
        $category = Category::find($id);
        return view('category.edit',compact('category'));
    }
    public function update( Request $request ){
        $request->validate([
            'name'=>'required|unique:categories,name,'.$request->id,
        ]);

        Category::find( $request->id )->update([
            'name'=>$request->name,
        ]);
        return redirect('/contracts/category')->with('success',"Category Updated Successfully");
    }
    public function delete( $id ){
        Category::find( $id )->delete();
        return back()->with('danger',"Category Deleted Successfully");
    }
    public function show( $category ){

        $contracts = Contract::with(
            'contract_users:id,user_id,contract_id,status',
            'contract_users.user:id,name'
        )
        ->where('category_id',$category)
        ->latest()->get();
        return view('contracts.index',compact('contracts'));
    }
}
