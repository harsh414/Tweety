<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTweetNotification extends Notification
{
    use Queueable;
    public $tweet;
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tweet,$user)
    {
        $this->tweet= $tweet;
        $this->user= $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type'=>'New tweet',
            'message'=>'Recent Tweet From '.$this->user->name,
            'tweet_body'=>$this->tweet->body,
            'url'=>$this->user->url,
            'id'=>$this->tweet->id,
        ];
    }
}
