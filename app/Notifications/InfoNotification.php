<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class InfoNotification extends Notification
{
    use Queueable;

    private $_title = "";
    private $_body = "";
    private $_action_url = "/";
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $body, $action_url)
    {
        //
        $this->_title = $title;
        $this->_body = $body;
        $this->_action_url = $action_url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast', WebPushChannel::class];
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
          'title' => $this->_title,
          'body' => $this->_body,
          'action_url' => $this->_action_url,
          'created' => Carbon::now()->toIso8601String()
      ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->_title)
            ->body($this->_body)
            ->data(['id' => $notification->id]);
    }
}
