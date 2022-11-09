<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\SalesOrder;

class AdminDashboardController extends Controller
{
    public function index(){
        $pageTitle = 'Dashboard';
        $vendor = User::whereNull('deleted_at')->where([['status','ACTIVE'],['role','VENDOR']])->get()->count();
        $supplier = User::whereNull('deleted_at')->where([['status','ACTIVE'],['role','SUPPLIER']])->get()->count();
        $driver = User::whereNull('deleted_at')->where([['status','ACTIVE'],['role','DRIVER']])->get()->count();
        $salesOrder = SalesOrder::whereNull('deleted_at')->where('order_status',SalesOrder::ORDERPLACED)->get()->sum('final_total');
        // $salesOrder = SalesOrder::whereNull('deleted_at')->where('order_status','PLACED')->get()->sum('final_total');
        $d = SalesOrder::select(\DB::raw('sum(final_total) as `data`'),
                                \DB::raw('count(id) as `count`'),
                                \DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),
                                \DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month'))
                                ->orderBy('created_at','asc')
                                ->groupby('year','month')
                                ->get();
        
       // $d = array(1,59 , 80, 81, 56, 55, 40 , 40, 40, 40, 400);
       
        $x_axies=array();
        $y_axies=array();        
        $d_xline = $d->toArray();
        // dd($d_xline);
         foreach($d_xline as $key =>$m)
          {
           $x_axies[]=$m['month'];
           $y_axies[]= bcdiv($m['data'],1,2); 
          }  

        return view('admin.dashboard.index', compact( 'pageTitle','vendor','supplier','driver','salesOrder','x_axies','y_axies'));
    }
}
