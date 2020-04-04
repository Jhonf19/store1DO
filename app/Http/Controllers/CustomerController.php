<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Customer;
use App\Credit;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customers.index');
    }

    public function getCustomers(){
        $customers = Customer::all();
        
        return $customers;
    }

    public function searchCustomers(Request $request){
        $customers = DB::table('customers')->select('*')->where('name','like','%'.$request->get('word').'%')->get();
        return $customers;
    }

    public function getCustomerInfo($id){
        $customer = Customer::find($id);
        $credit = Credit::where('customer',$id)->first();
        return [ 'customer' => $customer, 'credit' => $credit];
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = new Customer();
            $customer->document = $request->get('document');
            $customer->name = $request->get('name');
            $customer->last_name = $request->get('last_name');
            $customer->save();
            return 'Cliente Guardado';
        // return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $customer = Customer::find($id);
        return view('customers.show')->with(compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->document = $request->get('document');
            $customer->name = $request->get('name');
            $customer->last_name = $request->get('last_name');
            $customer->save();
            return 'Información Editada';

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        return view('customers.index');
    }
}
