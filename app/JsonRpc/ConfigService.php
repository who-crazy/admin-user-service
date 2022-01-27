<?php


namespace App\JsonRpc;

use Hyperf\Di\Annotation\Inject;
use Hyperf\RpcServer\Annotation\RpcService;
use Taoran\HyperfPackage\Core\AbstractController;
use function Taoran\HyperfPackage\Helpers\set_save_data;

/**
 * @RpcService(name="ConfigService", server="jsonrpc-http", protocol="jsonrpc-http", publishTo="consul")
 */
class ConfigService extends AbstractController implements ConfigServiceInterface
{

    public function getAbout()
    {
        $code = 'about_us';
        $data = \App\Model\Config::getOne(['id', 'code', 'desc', 'value'], function ($query) use ($code) {
            $query->where('code', $code);
        });

        $data->value = htmlspecialchars_decode($data->value);

        return $data;
    }

    public function updateAbout($params)
    {
        $validator = $this->validationFactory->make(
            $params,
            [
                'value' => 'required',
            ],
            [
                'value.required' => '内容不能为空！'
            ]
        );
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $code = 'about_us';
        $data = \App\Model\Config::getOne(['id', 'code', 'desc', 'value'], function ($query) use ($code) {
            $query->where('code', $code);
        });

        if (!$data) {
            throw new \Exception("配置不存在！");
        }

        set_save_data($data, [
            'value' => $params['value']
        ]);
        $data->save();

        return true;
    }


}