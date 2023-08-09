<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Lib\Api;
use App\Lib\GetResponseApi;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\Auth;

class GetResponseController extends Controller
{
    protected $user;

    public function connect()
    {
        if (!$this->user instanceof User) {
            throw new AuthorizationException();
        }
        return new GetResponseApi($this->user->integration_getresponse);
    }

    public function addContact(Request $request)
    {
        try {
            $this->user = User::FindOrFail($request->input('autoresponder_owner'));

            $req = $this->connect()->email( $request->get('email') );

            if ($request->has('name')) {
                $req->name( $request->input('name') );
            }

            $res = $req->addToCampaign( $request->get('list_id') );

            return response()->json([
                'success'=> true,
                'response' => $res
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
        
    }

    public function getCampaigns(Request $request)
    {
        if ((int) $request->user_id)
            $this->user = User::findOrFail((int) $request->user_id);
        else
            $this->user = Auth::user();

        return $this->connect()->getCampaigns();
    }
}
