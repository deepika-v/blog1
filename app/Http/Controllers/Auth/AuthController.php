<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

     public function getLoginValidationRules()
    {
        return [
            'grant_type'    => 'required',
            'client_id'     => 'required',
            'client_secret' => 'required',
            'username'      => 'required',
            'password'      => 'required',
            'scope'         => 'required',
        ];
    }

    public function login(Request $request)
    {
       $clientData=DB::table('oauth_clients')->where('name', 'api')->first();
        $userScope=$this->checkUserScope(Input::get('username'));
        Input::merge([
                'client_id'     => "".$clientData->id,
                'client_secret' => "".$clientData->secret,
                'scope'         => $userScope
            ]);
            $credentials = $request->only(['grant_type', 'client_id', 'client_secret', 'username', 'password','scope']);

            $credentials["client_id"]="".$clientData->id;
            $credentials["client_secret"]="".$clientData->secret;
            $validationRules = $this->getLoginValidationRules();
            $this->validateOrFail($credentials, $validationRules);
            try {
                if (! $accessToken = Authorizer::issueAccessToken()) {
                    return $this->response->errorUnauthorized();
                }
            }
            catch (\League\OAuth2\Server\Exception\OAuthException $e)
            {
                throw $e;
                return $this->response->error('could_not_create_token', 500);
            }
            $accessToken["groups"][]=$userScope;
            $request->headers->set('Authorization','Bearer '.$accessToken['access_token']);
            Authorizer::validateAccessToken();
            $userId = Authorizer::getResourceOwnerId();
            $userType=User::find($userId)->id;
            $accessToken['userable_id']=$userType;
            $accessToken['userId']=$userId;
            
            return response()->json(compact('accessToken'));
    }

    public function getUserIdByEmail($email)
    {
        try
        {
            $user=User::where('email',$email)->firstOrFail();
            return $user;
        }
        catch(ModelNotFoundException $mnfex)
        {
            return $this->response->error('User Does Not Exists !', 404);
        }
        catch(\Exception $ex)
        {
            return $this->response->error('Error Occurred !', 500);
        }
    }

    public function checkUserScope($username)
    {
        try
        {
            if((User::where('username', '=', $username)->exists()))
            {
               $userId=User::where('username', '=', $username)->pluck('id');
//                $user=User::find($userId);
//                $groups=$user->groups;
//
//                return $groups[0]->name;
                $user=DB::table('users')
                    ->select('UserRole')
                    ->join('user_role','user_role.UserRoleID','=','users.UserRoleID')
                    ->where('users.id','=',$userId)
                    ->get();

                //dd($user);
                return $user[0]->UserRole;
            }
            else
            {
                return "empty";
            }
        }
        catch(\Exception $ex)
        {
            return $this->response->error('Error Occurred : '.$ex->getMessage(), 404);
        }


    }
}
