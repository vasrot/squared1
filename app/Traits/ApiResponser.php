<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser {

    protected function successResponse($data, $code) {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code) {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200) {
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }

        $collection = $this->sortData($collection);
        $collection = $this->paginate($collection);
        $collection = $this->cache($collection);

        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance, $code = 200) {
        return $this->successResponse($instance, $code);
    }

    protected function sortData(Collection $collection) {
        if ( request()->has('sort_by')) {
            $collection = $collection->sortBy(request()->sort_by)->values();
        } else if (request()->has('sort_by_desc')) {
            $collection = $collection->sortByDesc(request()->sort_by_desc)->values();
        }
        return $collection;
    }

    protected function paginate(Collection $collection) {
        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 100;

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }

    protected function cache($data) {

        $url = request()->url();
        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 1/60, function () use($data) {
            return $data;
        });
    }

}
