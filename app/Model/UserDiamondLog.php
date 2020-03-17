<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Db;

/**
 * @property int $id
 * @property int $type
 * @property int $key_id
 * @property int $user_id
 * @property int $is_double
 * @property int $sum_method
 * @property string $did
 * @property int $diamond_count
 * @property string $extra
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class UserDiamondLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_diamond_log';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'type' => 'integer', 'key_id' => 'integer', 'user_id' => 'integer', 'is_double' => 'integer', 'sum_method' => 'integer', 'diamond_count' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    private static function UserDiamondLogQuery()
    {
        return Db::table('user_diamond_log as u_dl')
            ->select([
                'u_dl.id',
                'u_dl.number',
                'u_dl.type',
                'u_dl.key_id',
                'u_dl.user_id',
                'u_dl.is_double',
                'u_dl.sum_method',
                'u_dl.did',
                'u_dl.diamond_count',
                'u_dl.extra',
                'u_dl.created_at',
                'u_dl.updated_at',
                'u.phone',
            ])
            ->leftJoin('users as u', 'u.id', '=', 'u_dl.user_id');
    }

    public static function getUserDiamondLogList($param)
    {
        $where = [];
        if ($param['start_time']) {
            $where[] = ['u_dl.created_at', '>=', $param['start_time']];
        }
        if ($param['end_time']) {
            $where[] = ['u_dl.created_at', '<=', $param['end_time']];
        }
        if ($param['type'] >= 1 && $param['type'] <= 13) {
            $where[] = ['u_dl.type', '=', $param['type']];
        }
        if ($param['user_id'] > 0) {
            $where[] = ['u_dl.user_id', '=', $param['user_id']];
        }
        $user_query = self::UserDiamondLogQuery()
            ->where($where);
        if ($param['keywords']) {
            $user_query = $user_query->where(function ($query) use ($param) {
                $query->where('u.phone', '=', $param['keywords'])
                    ->orWhere([
                        ['u_dl.user_id', '=', $param['keywords']],
                    ])
                    ->orWhere([
                        ['u_dl.number', '=', $param['keywords']],
                    ]);
            });
        }
        return [
            'count'     => $user_query->count('u_dl.id'),
            'data_list' => $user_query
                ->forPage($param['page'], $param['limit'])
                ->orderBy('u_dl.id', 'desc')
                ->get()
                ->each(function ($item, $key) {
                    return self::eachFunction($item, $key);
                })
        ];
    }

    private static function eachFunction($item, $key)
    {
        $item->extra = json_decode($item->extra);
        return $item;
    }
}