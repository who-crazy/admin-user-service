<?php


namespace App\JsonRpc;

use Hyperf\Di\Annotation\Inject;
use Hyperf\RpcServer\Annotation\RpcService;
use Phper666\JwtAuth\Jwt;
use Taoran\HyperfPackage\Core\AbstractController;
use function Taoran\HyperfPackage\Helpers\Password\eq_password;

/**
 * @RpcService(name="AdminUserService", server="jsonrpc-http", protocol="jsonrpc-http", publishTo="consul")
 */
class AdminUserService extends AbstractController implements AdminUserServiceInterface
{
    /**
     * @Inject()
     * @var Jwt
     */
    protected $jwt;

    public function login($params)
    {
        $validator = $this->validationFactory->make(
            $params,
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
            throw new \Exception($validator->errors()->first());
            //return $this->responseCore->error(Code::PARAMS_ERROR, $validator->errors()->first());
        }

        //查询用户信息
        $adminUser = \App\Model\AdminUser::where('is_on', 1)->where('account', '=', $params['account'])->first();
        if (!$adminUser) {
            throw new \Exception("管理员不存在！");
            //return $this->responseCore->error(Code::USER_NOT_FOUND);
        }
        //判断用户名密码
        if (!eq_password($adminUser->password, md5($params['password']), $adminUser->salt)) {
            throw new \Exception("账号或密码错误！");
            //return $this->responseCore->error(Code::INCORRECT_PASSWORD);
        }

        //创建token
        $token = (string) $this->jwt->getToken(['admin_id' => $adminUser->id]);
        return ['token' => $token, 'admin_name' => $adminUser->name,'expires' => $this->jwt->getTTL()];
    }
}