<?php
/**********************************************
 * 프로젝트명   : 2nd-project
 * 디렉토리     : Controllers
 * 파일명       : HomeController.php
 * 이력         : v001 0615 BJ.Kwon new
 *                v002 
 **********************************************/
namespace App\Http\Controllers;

use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Route::get('/home/{id}', [HomeController::class, 'home'])->name('home');

    public function home($id)
    {
        $data = UserInfo::find($id);
        $date = Carbon::now()->format('Y-m-d');
        // return view('home')->with("id",$id);
        // var_dump($data);
        // exit;

        return view('home')->with("data",$data)->with("date",$date);
    }
}
