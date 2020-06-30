<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 11/23/2015
 * Time: 5:36 AM
 */
namespace app\components;
use Yii;
use yii\web\Controller;
use yii\helpers\Json;

class RestController extends Controller {

    public $layout = false;
    public $enableCsrfValidation = false;
    public $success_status = [200,201,204];


    public function init() {
        parent::init();
    }

    Const APPLICATION_ID = 'thetatech';

    protected function sendResponse($status = 200, $body = '', $contentType = 'application/json') {
        // Set the status
        $statusHeader = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
        header($statusHeader);
        // Set the content type
        header('Content-type: ' . $contentType);

        echo $body;
        exit();
    }

    protected  function errorResponder($postData,$validationError)  {

        $response = array();
        $error_key = array_keys($validationError);
        $post_key = array_keys($postData);
        $validate_key = array_intersect($error_key,$post_key);
        $validate_description = array();
        foreach($validationError as $key => $values) {
            if(in_array($key,$validate_key)){
                $validate_description = $this->array_push_assoc($validate_description,$key,$values[0]);
            }
        }
        $response['error']=['code'=>400,'message'=>$this->getStatusCodeMessage(400),'validate_keys'=>$validate_key,"validate_description"=>(object)$validate_description,'description'=>REST_DEFAULT_MESSAGE_STRING,"error_log"=>$validationError];
        $this->sendResponse(400,json_encode($response));
    }
    protected function filterData($data){
        $requestarray = Json::decode($data);
        $result =Yii::$app->commonfunction->alterKeyInArray($requestarray,'@');
        return Json::encode($result);
    }
    protected function makeResponse($code,$message,$res_data = []){

        if(!in_array($code,$this->success_status)){
            $data['error']['code'] = $code;
            $data['error']['message']= $this->getStatusCodeMessage($code);
            $data['error']['description']= $message;

        }else{
            $data['code'] = $code;
            $data['message']= $this->getStatusCodeMessage($code);
            $data['description']= $message;
            if(!empty($res_data)){
                $data['response_data']= $res_data;
            }
        }


        return $data;
    }

    protected function getSIG(){
        return md5($this->expedia_api_key.$this->expedia_secret_key.gmdate('U'));
    }
    protected function expediaConfig(){
        return array(
            'cid'=>$this->expedia_cid,
            'apikey'=>$this->expedia_api_key,
            'sig'=>$this->getSIG()
        );
    }

    protected function checkAuth() {
        $headers = apache_request_headers();
        // Check if we have the USERNAME and PASSWORD HTTP headers set?
        $RESTHEADER = 'httpx-' . self::APPLICATION_ID . '-accesstoken';//ucwords('httpx-' . self::APPLICATION_ID . '-accesstoken', "-");
        if (!isset($headers[$RESTHEADER])) {
            // Error: Unauthorized
            //$this->sendResponse(401);
            $this->sendResponse(401, JSON::encode(array('error'=>array('success' => false, 'message' => $this->getStatusCodeMessage(401),'description' => "access token not given"))));
        }
        $access_token = $headers[$RESTHEADER];
        //$p = Yii::$app->params->get['webservice_token_expired'];
        //$tokenUserData = $devices->find('token=:token', array(':token' => $access_token));
        //$devicedata = Devices::find()->where(['access_token' => $access_token])->one();

        //if ($tokenUserData !== null && (strtotime('now') < strtotime($p,strtotime($tokenUserData->created_date)))) {
        if ($devicedata !== null) {
            return true;
        } else {
            // Error: Unauthorized
            $this->sendResponse(401, JSON::encode(array('error'=>array('success' => false, 'message' => $this->getStatusCodeMessage(401),'description' => "access token not valid or not given"))));
        }

    }
    protected function array_push_assoc($array, $key, $value){
        $array[$key] = $value;
        return $array;
    }

    protected function getStatusCodeMessage($status) {
        $codes = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }


}