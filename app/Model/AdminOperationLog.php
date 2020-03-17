<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Db;

/**
 * @property int $id
 * @property int $user_id
 * @property string $path
 * @property string $method
 * @property string $ip
 * @property string $input
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AdminOperationLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_operation_log';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path', 'user_id', 'method', 'ip', 'input'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function setInputAttribute($value)
    {
        $this->attributes['input'] = json_encode($value);
    }

    public function getInputAttribute($value)
    {
        return json_decode($value);
    }

    public static function getAdminOperationLogList($param)
    {
        $where = [];
        if ($param['start_time']) {
            $where[] = ['a_ol.created_at', '>=', $param['start_time']];
        }
        if ($param['end_time']) {
            $where[] = ['a_ol.created_at', '<=', $param['end_time']];
        }
        $user_query = self::AdminOperationLogQuery()
            ->where($where);
        if ($param['keywords']) {
            $user_query = $user_query->where(function ($query) use ($param) {
                $query->where('u.username', 'like', "%{$param['keywords']}%")
                    ->orWhere([
                        ['a_ol.user_id', '=', $param['keywords']],
                    ])
                    ->orWhere([
                        ['u.name', 'like', "%{$param['keywords']}%"],
                    ])
                    ->orWhere([
                        ['a_ol.path', '=', $param['keywords']],
                    ])
                    ->orWhere([
                        ['a_ol.ip', '=', $param['keywords']],
                    ]);
            });
        }
        return [
            'count'     => $user_query->count('a_ol.id'),
            'data_list' => $user_query
                ->forPage($param['page'], $param['limit'])
                ->orderBy('a_ol.id', 'desc')
                ->get()
                ->each(function ($item, $key) {
                    return self::eachFunction($item, $key);
                })
        ];
    }

    private static function AdminOperationLogQuery()
    {
        return Db::table('admin_operation_log as a_ol')
            ->select([
                'a_ol.id',
                'a_ol.path',
                'a_ol.user_id',
                'a_ol.method',
                'a_ol.ip',
                'a_ol.input',
                'a_ol.created_at',
                'a_ol.updated_at',
                'u.username',
                'u.name',
            ])
            ->leftJoin('admin_user as u', 'u.id', '=', 'a_ol.user_id');
    }

    private static function eachFunction($item, $key)
    {
        $item->input = json_decode($item->input);
        return $item;
    }
}