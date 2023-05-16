<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::orderBy('id','desc')->paginate(5);
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $name = $request->name;
       $email = $request->email;
       $address = $request->address;
   
       $validator=Validator::make([
           'name' => $name,
           'email' => $email,
           'address' => $address
        ],
        [
            'name' => 'required|max:25',
            'qmail' => 'required|email|unique:users',
            'address' => 'required',
        ]
    );
    if($validator->fails()){
       $messages = $validator->messages();
       return redirect()->route('companies.create')->with(['messages'=> $messages]);
    }else{
        Company::create($request->post());
        return redirect()->route('companies.index')->with(['success', 'Company has been created successfully.']);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|max:25',
            'qmail' => 'required|email|unique:users',
            'address' => 'required',
        ]);

        $company->fill($request->post())->save();

        return redirect()->route('companies.index')->with('success', 'Company has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company has been deleted successfully.');
    }

    public function error()
    {
        return view('my-view')->with('errors', $validator->errors());
        return redirect()->route('my-route')->withErrors($validator);
    }
}
