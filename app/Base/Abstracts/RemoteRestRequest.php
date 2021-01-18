<?php namespace App\Base\Abstracts;

use App\Base\Models\RemoteApiLog;
use App\Base\Traits\HasErrors;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use SoapClient;
use SoapHeader;

class RemoteRestRequest
{
    use HasErrors;

    protected $statusCode = 200;
    protected $errorMessage = '';
    protected $result = true;

    protected $content;
    protected $log;

    protected $base_uri;
    protected $path;
    protected $method = 'GET';
    protected $options;

    protected function onSuccess(): void
    {

    }

    protected function onError(): void
    {

    }

    protected function onComplete(): void
    {

    }

    protected function prepareRequest(): void
    {

    }

    public function __construct()
    {
        $this->prepareRequest();

        if($this->type == "soap")
        {
            $this->soapRequest();
        }else{
            $this->makeRequest();
        }
    }

    protected function makeRequest(): void
    {
        $log = new RemoteApiLog();
        $log->user_id = auth()->id() ?? 0;
        $log->request_class = static::class;
        $log->remote_path = $this->base_uri . '/' . $this->path;

        $requestObjectForLog = $this->options;
        if (
            isset($requestObjectForLog['headers']) &&
            isset($requestObjectForLog['headers']['content-type']) &&
            $requestObjectForLog['headers']['content-type'] == 'application/json'
        ) {
            if (isset($requestObjectForLog['body'])) {
                $requestObjectForLog['body'] = json_decode($requestObjectForLog['body']);
            }
        }

        $log->request = json_encode($requestObjectForLog);

        try {

            $client = new Client(['base_uri' => $this->base_uri]);
            $response = $client->request($this->method, $this->path, $this->options);
            $this->setContent($response->getBody()->getContents());
            $this->setStatusCode($response->getStatusCode());

        } catch (ClientException $exception) {

            $this->addError($exception->getMessage());
            $this->setStatusCode($exception->getResponse()->getStatusCode());
            $this->setContent($exception->getResponse()->getBody()->getContents());

        } catch (BadResponseException $exception) {

            $this->addError($exception->getMessage());
            $this->setStatusCode($exception->getResponse()->getStatusCode());
            $this->setContent($exception->getResponse()->getBody()->getContents());

        } catch (\Exception $exception) {

            $this->addError($exception->getMessage(), $exception->getTrace());
            $this->setStatusCode(404);
        }

        $log->response = $this->ensureJson($this->getContent());

        $log->http_status = $this->getStatusCode();

        if ($this->hasErrors()) {
            $log->failed = true;
            $log->errors = json_encode($this->getErrors());
            $this->onError();
        } else {
            $log->failed = false;
            $this->onSuccess();
        }
        $log->save();
        $this->setLog($log);
        $this->onComplete();
    }

    private function ensureJson($input)
    {
        if (
            is_string($input) &&
            (
                $input == 'null' ||
                json_decode($input) !== null
            )
        ) {
            return $input;
        } else {
            return json_encode($input);
        }
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    private function setStatusCode($statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    private function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * Return the log Model for the request
     *
     * @return RemoteApiLog|null
     */
    public function getLog(): ?RemoteApiLog
    {
        return $this->log;
    }

    /**
     * @param null $log
     */
    private function setLog($log): void
    {
        $this->log = $log;
    }

    protected function soapRequest(): void
    {

        $log = new RemoteApiLog();
        $log->user_id = auth()->id() ?? 0;
        $log->request_class = static::class;
        $log->remote_path = $this->base_uri;


        $requestObjectForLog = $this->options;

        if (
            isset($requestObjectForLog['headers']) &&
            isset($requestObjectForLog['headers']['content-type']) &&
            $requestObjectForLog['headers']['content-type'] == 'application/json'
        ) {
            if (isset($requestObjectForLog['body'])) {
                $requestObjectForLog['body'] = json_decode($requestObjectForLog['body']);
            }
        }

        $log->request = json_encode($requestObjectForLog);

        try {
            $headers = $requestObjectForLog['headers'];
            $auth = $requestObjectForLog['auth'];
            $connection = $this->connect($auth,$headers,$this->base_uri);
            $method = $this->method;
            $params = $this->objToArray(json_decode($requestObjectForLog['body'], true));
            $response = $connection->$method($params);

            $this->setContent($response->SetOrderResult->OrderResultInfo);
            $this->setStatusCode($response->SetOrderResult->OrderResultInfo->ResultCode);

        } catch (ClientException $exception) {

            $this->addError($exception->getMessage());
            $this->setStatusCode($exception->getResponse()->getStatusCode());
            $this->setContent($exception->getResponse()->getBody()->getContents());

        } catch (BadResponseException $exception) {

            $this->addError($exception->getMessage());
            $this->setStatusCode($exception->getResponse()->getStatusCode());
            $this->setContent($exception->getResponse()->getBody()->getContents());

        } catch (\Exception $exception) {

            $this->addError($exception->getMessage(), $exception->getTrace());
            $this->setStatusCode(404);
        }

        $log->response = $this->ensureJson($this->getContent());

        $log->http_status = $this->getStatusCode();

        if ($this->hasErrors()) {
            $log->failed = true;
            $log->errors = json_encode($this->getErrors());
            $this->onError();
        } else {
            $log->failed = false;
            $this->onSuccess();
        }
        $log->save();
        $this->setLog($log);
        $this->onComplete();
    }

    protected function objToArray($obj)
    {
        if (!is_object($obj) && !is_array($obj)) {
            return $obj;
        }
        foreach ($obj as $key => $value) {
            $arr[$key] = $this->objToArray($value);
        }
        return $arr;
    }

    public function connect($auth,$headers,$base_uri)
    {
        $opts = array(
            'ssl' => array('verify_peer' => false, 'verify_peer_name' => false)
        );
        $client = new SoapClient($base_uri,array('trace'=> 1,'exceptions'=> 0,'stream_context' => stream_context_create($opts)));
        $AuthHeader = $auth;
        $header = new SoapHeader($headers['url'], $headers['Authentication'], $AuthHeader, false);
        $client->__setSoapHeaders(array($header));
        return $client;
    }

}
