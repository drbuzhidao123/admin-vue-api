<?php


namespace app\admin\middleware;
class Before
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $key = "HkPIucnOnw8SXXLx";
        $iv = 'ABCDEF1234123412';
        $request->param = json_decode(openssl_decrypt(base64_decode($request->param("data")),"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv)); 
        return $next($request); 
    }
}
