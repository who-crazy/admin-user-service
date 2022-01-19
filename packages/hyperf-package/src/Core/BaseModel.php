<?php
declare(strict_types=1);
namespace Package\HyperfPackage\Core;

use Hyperf\DbConnection\Model\Model;
class BaseModel extends Model {


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $prefix = 'la_';

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'default';

}