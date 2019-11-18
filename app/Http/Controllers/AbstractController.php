<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class AbstractController extends Controller
{
    /**
     * @var Model $model
     */
    public $model;

    /**
     * AbstractController constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $request
     * @return Builder|Collection
     */
    protected function filterModelByQuery($request)
    {
        $query = $this->model->query();

        foreach ($request->filters as $filter) {
            if ($filter['value'] != null) {
                if ($filter['column'] == 'created_at' || $filter['column'] == 'updated_at') {
                    $filter['value'] = Carbon::parse($filter['value'])->toDateTimeString();
                }

                $query->where($filter['column'], $filter['operator'], $filter['value']);
            }
        }

        if (isset($request->joins)) {
            foreach ($request->joins as $join) {
                $fields = $join['fields'] != null ? ':' . implode(',', $join['fields']) : '';

                $query->with($join['name'] . $fields);

                if (Arr::has($join, 'value') && $join['value'] != null) {
                    $query->whereHas($join['name'], function ($query) use ($join) {
                        $query
                            ->where($join['column'], $join['operator'], $join['value'])
                            ->select($join['fields'] != null ? $join['fields'] : '*')
                        ;
                    });
                }
            }
        }

        $query = $query->get();

        if ($query instanceof Model) {
            $query = collect($query);
        }

        return $query;
    }
}
