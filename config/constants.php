<?php

return [
    'dateTimeFormat'=> 'Y-m-d H:i:s',
    'dateTimeFormatEurope'=> 'm.d.Y H:i:s',
    'dateFormatEurope'=> 'm.d.Y',
    'dateFormat'=> 'Y-m-d',
    'emailValidation'=> 'regex:/^\w+([-+.\']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
    'passwordValidation'=> 'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&_-~])[A-Za-z\d@$!%*#?&_-~]{8,}$/',
    'errorCodes' => [
        'login_failed' => 600,
        'wrong_parameters' => 601,
        'can_not_send_answer'=>602
    ],
];

