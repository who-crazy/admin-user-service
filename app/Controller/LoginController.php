<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Package\HyperfPackage\Core\Verify;
use Package\HyperfPackage\Core\Code;
use Phper666\JWTAuth\JWT;
use function Package\HyperfPackage\Helpers\Password\eq_password;
use Package\HyperfPackage\Core\AbstractController;

class LoginController extends AbstractController
{
    /**
     * @Inject
     * @var JWT
     */
    protected $jwt;

    /**
     * 登录
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function login(RequestInterface $request, ResponseInterface $response)
    {
        $param = Verify::requestParam([
            ['account', ''],
            ['password', ''],
        ], $this->request);

        $validator = $this->validationFactory->make(
            $param,
            [
                'account' => 'required',
                'password' => 'required',
            ],
            [
                'account.required' => '账号不能为空',
                'password.required' => '密码不能为空',
            ]
        );
        if ($validator->fails()) {
            return $this->responseCore->error(Code::PARAMS_ERROR, $validator->errors()->first());
        }


        //查询用户信息
        $adminUser = \App\Model\AdminUser::where('is_on', 1)->where('account', '=', $param['account'])->first();
        if (!$adminUser) {
            return $this->responseCore->error(Code::USER_NOT_FOUND);
        }
        //判断用户名密码
        if (!eq_password($adminUser->password, md5($param['password']), $adminUser->salt)) {
            return $this->responseCore->error(Code::INCORRECT_PASSWORD);
        }

        //创建token
        $token = (string) $this->jwt->getToken(['admin_id' => $adminUser->id]);
        return $this->responseCore->success(['token' => $token, 'admin_name' => $adminUser->name,'expires' => $this->jwt->getTTL()]);
    }

    /**
     * logout
     */
    public function logout()
    {

    }
}
