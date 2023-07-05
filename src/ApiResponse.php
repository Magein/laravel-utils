<?php

namespace Magein\LaravelUtils;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    public static function json($params)
    {
        if ($params instanceof ApiJson) {
            return response()->json($params->toArray());
        }

        $apiJson = new ApiJson();
        if ($params instanceof LengthAwarePaginator) {
            $apiJson->setData([
                'pages' => [
                    'current_page' => $params->currentPage(),
                    'per_page' => $params->perPage(),
                    'total' => $params->total(),
                    'last_page' => $params->lastPage(),
                    'has_more' => $params->hasMorePages(),
                    //获取结果集中第一个数据的编号
                    'from' => $params->firstItem(),
                    // 获取结果集中最后一个数据的编号
                    'to' => $params->lastItem(),
                ],
                'items' => $params->items(),
            ]);
        } elseif ($params instanceof Collection) {
            $apiJson->setData($params->toArray());
        } else {
            $apiJson->setData($params);
        }
        return response()->json($apiJson->toArray());
    }
}
