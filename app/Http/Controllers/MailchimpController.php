<?php

namespace App\Http\Controllers;

use App\Lib\MailchimpApi;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MailchimpController extends Controller
{
    protected $user;

    public function connect()
    {
        $mailChimpApi = new MailchimpApi($this->user->integration_mailchimp);

        dd(123);

        if (!$mailChimpApi) {
            throw new \Exception('Unable to connect MailChimp API.');
        }

        return $mailChimpApi;
    }

    public function addContact(Request $request)
    {
        try {
            $this->user = User::findOrFail($request->input('autoresponder_owner'));

            $req = $this->connect()->email($request->email);

            if ( $request->input('name') ) {
                $req->name($request->name);
            }

            $res = $req->addToList($request->list_id);
            return response()->json([
                'success' => true,
                'response' => $res
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace'=>$e->getTrace()
            ], 400);
        }
    }

    public function getLists(Request $request)
    {
        if ((int) $request->user_id)
            $this->user = User::findOrFail((int) $request->user_id);
        else
            $this->user = Auth::user();

        try {
            $lists = $this->connect()->getLists();

            if (!$lists) return response()->error('Invalid credentials.');

            return $lists;
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }
}
