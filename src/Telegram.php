<?php

namespace src;

class Telegram {
    protected $token;
    protected $webhook_url;
    protected $api_url;

    public function __construct($token, $webhook_url = null)
    {
        $this->token = $token;
        $this->webhook_url = $webhook_url;
        $this->api_url = 'https://api.telegram.org/bot'.$token.'/';
    }

    public function setWebhook($flag, $cert_file_path = null)
    {
        $postfields = [
            'url' => $flag ? $this->webhook_url : ''
        ];

        $handle = curl_init($this->api_url.'setWebhook');

        if (null !== $cert_file_path) {
            $postfields['certificate'] = '@'.$cert_file_path;
            curl_setopt($handle, CURLOPT_HEADER, true);
            curl_setopt($handle, CURLOPT_HTTPHEADER, [
                "Content-Type: multipart/form-data",
            ]);
            curl_setopt($handle, CURLOPT_INFILESIZE, filesize($cert_file_path));
            curl_setopt($handle, CURLOPT_POSTFIELDS, $postfields);
        }

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);

        return $this->execCurlRequest($handle);
    }

    protected function logError($mes)
    {
        file_put_contents(DIR_TMP.'telegram.errors.log', "----\n".date('Y-m-d H:i:s')."\n".$mes."\n", FILE_APPEND);
    }

    public function apiRequestWebhook($method, $parameters) {
        if (!is_string($method)) {
            $this->logError("Method name must be a string\n");
            return false;
        }

        if (!$parameters) {
            $parameters = array();
        } else if (!is_array($parameters)) {
            $this->logError("Parameters must be an array\n");
            return false;
        }

        $parameters["method"] = $method;

        header("Content-Type: application/json");
        echo json_encode($parameters);
        return true;
    }

    public function execCurlRequest($handle) {
        $response = curl_exec($handle);

        if (false === $response) {
            $errno = curl_errno($handle);
            $error = curl_error($handle);
            $this->logError("Curl returned error $errno: $error\n");
            curl_close($handle);
            return false;
        }

        $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
        curl_close($handle);

        if (200 == $http_code){
            $response = json_decode($response, true);
            if (isset($response['description'])) {
                $this->logError("Request was successfull: {$response['description']}\n");
            }
            $response = $response['result'];
        } else if ($http_code >= 500) {
            // do not wat to DDOS server if something goes wrong
            sleep(10);
            return false;
        } else {
            $response = json_decode($response, true);
            $this->logError("Request has failed. Http code = {$http_code}. Error {$response['error_code']}: {$response['description']}\n");
            if ($http_code == 401) {
                throw new \Exception('Invalid access token provided');
            }
            return false;
        }

        return $response;
    }

    public function apiRequest($method, $parameters) {
        if (!is_string($method)) {
            $this->logError("Method name must be a string\n");
            return false;
        }

        if (!$parameters) {
            $parameters = array();
        } else if (!is_array($parameters)) {
            $this->logError("Parameters must be an array\n");
            return false;
        }

        foreach ($parameters as $key => &$val) {
            // encoding to JSON array parameters, for example reply_markup
            if (!is_numeric($val) && !is_string($val)) {
                $val = json_encode($val);
            }
        }
        $url = $this->api_url.$method.'?'.http_build_query($parameters);

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);

        return $this->execCurlRequest($handle);
    }

    public function apiRequestJson($method, $parameters) {
        if (!is_string($method)) {
            $this->logError("Method name must be a string\n");
            return false;
        }

        if (!$parameters) {
            $parameters = array();
        } else if (!is_array($parameters)) {
            $this->logError("Parameters must be an array\n");
            return false;
        }

        $parameters["method"] = $method;

        $handle = curl_init($this->api_url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
        curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);

        return $this->execCurlRequest($handle);
    }

    public function processMessage($message) {
        // process incoming message
        $message_id = $message['message_id'];
        $chat_id = $message['chat']['id'];
        if (isset($message['text'])) {
            // incoming text message
            $text = $message['text'];

            if (strpos($text, "/start") === 0) {
                return $this->apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => array(
                    'keyboard' => array(array('Hello', 'Hi')),
                    'one_time_keyboard' => true,
                    'resize_keyboard' => true)));
            } else if ($text === "Hello" || $text === "Hi") {
                return $this->apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Nice to meet you'));
            } else if (strpos($text, "/stop") === 0) {
                // stop now
            } else {
                return $this->apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => 'Cool'));
            }
        } else {
            return $this->apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
        }
    }

    public function sendMessage($message, $chat_id)
    {
        $this->apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => $message));
    }

    public function sendMessageWH($message, $chat_id)
    {
        $this->apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, 'text' => $message));
    }
} 