<?php  declare(strict_types=1); 
namespace App\Http\Controllers\Dashboard;

use App\Widget\Widget;
use App\Http\Controllers\Controller;
use Careminate\Http\Responses\Response;
use Careminate\Authentication\SessionAuthentication;

class DashboardController extends Controller
{
    public function __construct(private Widget $widget, private SessionAuthentication $auth){}

    public function index(): Response
    {
          try {
            // Attempt to retrieve the logged-in user
            $user = $this->auth->getUser();
            // dd($user);
        } catch (\LogicException $e) {
            // Handle the case when there is no logged-in user
            return redirect('/login');
        }
        return view('admin/dashboard.html.twig',compact('user'));
    }
}