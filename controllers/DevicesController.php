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

class DevicesController extends RestController
{

    /**
     * Add New Device Info.
     *
     * @return string
     */
    public function actionAddDevices()
    {
        try{
            $data=[];
            if(Yii::$app->request->isPost){
                $postdata = Json::decode(file_get_contents("php://input"));
                $user = UserDevicesInfo::findOne(['unique_device_id'=>$postdata['UUID']]);
                if($user){
                    $model = $user;
                }else{
                    $model= new UserDevicesInfo();
                }

                $model->attributes = $postdata;
                $model->unique_device_id =  $postdata['UUID'];
                $model->paid_app = $postdata['paidApp'];
                $model->last_version = $postdata['lastVersion'];
                $model->created_date = time();

                if($model->validate()){
                    $model->save();
                    $code = $data['code'] = 201;
                    $data['message']= $this->getStatusCodeMessage($code);

                }else{
                    $code = $data['error']['code'] = 200;
                    $data['error']['message']= "something went wrong.";
                }

            }else{
                $code = $data['error']['code']=405;
                $data['error']['message']=$this->getStatusCodeMessage($code);
            }
        } catch(\Exception $e){
            $code = $data['error']['code']=500;
            $data['error']['message']=$this->getStatusCodeMessage($code);
            $data['error']['description']=$e->getMessage();
        }


        $this->sendResponse($code, JSON::encode($data));
    }

    /**
     * Add New Device Info.
     *
     * @return string
     */
    public function actionAddSubscription()
    {
        try{
            $data=[];
            if(Yii::$app->request->isPost){
                $postdata = Json::decode(file_get_contents("php://input"));

                $user = UserDevicesInfo::findOne(['unique_device_id'=>$postdata['UUID']]);
                if($user){
                    $model = new SubscriptionInfo();
                    $model->user_device_id = $user->id;
                    $model->auto_renewing = $postdata['autoRenewing'];
                    $model->expiry_time = $postdata['expiryTime'];
                    $model->is_expired = $postdata['isExpired'];
                    $model->last_update_time = $postdata['lastUpdateTime'];
                    $model->order_id = $postdata['orderId'];
                    $model->product_id = $postdata['productId'];
                    $model->purchase_state = $postdata['purchaseState'];
                    $model->purchase_time = $postdata['purchaseTime'];
                    if($model->validate()){
                        $model->save();
                        $code = $data['code'] = 201;
                        $data['message']= $this->getStatusCodeMessage($code);

                    }else{
                        print_r($model->getErrors());die;
                        $code = $data['error']['code'] = 200;
                        $data['error']['message']= "something went wrong.";
                    }
                }else {
                    $code = $data['error']['code']=200;
                    $data['error']['message']= "User is not found with this UUID";
                }
            }else{
                $code = $data['error']['code']=405;
                $data['error']['message']=$this->getStatusCodeMessage($code);
            }
        } catch(\Exception $e){
            $code = $data['error']['code']=500;
            $data['error']['message']=$this->getStatusCodeMessage($code);
            $data['error']['description']=$e->getMessage();
        }


        $this->sendResponse($code, JSON::encode($data));
    }




}
