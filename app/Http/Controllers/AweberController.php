<?php

namespace App\Http\Controllers;

use App\Lib\AweberApiConnector;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AweberController extends Controller
{
    /**
     * @var AweberApiConnector
     */
    protected $apiConnector;
    protected $user;

    public function connect()
    {

        if (!$this->user instanceof User) {
            throw new AuthorizationException();
        }

        return new AweberApiConnector($this->user->integration_aweber);
    }

    public function addContact(Request $request)
    {
        try {
            $this->user = User::FindOrFail($request->input('autoresponder_owner'));
    
            $req = $this->connect()->email( $request->get('email') );
    
            if ($request->has('name')) {
                $req->name($request->input('name'));
            }
    
            $res = $req->addToList( $request->get('list_id') );

            return response()->json([
                'success' => true, 
                'response' => $res
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getLists()
    {
        $this->user = Auth::user();
        $lists = $this->connect()->getLists();
        return ['lists' => $lists];
    }
}