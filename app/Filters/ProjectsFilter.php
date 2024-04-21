<?php

namespace App\Filters;
use App\Filters\ApiFilter;

class ProjectsFilter extends ApiFilter{

    protected $allowedParams = [
        'id' => ['eq'],
        'name' => ['eq'],
    ];

    protected $columnMap = [];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>='
    ];

}
