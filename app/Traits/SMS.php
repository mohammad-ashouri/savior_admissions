<?php

namespace App\Traits;

use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\Laravel\Facade as Kavenegar;

trait SMS
{
    public function sendSMS($mobile, $messageText)
    {
        if (env('SEND_SMS')==true) {
            try {
                $sender = '+9890005085';
                $message = $messageText;
                $receptor = [$mobile];
                $result = Kavenegar::Send($sender, $receptor, $message . "\nلغو11");
                $this->format($result);
            } catch (ApiException $e) {
                echo $e->errorMessage();
            } catch (HttpException $e) {
                echo $e->errorMessage();
            }
        }else{
            return 'Sent (BETA)';
        }
    }

    private function format($result)
    {
        //        $statuses = [];
        //        if ($result) {
        //            foreach ($result as $r) {
        //                $statuses[] = ['status' => $r->status];
        //            }
        //        }
        //        return json_encode($statuses);
    }
}
