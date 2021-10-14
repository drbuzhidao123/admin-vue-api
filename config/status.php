<?php
// +----------------------------------------------------------------------
// | 业务状态码
// +----------------------------------------------------------------------
return [
    'success' => 200,
    'error'   => -1,
    'not_login' => -2,
    'user_is_register' => -3,
    'action_not_found' => -4,
    'controller_not_found' => -5,
    'token_out' => 40001, //token过期或没token
    'nosku' => -6,
    'not_ajax'=>4002,
];
