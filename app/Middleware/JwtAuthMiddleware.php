<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Middleware;

use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\Context;
use Phper666\JwtAuth\Jwt;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JwtAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var HttpResponse
     */
    protected $response;

    protected $prefix = 'Bearer';

    protected $jwt;

    public function __construct(HttpResponse $response, Jwt $jwt)
    {
        $this->response = $response;
        $this->jwt = $jwt;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $isValidToken = false;
            // 根据具体业务判断逻辑走向，这里假设用户携带的token有效
            $token = $request->getHeader('Authorization')[0] ?? '';
            if (strlen($token) > 0) {
                $token = ucfirst($token);
                $arr = explode($this->prefix . ' ', $token);
                $token = $arr[1] ?? '';
                if (strlen($token) > 0 && $this->jwt->checkToken()) {
                    $isValidToken = true;
                }
            } else {
                return $this->response->json(['code' => 401, 'message' => '请登陆！', 'data' => []]);
            }
            if ($isValidToken) {
                $request = $request->withAttribute('uid', $this->jwt->getParserData()['user_id']);
                $request = $request->withAttribute('group_id', $this->jwt->getParserData()['group_id']);
                Context::set(ServerRequestInterface::class, $request);
                return $handler->handle($request);
            }
            return $this->response->json(['code' => 401, 'message' => '登陆失败！', 'data' => []]);
        } catch (\Exception $e) {
            return $this->response->json(['code' => 1010, 'message' => $e->getMessage(), 'data' => []]);
        }
    }
}
