<?php
namespace app\admin\controller;
use app\BaseController;
use PclZip;
use ric\captcha\facade\CaptchaApi;

class Tool extends BaseController
{
    /**
    * 日志下载
    */
    public function getLog()
    {   
        if (!file_exists(public_path() . 'tempfile')) {
            mkdir(public_path() . 'tempfile');
        }        
        $filepath = public_path() . 'tempfile' . DS . 'log_' . date('Ymd') . '.zip';
        $logszip = new PclZip($filepath);
        $zipList = $logszip->create(runtime_path() . 'log' . DS);  
        if ($zipList == 0) {
            $this->message('error', '日志文件压缩失败:' . $logszip->errorInfo(true));
        }
        ob_end_clean();
        header("Content-Type: application/force-download;"); 
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($filepath));
        header("Content-Disposition: attachment; filename=" . 'log_' . date('Ymd') . '.zip'); 
        header("Expires: 0");
        header("Cache-control: private");
        header("Pragma: no-cache"); 
        readfile($filepath);         
        exit ;
    }

    /**
     * 获取验证码
     */
    public function getCaptcha()
    {
        $data = CaptchaApi::create();
        return show(config('status.success'), '获取验证码成功！', $data);
    }


}
