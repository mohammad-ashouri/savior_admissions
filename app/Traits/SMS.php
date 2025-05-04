<?php

namespace App\Traits;

use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

trait SMS
{
    public function sendSMS($mobile, $messageText): void
    {
        try {
            $sender = '+9890005085';
            $message = $messageText;
            $receptor = [$mobile];
//            $result = Kavenegar::Send($sender, $receptor, $message."\nلغو11");
//            $this->format($result);
        } catch (ApiException $e) {
            echo $e->errorMessage();
        } catch (HttpException $e) {
            echo $e->errorMessage();
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
