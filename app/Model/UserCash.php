<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Db;

/**
 * @property int $id
 * @property string $number
 * @property int $user_id
 * @property int $cash_id
 * @property int $user_card_id
 * @property int $status
 * @property string $extra
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class UserCash extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_cash';
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
    protected $casts = [
        'id'           => 'int',
        'user_id'      => 'integer',
        'cash_id'      => 'integer',
        'user_card_id' => 'integer',
        'status'       => 'integer',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime'
    ];

    public static function getUserCashList($param)
    {
        $where = [];
        if ($param['start_time']) {
            $where[] = ['u_c.created_at', '>=', $param['start_time']];
        }
        if ($param['end_time']) {
            $where[] = ['u_c.created_at', '<=', $param['end_time']];
        }
        if ($param['status'] >= 0 && $param['status'] <= 2) {
            $where[] = ['u_c.status', '=', $param['status']];
        }
        if ($param['user_id'] > 0) {
            $where[] = ['u_c.user_id', '=', $param['user_id']];
        }
        $user_cash_query = self::UserUserCashQuery()
            ->where($where);
        if ($param['keywords']) {
            $user_cash_query = $user_cash_query->where(function ($query) use ($param) {
                $query->where('u.phone', '=', $param['keywords'])
                    ->orWhere([
                        ['u_c.user_id', '=', $param['keywords']],
                    ])
                    ->orWhere([
                        ['u_c.number', '=', $param['keywords']],
                    ]);
            });
        }
        return [
            'count'     => $user_cash_query->count('u_c.id'),
            'data_list' => $user_cash_query
                ->forPage($param['page'], $param['limit'])
                ->orderBy('u_c.id', 'desc')
                ->get()
                ->each(function ($item, $key) {
                    return self::eachFunction($item, $key);
                })
        ];
    }

    private static function eachFunction($item, $key)
    {
        $item->extra     = json_decode($item->extra);
        $item->vnd_count = $item->vnd_count / 10;
        return $item;
    }

    private static function UserUserCashQuery()
    {
        return Db::table('user_cash as u_c')
            ->select([
                'u_c.id',
                'u_c.number',
                'u_c.user_id',
                'u_c.cash_id',
                'u_c.user_card_id',
                'u_c.status',
                'u_c.extra',
                'u_c.created_at',
                'u_c.updated_at',
                'u.phone',
                'u_card.number as card_number',
                'c.diamond_count as vnd_count',
                //其他数据待补充 操作时间 打款成功时间 打款人
            ])
            ->leftJoin('users as u', 'u.id', '=', 'u_c.user_id')
            ->leftJoin('user_card as u_card', 'u_card.id', '=', 'u_c.user_card_id')
            ->leftJoin('cash as c', 'c.id', '=', 'u_c.cash_id');
    }
}