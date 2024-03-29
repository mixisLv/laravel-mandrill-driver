<?php

namespace mixisLv\LaravelMandrillDriver;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class MandrillWebhookController extends Controller
{

    /**
     * Handle the Mandrill webhook and call
     * method if available
     *
     * @param Request $request
     * @return Response
     */
    public function handleWebHook(Request $request)
    {
        if ($this->validateSignature($request)) {
            $events = $this->getJsonPayloadFromRequest($request);

            foreach ($events as $event) {
                $eventName = isset($event['event']) ? $event['event'] : 'undefined';
                if($eventName == 'undefined' && isset($event['type'])){
                    $eventName = $event['type'];
                }
                $method = 'handle' . Str::studly(str_replace('.', '_', $eventName));

                if (method_exists($this, $method)) {
                    $this->{$method}($event);
                }
            }

            return new Response;
        }

        return  new Response('Unauthorized', 401);
    }

    /**
     * Pull the Mandrill payload from the json
     *
     * @param Request $request
     *
     * @return array
     */
    private function getJsonPayloadFromRequest(Request $request)
    {
        return (array) json_decode($request->get('mandrill_events'), true);
    }

    /**
     * Validates the signature of a mandrill request if key is set
     *
     * @param  Request $request
     *
     * @return bool
     */
    private function validateSignature(Request $request)
    {
        $webhookKey = config('services.mandrill.webhook-key', config('services.mandrill.secret'));

        if (!empty($webhookKey)) {
            $signature = $this->generateSignature($webhookKey, $request->url(), $request->all());
            return $signature === $request->header('X-Mandrill-Signature');
        }

        return true;
    }

    /**
     * https://mandrill.zendesk.com/hc/en-us/articles/205583257-How-to-Authenticate-Webhook-Requests
     * Generates a base64-encoded signature for a Mandrill webhook request.
     *
     * @param string $webhookKey the webhook's authentication key
     * @param string $url the webhook url
     * @param array $params the request's POST parameters
     *
     * @return string
     */
    public function generateSignature($webhookKey, $url, $params)
    {
        $signedData = $url;
        ksort($params);
        foreach ($params as $key => $value) {
            $signedData  .= $key;
            $signedData  .= $value;
        }

        return base64_encode(hash_hmac('sha1', $signedData, $webhookKey, true));
    }
}
