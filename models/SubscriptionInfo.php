<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subscription_info".
 *
 * @property int $id
 * @property int $user_device_id
 * @property int $auto_renewing 0-no auto renew 1- auto renew
 * @property int $expiry_time
 * @property int|null $is_expired 0-not expired 1-expired
 * @property int $last_update_time
 * @property int $is_iap
 * @property string|null $order_id
 * @property string|null $product_id
 * @property string|null $purchase_state
 * @property int|null $purchase_time
 *
 * @property UserDevicesInfo $userDevice
 */
class SubscriptionInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_device_id', 'expiry_time', 'last_update_time', 'is_iap'], 'required'],
            [['user_device_id', 'auto_renewing', 'expiry_time', 'is_expired', 'last_update_time', 'is_iap', 'purchase_time'], 'integer'],
            [['order_id', 'product_id', 'purchase_state'], 'string', 'max' => 255],
            [['user_device_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserDevicesInfo::className(), 'targetAttribute' => ['user_device_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_device_id' => 'User Device ID',
            'auto_renewing' => 'Auto Renewing',
            'expiry_time' => 'Expiry Time',
            'is_expired' => 'Is Expired',
            'last_update_time' => 'Last Update Time',
            'is_iap' => 'Is Iap',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'purchase_state' => 'Purchase State',
            'purchase_time' => 'Purchase Time',
        ];
    }

    /**
     * Gets query for [[UserDevice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserDevice()
    {
        return $this->hasOne(UserDevicesInfo::className(), ['id' => 'user_device_id']);
    }
}
