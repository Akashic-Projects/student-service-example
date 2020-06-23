<?php

namespace App\Services\Akashic;

use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Log;

class BruteForceDetectionService
{
    private $akashicBaseUrl;

    public function __construct()
    {
        $this->akashicBaseUrl = env('AKASHIC_HOST','http://localhost:5000');
    }

    private function create_login_attempt(Request $request) {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name" => "Create_new_LoginAttempt__".$uuid,
            "salience" => 500,
            "run-once" => true,
            "when" => [
                (object)[ "?time=" => "time_to_str(now(), '%d.%m.%Y. %H:%M:%S')" ]
            ],
            "then" => [
                (object) [
                    "create" => (object) [
                        "model-id" => "LoginAttempt",
                        "reflect-on-web" => false,
                        "data" => (object) [
                            "id" => $uuid,
                            "ip" => $request->ip(),
                            "timestamp" => "?time"
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Add_login_attempt' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }


    private function create_new_current_time() {
        $uuid = str_replace("-", "", Uuid::generate()->string);
        $rule = (object) [
            "rule-name" => "Create_new_CurrentTimestamp__".$uuid,
            "salience" => 1010,
            "run-once" => true,
            "when" => [
                (object)[ "?time=" => "now()" ]
            ],
            "then" => [
                (object) [
                    "create" => (object) [
                        "model-id" => "CurrentTimestamp",
                        "reflect-on-web" => false,
                        "data" => (object) [
                            "id" => $uuid,
                            "timestamp" => "?time"
                        ]
                    ]
                ]
            ]
        ];
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/rules';
        try {
            $response = $client->request('POST',
                $uri,
                [
                    'json' => $rule,
                    'http_errors' => true
                ]
            );
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Add_login_attempt' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            if ($resp) {
                dd((string) $resp->getBody());
            } else {
                dd("Body is empty");
            }
        }
        return json_decode($response->getBody());
    }



    public function check_brute_force(Request $request) {

        $resp1 = $this->create_login_attempt($request);
        $resp2 = $this->create_new_current_time();

        /* RUN ENGINE */
        /* Send request */
        $client = new Client();
        $uri = $this->akashicBaseUrl . '/direct/run';
        try {
            $response = $client->request('GET', $uri);
        } catch (ServerException $e) {
            Log::critical(Psr7\str($e->getResponse()));
            throw new BadRequestHttpException("Error while adding 'Add_login_attempt' rule.");
        }  catch (RequestException $e) {
            $resp = $e->getResponse();
            dd((string) $resp->getBody());
        }

        $resp = json_decode($response->getBody());
        //dd($resp);

        foreach ($resp->data as $ret) {
            if (strcmp($ret->meta->tag, "blocked_ip") == 0 and
                (strcmp($ret->data->ip, $request->ip()) == 0)) {
                return false;
            }
        }

        return $resp;
    }
}
