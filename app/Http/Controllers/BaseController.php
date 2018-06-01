<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use DB;

ini_set('xdebug.max_nesting_level', 500);

class BaseController extends Controller
{
   /// use Helpers;

    public $limit;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->limit = ($request->get('limit') ? $request->get('limit') : config('mm.page_limit'));
    }

    
    protected function validateOrFail($data, $validationRules, $options=[])
    {
        if ($this->auth->user()) {
            $data['user_id'] = $this->auth->user()->id; // Get User id from User Resolver
        }

        $validator = app('validator')->make($data, $validationRules, $options);

        if ($validator->fails()) {
            $message = (isset($options['message']) ? $options['message']:'Could not process your request, following are the errors.');
            throw new ValidationHttpException($validator->errors()->all());
        }
    }

    protected function getAuthenticatedUserId()
    {
        if (null !== $this->auth->user() && isset($this->auth->user()->id)) {
            return $this->auth->user()->id;
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Unable to get authenticated user info.', 'Unable to get authenticated user info.');
        }
    }

    public function debugQueries()
    {
        if (app()->environment('local')) {
            DB::listen(function($sql, $bindings) {
                var_dump($sql);
                var_dump($bindings);
            });
        }
    }
}
