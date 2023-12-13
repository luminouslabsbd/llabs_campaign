<?php

namespace Luminouslabs\Installer\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class RocketChatController extends Controller
{
    public function index(){

        $rocketData = DB::table('rocket_chat')->first();
        return view('admin.rocket_chat.index',compact('rocketData'));
    }

    public function edit($lang='en-us' ,$id){
        $rocketData = DB::table('rocket_chat')->where('id',$id)->first();
        $languages['all'] = [];
        if($rocketData != null){
            return view('admin.rocket_chat.form',compact('rocketData','languages'));
        }
    }

    public function add(){

        $rocketData = DB::table('rocket_chat')->first();
        $languages['all'] = [];
        return view('admin.rocket_chat.form',compact('rocketData','languages'));
    }

    public function storeUpdate(Request $request){
        
        $existingRecord = DB::table('rocket_chat')->where('id', $request->id)->first();
        $data = [
            'api_url' => $request->api_url,
            'api_title' => $request->api_title,
            'api_token' => Crypt::encryptString($request->api_token),
            'x_user_id' => $request->x_user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if($existingRecord){
            DB::table('rocket_chat')->where('id', $request->id)->update($data);
        }else{
            DB::table('rocket_chat')->insert($data);
        }
        $toast = "Data Update Successful";
        $languages['all'] = [];
        return redirect()->route('admin.rocket_chat'); 

    }


}
