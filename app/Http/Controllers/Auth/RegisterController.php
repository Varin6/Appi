<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AuthController;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthController $authcontroller)

    {
        $this->authcontroller = $authcontroller;
        $this->middleware('guest');
    }


    /**
     * Handle a registration request for the application.
     *
     *
     */
    public function register()
    {
        $this->validator(request(['name', 'email', 'password', 'password_confirmation']))->validate();

        $data = request(['name', 'email', 'password']);

        return $this->create($data);

        //return redirect()->route('login')
            //->with(['success' => 'Congratulations! your account is registered, you will shortly receive an email to activate your account.']);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function create(array $data)

    {

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $this->authcontroller->login();

    }
}
