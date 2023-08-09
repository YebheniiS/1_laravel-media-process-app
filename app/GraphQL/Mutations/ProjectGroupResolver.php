<?php

namespace App\GraphQL\Mutations;

use App\Project;
use App\ProjectGroup;

class ProjectGroupResolver
{
    /**
     * @param $rootValue
     * @param array $args
     * @param $context
     * @param $resolveInfo
     * @return bool
     */
    public function updateProjectGroupsSorting($rootValue, array $args, $context, $resolveInfo)
    {
        $lists = $args['lists'];

        foreach ($lists as $list) {
            ProjectGroup::query()->find($list['id'])->update(['sort_order' => $list['sort_order_number']]);
        }

        return $lists;
    }
}
