<?php


namespace App\Http\Controllers\Auth;

use Auth;

class Verifier {
    public function verify($username, $password)
    {
        \Log::debug("I am Inside Verifier username = " . $username . " and password = " . $password);

        if( is_numeric($username)){
            $credentials = [
                'phone'    => $username,
                'password' => $password,
            ];
        }
        /*if($username == 'admin' || $username == 'INDBA' || $username == 'USDBA' ||$username == 'MXDBA') {
            $credentials = [
                'email'    => $username,
                'password' => $password,
            ];
        }

        elseif (strpos($username, '@') !== false) {
            $credentials = [
                'email'    => $username,
                'password' => $password,
            ];
        }*/
        else{
            $credentials = [
                'email'    => $username,
                'password' => $password,
            ];
        }

        if (Auth::once($credentials)) {
            \Log::debug("I am Inside Verifier Function " . \Auth::user()->id);

            return Auth::user()->id;
        }

        return false;
    }
}