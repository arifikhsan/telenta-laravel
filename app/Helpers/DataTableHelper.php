<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DataTableHelper
{
    public static function make(Request $request, Builder $query, array $columns)
    {
        $draw   = $request->input('draw');
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value');

        // Filtering
        if (!empty($search)) {
            $query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $col) {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
            });
        }

        $recordsFiltered = $query->count();
        $recordsTotal    = $query->getModel()->count();

        // Ordering
        if ($request->has('order')) {
            foreach ($request->input('order') as $order) {
                $colIndex = $order['column'];
                $dir      = $order['dir'];
                $query->orderBy($columns[$colIndex], $dir);
            }
        }

        // Paging
        $data = $query->skip($start)->take($length)->get();

        return [
            'draw'            => intval($draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ];
    }

    public static function fromCollection(Request $request, Collection $collection, array $columns)
    {
        $draw   = $request->input('draw');
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value');

        $recordsTotal = $collection->count();

        // Filtering (di memory)
        if (!empty($search)) {
            $collection = $collection->filter(function ($row) use ($columns, $search) {
                foreach ($columns as $col) {
                    // cek property jika ada
                    if (isset($row[$col]) && stripos($row[$col], $search) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        $recordsFiltered = $collection->count();

        // Ordering
        if ($request->has('order')) {
            foreach ($request->input('order') as $order) {
                $colIndex = $order['column'];
                $dir      = $order['dir'];
                $colName  = $columns[$colIndex];

                $collection = $dir === 'asc'
                ? $collection->sortBy($colName)
                : $collection->sortByDesc($colName);
            }
        }

        // Paging
        $data = $collection->slice($start, $length)->values();

        return [
            'draw'            => intval($draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ];
    }

    public static function fromCollectionWithFormatter(Request $request, Collection $collection, array $formatters)
    {
        $draw   = $request->input('draw');
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value');

        $recordsTotal = $collection->count();

        // Filtering sederhana
        if (!empty($search)) {
            $collection = $collection->filter(function ($row) use ($search) {
                return stripos(json_encode($row), $search) !== false;
            });
        }

        $recordsFiltered = $collection->count();

        // Paging
        $paged = $collection->slice($start, $length)->values();

        // Formatting row by row
        $data = [];
        $num  = $start + 1;
        foreach ($paged as $item) {
            $row = [];
            foreach ($formatters as $formatter) {
                $row[] = $formatter($item, $num);
            }
            $data[] = $row;
            $num++;
        }

        return [
            'draw'            => intval($draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ];
    }
}