<?php


namespace Package\HyperfPackage\Traits;

use Package\HyperfPackage\Traits\CallbackTrait;

/**
 * repository层，协助model处理
 *
 * Trait RepositoryTrait
 * @package App\Traits
 */
trait RepositoryTrait
{
    /**
     * 获取列表
     *
     * @param array $select
     * @param bool $is_all
     * @return \Hyperf\Contract\LengthAwarePaginatorInterface
     */
    public function getList($select = ['*'], $params, callable $where = null)
    {
        $orm = self::select($select)->where('is_del', 0);

        //执行callable
        CallbackTrait::callback($where, $orm);

        if (isset($params['is_all']) && $params['is_all'] == 1) {
            $list = $orm->get();
        } else {
            $list = $orm->paginate($params['page_limit'] ?? 20);
        }
        return $list;
    }

    /**
     * 根据id获取数据
     *
     * @param $id
     */
    public function getOneById($id, $select = ['*'])
    {
        $data = self::select($select)->where('is_del', 0)->find($id);
        if (!$data) {
            throw new \Exception("数据不存在！");
        }
        return $data;
    }

    /**
     * 获取单条数据
     *
     * @param array $select
     * @param string $where
     * @return \Hyperf\Database\Model\Model|\Hyperf\Database\Query\Builder|object|null
     */
    public function getOne($select = ['*'], callable $where = null)
    {
        $orm =  self::select($select)->where('is_del', 0);

        CallbackTrait::callback($where, $orm);

        return $orm->first();
    }
}