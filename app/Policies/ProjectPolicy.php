<?php

namespace App\Policies;

use App\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use SebastianBergmann\Comparator\Book;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Can authenticated user update the project. The global scope should only return
     * projects that can be updated but this is an added layer of auth
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function update(User $user, Project $project) : Bool
    {
        // Does the user own the project
        if( $user->id === $project->user_id ) return true;

        // Does the users parent own the project
        if( $user->parent_user_id === $project->user_id ) return true;

        // If we get here user does't have permission
        return false;
    }

    /**
     * Does the user have permission to create a
     * project
     * @param User $user
     * @return bool
     */
    public function create(User $user) : Bool
    {
        // Is the user a sub user?
        if($user->parent_user_id) return false;

        // Can the user create more projects
        $maxProjects = $user->max_projects;

        if( $maxProjects ) {
            // How many projects has the user created
            $projects = Project::where('user_id', $user->id )->count();

            if($maxProjects >= $projects) return false;
        }

        // All checks passed
        return true;
    }

    /**
     * Does the user permissions to update the template
     * properties on a project
     * @param User $user
     * @return bool
     */
    public function updateTemplate(User $user) : Bool
    {
        return ( $user->superuser );
    }

    /**
     * Can user delete the project
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function destroy(User $user, Project $project) : Bool
    {
        return ( $user->id === $project->user_id );
    }
}
