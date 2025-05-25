<?php 
namespace App\Http\Controllers\Auth;

use App\Repository\UserRepository;
use App\Http\Controllers\Controller;
use Careminate\Http\Responses\Response;
use Careminate\Authentication\SessionAuthentication;

class LoginController extends Controller
{
    public function __construct(
        private SessionAuthentication $auth,
        private UserRepository $userRepository) {}
    
    public function loginForm(): Response
    {
        return view('auth/login.html.twig');
    }

   public function login(): Response
    {
        // Attempt to authenticate the user using a security component (bool)
        // create a session for the user
        $userIsAuthenticated = $this->auth->authenticate($this->request->input('email'),$this->request->input('password'));
       dd($userIsAuthenticated);
       
        // If successful, retrieve the user

        // Redirect the user to intended location
        return redirect('/');
    }
}