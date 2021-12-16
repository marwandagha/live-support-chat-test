<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    protected $tokenName = 'customer_token';
    protected $scopeName = ['customers'];
    protected $authGuard = 'customers';

    public function register(Request $request)
    {

        $rules = array(
            "name" => ['required', 'max:50'],
            'email' => ['required', Config::get('constants.emailValidation'), 'unique:customers', 'max:50'],
            'password' => ['required', Config::get('constants.passwordValidation')],
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return errorResponse(Config::get('constants.errorCodes.wrong_parameters'), $validator->errors());
        }

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),

        ]);

        if ($customer) {
            return successResponse($customer);
        }

    }

    public function login(Request $request)
    {

        $rules = array(
            'email' => ['required', Config::get('constants.emailValidation'), 'max:50'],
            "password" => ['required'],
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return errorResponse(Config::get('constants.errorCodes.wrong_parameters'), $validator->errors());
        }

        $emailBasedCreds = [
            'email' => $request->email,
            'password' => $request->password
        ];

        auth($this->authGuard)->attempt($emailBasedCreds) ? $isAuth = true : $isAuth = false;

        if ($isAuth) {
            //generate token
            $token = auth($this->authGuard)->user()->createToken($this->tokenName, $this->scopeName)->accessToken;
            return successResponse(null, $token);

        } else {
            return errorResponse(Config::get('constants.errorCodes.login_failed'));
        }
    }

    public function listMyQuestions(Request $request)
    {
        //get all customer question where status is not spam

        $questions = Question::where([['status', '<>', 4], ['customer_id', $request->user()->id]])->get();
        return successResponse($questions);
    }

    public function sendQuestion(Request $request)
    {
        $rules = array(
            "text" => ['required'],
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return errorResponse(Config::get('constants.errorCodes.wrong_parameters'), $validator->errors());
        }

        $question = Question::create([
            'text' => $request->text,
            'customer_id' => $request->user()->id,
        ]);

        if ($question)
            return successResponse($question);

    }

}
