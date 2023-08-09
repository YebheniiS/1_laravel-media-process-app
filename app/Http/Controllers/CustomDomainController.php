<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;


class CustomDomainController extends Controller
{

  protected $servers = ["76.76.21.21"]; // need to add interactr ip address here
  protected $apiPrefix = "https://api.vercel.com";
  public function verify(Request $request)
  {

    try {
      $domain = $this->cleanDomainName($request->domain);

      //$recsDNS = dns_get_record($domain, DNS_CNAME);
      $recsDNS = dns_get_record($domain);

      if (!count($recsDNS)) {
        throw new \Exception('No DNS Record found for this domain');
      }

      $cname = 'custom.interactrapp.com';
      $checksOut = false;

      if (isset($recsDNS[0]['target'])) {
        // CNAME Record
        if ($recsDNS[0]['target'] === $cname) {
          $checksOut = true;
        } else throw new \Exception('Domain name doesn\'t point to the correct CNAME. It\'s currently pointing to: ' . $recsDNS[0]['target']);
      }

      if ($recsDNS[0]['type'] === 'A') {
        // A Record
        throw new \Exception('Domain name doesn\'t point to a cname. It\'s currently pointing to an A record. Please change the target for your subdomain to a cname record that points here : ' . $cname);

        // if (in_array($recsDNS[0]['ip'], $this->servers)) {
        //   $checksOut = true;
        // } else {
        //   throw new \Exception('Domain name doesn\'t point to the correct server. It\'s currently pointing to: ' . $recsDNS[0]['ip'] . '. Please change the target for your subdomain to ' . $this->servers[0]);
        // }
      }

      if ($recsDNS[0]['type'] === 'HINFO') {
        // Cloud Flare Record
        $checksOut = true;
      }

      $checksOut = true;
      if ($checksOut) {
        $agency = Agency::query()->where('user_id', auth()->id())->first();
        $agency->domain = $request->domain;
        $agency->domain_verified = 1;
        $agency->save();

        return response()->json([
          'success' => true,
          'agency' => $agency
        ]);
      } else throw new \Exception('No CNAME records setup for this domain. ');
    } catch (\Exception $e) {
      
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 400);
    }
  }

  public function checkStatus(Request $request)
  {
    try {
      $agency = Agency::where('domain', $request->domain)->first();
      if (!$agency) throw Exception('Domain not linked to any agencies');
      if (!$agency->domain_verified) throw Exception('Domain not setup correctly');

      return response()->json([
        'success' => true,
        'message' => 'domain is correctly setup',
        'agency' => $agency
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 400);
    }
  }

  private function cleanDomainName($domain)
  {
    $cleanup = str_replace('https://', '', $domain);
    $cleanup = str_replace('http://', '', $cleanup);
    $cleanup = str_replace('/', '', $cleanup);

    return $cleanup;
  }

  public function addDomain(Request $request) {
    try {
      $domain = $this->cleanDomainName($request->domain);

      //$recsDNS = dns_get_record($domain, DNS_CNAME);
      $recsDNS = dns_get_record($domain, DNS_A);
      
      if (!count($recsDNS)) {
        throw new \Exception('No DNS Record found for this domain');
      }
      // $cname = 'custom.interactrapp.com';
      $checksOut = false;

      // if (isset($recsDNS[0]['target'])) {
      //   // CNAME Record
      //   if ($recsDNS[0]['target'] === $cname) {
      //     $checksOut = true;
      //   } else throw new \Exception('Domain name doesn\'t point to the correct CNAME. It\'s currently pointing to: ' . $recsDNS[0]['target']);
      // }

      if ($recsDNS[0]['type'] === 'A') {
        // A Record
        // throw new \Exception('Domain name doesn\'t point to a cname. It\'s currently pointing to an A record. Please change the target for your subdomain to a cname record that points here : ' . $cname);

        if (in_array($recsDNS[0]['ip'], $this->servers)) {
          $checksOut = true;
        } else {
          throw new \Exception('Domain name doesn\'t point to the correct server. It\'s currently pointing to: ' . $recsDNS[0]['ip'] . '. Please change the target for your subdomain to ' . $this->servers[0]);
        }
      }

      if ($recsDNS[0]['type'] === 'HINFO') {
        // Cloud Flare Record
        $checksOut = true;
      }

      if ($checksOut) {
        $endpoint = $this->apiPrefix."/v9/projects/".env('VERCEL_PROJECT_ID')."/domains?teamId=".env('VERCEL_TEAM_ID');
        $res = Http::withHeaders(['Authorization' => "Bearer ".env('VERCEL_TOKEN')])->post($endpoint, [
            'name' => $domain
        ]);

        if(!isset($res['error']) && $res['verified']) {
          $agency = Agency::query()->where('user_id', auth()->id())->first();
          $agency->domain = $domain;
          $agency->domain_verified = 1;
          $agency->save();

          return response()->json([
            'success' => true,
            'agency' => $agency
          ]);

        } else throw new \Exception($res['error']['code']);
      } else throw new \Exception('No CNAME records setup for this domain. ');

    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 400);
    }
  }

  public function removeDomain() {
    try {
      $agency = Agency::query()->where('user_id', auth()->id())->first();
      $domain = $agency->domain;
      $endpoint = $this->apiPrefix."/v9/projects/".env('VERCEL_PROJECT_ID')."/domains/".$domain."?teamId=".env('VERCEL_TEAM_ID');
      $res = Http::withHeaders(['Authorization' => "Bearer ".env('VERCEL_TOKEN')])->delete($endpoint);

      if(!isset($res['error'])) {
        $agency = Agency::query()->where('user_id', auth()->id())->first();
        $agency->domain = "";
        $agency->domain_verified = 0;
        $agency->save();

        return response()->json([
          'success' => true,
          'agency' => $agency
        ]);
      } else throw new \Exception($res['error']['code']);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 400);
    }
  }
}
