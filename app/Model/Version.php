<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property int $dpt
 * @property float $version
 * @property string $version_tip
 * @property int $min_version_id
 * @property string $apk_url
 * @property string $description
 * @property int $level
 * @property int $status
 * @property string $extra
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Version extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'version';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dpt',
        'version',
        'version_tip',
        'min_version_id',
        'apk_url',
        'description',
        'level',
        'status',
        'extra',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'             => 'int',
        'dpt'            => 'integer',
        'version'        => 'float',
        'min_version_id' => 'integer',
        'level'          => 'integer',
        'status'         => 'integer',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime'
    ];

    public static function getVersionList($param)
    {
        $where = [];
        if ($param['type'] == 1 || $param['type'] == 2) {
            $where[] = ['dpt', '=', $param['type']];
        }
        if ($param['start_time']) {
            $where[] = ['created_at', '>=', $param['start_time']];
        }
        if ($param['end_time']) {
            $where[] = ['created_at', '<=', $param['end_time']];
        }
        $VersionQuery = self::VersionQuery()
            ->where($where);
        if ($param['keywords']) {
            $VersionQuery = $VersionQuery->where(function ($query) use ($param) {
                $query->where('description', 'like', "%{$param['keywords']}%")
                    ->orWhere('version', '=', $param['keywords']);
            });
        }
        return [
            'count'     => $VersionQuery->count('id'),
            'data_list' => $VersionQuery
                ->forPage($param['page'], $param['limit'])
                ->orderBy('id', 'desc')
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

    private static function VersionQuery()
    {
        return self::query()
            ->select([
                'id',
                'dpt',
                'version',
                'version_tip',
                'min_version_id',
                'apk_url',
                'description',
                'level',
                'status',
                'extra',
                'created_at',
                'updated_at',
            ]);
    }
}