<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\Laravel\Facade as Kavenegar;

class SMSController extends Controller
{
    const FORMAT = '%s = %s <br/>';

    public function sendSMS(Request $request): void
    {
        $mobile = $request->mobile;
        $messageText = $request->message;

        try {
            $sender = '+9890005085';
            $message = $messageText;
            $receptor = [$mobile];
            $result = Kavenegar::Send($sender, $receptor, $message);
            $this->format($result);
        } catch (ApiException $e) {
            echo $e->errorMessage();
        } catch (HttpException $e) {
            echo $e->errorMessage();
        }
    }

    private function format($result): void
    {
        if ($result) {
            echo '<pre>';
            foreach ($result as $r) {
                echo sprintf(self::FORMAT, 'messageid', $r->messageid);
                echo sprintf(self::FORMAT, 'message', $r->message);
                echo sprintf(self::FORMAT, 'status', $r->status);
                echo sprintf(self::FORMAT, 'statustext', $r->statustext);
                echo sprintf(self::FORMAT, 'sender', $r->sender);
                echo sprintf(self::FORMAT, 'receptor', $r->receptor);
                echo sprintf(self::FORMAT, 'date', $r->date);
                echo sprintf(self::FORMAT, 'cost', $r->cost);
                echo '<hr/>';
            }
            echo '</pre>';
        }
    }
}
