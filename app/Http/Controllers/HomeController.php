<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Gate;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $query="select format(IFNULL(sum(tra.valor_tra),0),2,'de_DE') as valor_tra
        //   from ponto_venda as pv, operacao as ope, transacao as tra
        //   where pv.dtexc_pv is null and ope.dtexc_ope is null and ope.dtfinali_ope is not null and tra.dtexc_tra is null 
        //   and ope.codigo_ope = tra.codigo_ope
        //   and pv.codigo_pv = ope.codigo_pv 
        //   and DATE(ope.dtfinali_ope)=$data_pesq
        //   and ope.codigo_ot = 5 
        //   and pv.codigo_emp = $codigo_emp";


    if( Gate::denies('isAdmin') )
        return redirect()->route('financeiro');

        return view('home');
    }


    public function showChangePassword(){
        return view('auth.passwords.change');
    }

    public function changePassword(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth()->user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Sua senha atual não corresponde à senha fornecida. Por favor, tente novamente.");
        }
 
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","Nova senha não pode ser igual à sua senha atual. Escolha uma senha diferente.");
        }
 
        $this->validate($request, [
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
 
        //Change Password
        $user = Auth()->user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
 
        return redirect()->back()->with("success","Senha alterada com sucesso!");
 
    }

}
