<?php

namespace App\Http\Controllers;

use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Models\Employee;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    protected $tokenName = 'employee_token';
    protected $scopeName = ['employees'];
    protected $authGuard = 'employees';

    public function register(Request $request)
    {

        $rules = array(
            "name" => ['required', 'max:50'],
            'email' => ['required', Config::get('constants.emailValidation'), 'unique:employees', 'max:50'],
            'password' => ['required', Config::get('constants.passwordValidation')],
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return errorResponse(Config::get('constants.errorCodes.wrong_parameters'), $validator->errors());
        }

        $employee = Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),

        ]);

        if ($employee) {
            return successResponse($employee);
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

    public function listQuestions(Request $request)
    {
        $rules = array(
            'question_status' => ['nullable'],
            'customer_name' => ['nullable'],
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return errorResponse(Config::get('constants.errorCodes.wrong_parameters'), $validator->errors());
        }

        $customerName = $request->customer_name;
        $questionStatus = $request->question_status;

        $questions = Question::WhereHas('customer', function ($query) use ($customerName) {
            if (isset($customerName) && !empty($customerName)) {
                return $query->where('name', 'like', '%' . $customerName . '%');

            }
        })->with('customer')->with('employee')->where(function ($query) use ($questionStatus) {
            if (isset($questionStatus) && !empty($questionStatus)) {
                $query->where('status', $questionStatus);
            }

        })->get();


        if (($questions))
            return successResponse($questions);

    }

    public function ChangeQuestionStatus(Request $request)
    {

        $rules = array(
            //statuses should be 2 or 3 or 4 so hackers can not crush the system by adding other statuses
            'question_status' => ['required', 'in:2,3,4'],
            'question_id' => ['required'],
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return errorResponse(Config::get('constants.errorCodes.wrong_parameters'), $validator->errors());
        }
        $questionId = $request->question_id;
        $question = Question::where('id', $questionId)->get()->first();
        if (empty($question)) {
            return notFoundResponse();
        } else {
            $questionStatus = $request->question_status;
            $question->status = $questionStatus;
            $question->save();
            return successResponse();
        }

    }

    public function sendReply(Request $request)
    {
        $rules = array(

            'reply' => ['required'],
            'question_id' => ['required'],
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return errorResponse(Config::get('constants.errorCodes.wrong_parameters'), $validator->errors());
        }

        $questionId = $request->question_id;
        $reply = $request->reply;

        $question = Question::with('customer')->where([['id', $questionId],['employee_id', null]])->where('status',1)->orWhere('status',3)->get()->first();

        if(empty($question))
        {
            return errorResponse(Config::get('constants.errorCodes.can_not_send_answer'));
        }

        $question->reply = $reply;
        $question->employee_id = $request->user()->id;
        $question->save();
        MailController::sendQuestionAnsweredEmail($question->customer->email,$question->customer->name,$question->text,$question->reply);
        return successResponse();

    }
}
