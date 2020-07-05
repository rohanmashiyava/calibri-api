<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_devices_info".
 *
 * @property int $id
 * @property string $unique_device_id
 * @property string|null $version_name
 * @property string|null $version_code
 * @property int $paid_app 0-notpaid 1-paid
 * @property string|null $last_version
 * @property int $created_date
 *
 * @property SubscriptionInfo[] $subscriptionInfos
 */
class UserDevicesInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_devices_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unique_device_id', 'created_date'], 'required'],
            [['paid_app', 'created_date'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unique_device_id' => 'Unique Device ID',
            'version_name' => 'Version Name',
            'version_code' => 'Version Code',
            'paid_app' => 'Paid App',
            'last_version' => 'Last Version',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * Gets query for [[SubscriptionInfos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionInfos()
    {
        return $this->hasMany(SubscriptionInfo::className(), ['user_device_id' => 'id']);
    }
}
