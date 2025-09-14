<?php

namespace app\admin\middleware;

use app\admin\model\Token as TokenModel;
use app\Base;
use think\App;
use app\Tool;

class token extends Base
{

      /**
       * Token验证器
       */
      public function handle($request, \Closure $next)
      {
            $isNotHave = array_merge(explode(",", env("LOGIN_FUNCTION")),explode(",", env("NOTVER_FUNCTION")), ["IsValidCurrentToken"]);
            foreach ($isNotHave as $k => $v) {
                  //?排除登录和其他函数
                  if (Tool::getCurrentFunction() == $v)
                        return $next($request);
            }
        
            $token = $request->header("Token") ?? "";
            //*万能Token
            if ($token == env("ADMIN_TOKEN"))
                  return $next($request);

            try {
                  if (!$token)
                        return $this->Warning("请携带Token!");


                  $isTokenTimeOut = TokenModel::IsTokenTimeOut($token);

                  if (!$isTokenTimeOut)
                        return $this->LoginTimeOut();


                  $isVerifyTokenExpire = TokenModel::IsVerifyTokenExpire($token);
                  if (!$isVerifyTokenExpire)
                        return $this->Warning("Token过期,请重新登录!");
            } catch (\Exception $e) {
                  // dump($e);
                  return $this->ApiError($e->getMessage());
            }
            return $next($request);
      }
}
