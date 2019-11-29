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
        	/** If there is no value for the filter, ignore it */
            if ($filter['value'] == null) {
            	continue;
            }

            /** Parse all dates */
			if ($filter['column'] == 'created_at' || $filter['column'] == 'updated_at') {
				$filter['value'] = Carbon::parse($filter['value'])->toDateTimeString();
			}

			/** Query the model based on the filter structure */
			$query->where($filter['column'], $filter['operator'], $filter['value']);
        }

        if (isset($request->joins)) {
            foreach ($request->joins as $join) {
            	/** Selects only the fields necessary from a model, ->with(relationship:field1,field2) */
                $fields = $join['fields'] != null ? ':' . implode(',', $join['fields']) : '';

                /** Applies the previously formatted relationship */
                $query->with($join['name'] . $fields);

                /** No need to filter the relationship if the value is not set */
                if (!Arr::has($join, 'value') || empty($join['value'])) {
                    continue;
                }

                /** Fields can be not set in the Vue filter and it will return all the fields */
				$query->whereHas($join['name'], function ($query) use ($join) {
					$query
						->where($join['column'], $join['operator'], $join['value'])
						->select($join['fields'] != null ? $join['fields'] : '*')
					;
				});
            }
        }

        $query = $query->get();

        if ($query instanceof Model) {
            $query = collect($query);
        }

        return $query;
    }
}
