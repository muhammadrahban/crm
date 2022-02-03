<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
class Notification extends Model
{
    public $fillable = ['to','from','message','read_at','resend'];

    public function scopeToSingleDevice($query, $token= null, $title=null,$body=null,$icon, $click_action){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
                            ->setSound('default')
                            ->setBadge($this->where('read_at',null)->count())
                            ->setIcon($icon)
                            ->setClickAction($click_action);
        $dataBuilder = new PayloadDataBuilder();
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        $token = $token;
        // dd($token);
        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();
        $downstreamResponse->tokensToDelete();
        $downstreamResponse->tokensToModify();
        $downstreamResponse->tokensToRetry();
        $downstreamResponse->tokensWithError();
    }

    public function scopeToMultiDevice($query,$model, $title=null,$body=null,$icon, $click_action)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
                            ->setSound('default')
                            ->setBadge($this->where('read_at',null)->count())
                            ->setIcon($icon)
                            ->setClickAction($click_action);
        $dataBuilder = new PayloadDataBuilder();

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = $model->pluck('device_token')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();
        $downstreamResponse->tokensToDelete();
        $downstreamResponse->tokensToModify();
        $downstreamResponse->tokensToRetry();
        $downstreamResponse->tokensWithError();
    }

    public function scopeRead()
    {
        return $this->where('read_at',null)->get();
    }

    public function scopeNumberAlert(){
          $this->where('read_at',null)->count();
    }

    public function fromContact()
    {
        return $this->hasOne(WebUser::class, 'id', 'from');
    }

    public function toContact()
    {
        return $this->hasOne(WebUser::class, 'id', 'to');
    }
}

