<?php


namespace App\Http\GraphQL;


use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserQuery
{

    public function searchUsers($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if ( Auth::user()->superuser !== 1) {
            throw new \Exception('Unauthorised');
        }

        $query = $args['query'];

        $users = User::where( 'email' , 'like', '%'.$query.'%' )->orWhere('name', 'like', '%'.$query.'%')->limit(30)->get();

        return $users;
    }

}