<?php

namespace App\Filters;
use App\Filters\ApiFilter;

class TasksFilter extends ApiFilter{

    protected $allowedParams = [
        'id' => ['eq'],
        'projectId' => ['eq'],
        'running' => ['eq'],
        'date' => ['eq', 'lt', 'lte', 'gt', 'gte'],
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
