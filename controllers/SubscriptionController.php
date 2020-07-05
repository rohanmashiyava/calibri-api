<?php

namespace app\controllers;

use app\components\RestController;
use app\models\SubscriptionInfo;
use app\models\UserDevicesInfo;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SubscriptionController extends RestController
{
    //check subscriptions
    public function actionIndex(){
        try {
            $data = [];
            if (Yii::$app->request->isPost) {
                $postdata = Json::decode(file_get_contents("php://input"));
                if ($postdata['UUID']) {
                    $udid = $postdata['UUID'];
                    $devices = UserDevicesInfo::find()->where(['unique_device_id' => $udid])->one();
                    if ($devices) {
                        if ($devices->subscriptionInfos) {
                            $subscription = SubscriptionInfo::findOne(['user_device_id'=>$devices->id]);
                            if ($devices->paid_app == true) {
                                //offline receipt validation
                                    if($subscription->is_expired){
                                        $code = $data['error']['code'] = 200;
                                        $data['error']['msg_code'] = "subscription_expired";
                                        $data['error']['message'] = "subscription is expired";
                                        $data['error']['description'] = "Subscription is Expired.";
                                    }else{
                                        if($subscription->expiry_time < time()){
                                            $subscription->is_expired = ACTIVE;
                                            $subscription->save(false);
                                            $code = $data['error']['code'] = 200;
                                            $data['error']['msg_code'] = "subscription_expired";
                                            $data['error']['message'] = "subscription is expired";
                                            $data['error']['description'] = "Subscription is Expired.";
                                        }else{
                                            $code = $data['code'] = 200;
                                            $data['message'] = "Valid Subscription (offline)";
                                            $data['data'] = [
                                                'UUID'=>$devices->unique_device_id,
                                                'autoRenewing'=>$subscription->auto_renewing,
                                                'expiryTime'=>$subscription->expiry_time,
                                                'isExpired'=>$subscription->is_expired,
                                                'lasUpdateTime'=>$subscription->last_update_time,
                                                'isIap'=>$subscription->is_iap,
                                            ];
                                        }

                                    }

                            } else {
                                //google receipt validation
                                if($this->googleReceiptValidation()){

                                }else{

                                }
                            }
                        } else {
                            if ($devices->paid_app == true) {
                                // setup subscription if paid user
                                if ($this->setupPaidAppUserSubscription($devices)) {
                                    $subscription = SubscriptionInfo::findOne(['user_device_id'=>$devices->id]);
                                    $code = $data['code'] = 200;
                                    $data['message'] = "paid app user is now subscribed for next 6 month. ";
                                    $data['data'] = [
                                        'UUID'=>$devices->unique_device_id,
                                        'autoRenewing'=>$subscription->auto_renewing,
                                        'expiryTime'=>$subscription->expiry_time,
                                        'isExpired'=>$subscription->is_expired,
                                        'lasUpdateTime'=>$subscription->last_update_time,
                                        'isIap'=>$subscription->is_iap,
                                    ];
                                } else {
                                    $code = $data['error']['code'] = 200;
                                    $data['error']['msg_code'] = "something_wrong_with_subscription_add";
                                    $data['error']['message'] = "offline Subscription failure";
                                    $data['error']['description'] = "Something went wrong.";
                                }
                            } else {
                                // if not paid then return message
                                $code = $data['error']['code'] = 200;
                                $data['error']['msg_code'] = "invalid_user_need_subscription";
                                $data['error']['message'] = "Subscription Expired or Invalid";
                                $data['error']['description'] = "Subscription Expired or Invalid";
                            }
                        }
                    }
                } else {
                    //UUID not found so Add new User
                    $code = $data['error']['code'] = 200;
                    $data['error']['msg_code'] = "device_not_found";
                    $data['error']['message'] = "device not found in the system";
                    $data['error']['description'] = "UUID is not found in the system please add Device";
                }

            } else {
                //error for method not allowed
                $code = $data['error']['code'] = 405;
                $data['error']['message'] = $this->getStatusCodeMessage($code);
            }
        }catch(\Exception $e){
            $code = $data['error']['code'] = 500;
            $data['error']['message'] = $this->getStatusCodeMessage($code);
            $data['error']['description'] = $e->getMessage();
        }

        $this->sendResponse($code, JSON::encode($data));
    }

    public function setupPaidAppUserSubscription($devices){
        if($devices->paid_app == true){
            $subscription  = new SubscriptionInfo();
            $subscription->user_device_id = $devices->id;
            $subscription->auto_renewing = INACTIVE;
            $subscription->expiry_time = strtotime("+6 months");
            $subscription->is_expired = NOT_EXPIRED;
            $subscription->last_update_time = time();
            $subscription->is_iap = INACTIVE;
            if($subscription->validate()){
                $subscription->save();
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function googleReceiptValidation(){
        return true;

        $googleClient = new \Google_Client();
        $googleClient->setScopes([\Google_Service_AndroidPublisher::ANDROIDPUBLISHER]);
        $googleClient->setApplicationName('Your_Purchase_Validator_Name');
        $googleClient->setAuthConfig($pathToServiceAccountJsonFile);

        $googleAndroidPublisher = new \Google_Service_AndroidPublisher($googleClient);
        $validator = new \ReceiptValidator\GooglePlay\Validator($googleAndroidPublisher);

        try {
            $response = $validator->setPackageName('PACKAGE_NAME')
                ->setProductId('PRODUCT_ID')
                ->setPurchaseToken('PURCHASE_TOKEN')
                ->validateSubscription();
        } catch (\Exception $e){
            var_dump($e->getMessage());
            // example message: Error calling GET ....: (404) Product not found for this application.
        }
    }





}
