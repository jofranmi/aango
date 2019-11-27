<?php

namespace App\Listeners\User;

use App\Events\Notification\NotificationEvent;
use App\Events\User\CreateUserEvent;
use App\Mail\user\UserCreateMail;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;

class CreateUserListener implements ShouldQueue
{
    /**
     * @var Mailer $mailer
     */
    protected $mailer;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var UserCreateMail $userCreateMail
     */
    protected $userCreateMail;

    /**
     * Create the event listener.
     *
     * @param Mailer $mailer
     * @param User $user
     * @param UserCreateMail $userCreateMail
     */
    public function __construct(Mailer $mailer, User $user, UserCreateMail $userCreateMail)
    {
        $this->mailer = $mailer;
        $this->user = $user;
        $this->userCreateMail = $userCreateMail;
    }

    /**
     * Handle the event.
     *
     * @param CreateUserEvent $event
     * @return bool
     */
    public function handle(CreateUserEvent $event)
    {
        if ($this->user->where('email', $event->email)->first()) {
            event(new NotificationEvent('A user with the email ' . $event->email . ' already exists', 'alert-danger', $event->user));
            return true;
        }

        $user = $this->user->create([
            'name' => $event->name,
            'email' => $event->email,
            'customer_id' => empty($event->customerId) ? 0 : $event->customerId,
            'user_types_id' => $event->userTypesId,
            'password' => Hash::make($event->password)
        ]);

        if ($user) {
            $this->userCreateMail
                ->setPassword($event->password)
                ->setUser($user);
            $this->mailer
                ->to($user->email)
                ->send($this->userCreateMail);

            event(new NotificationEvent('User has been created successfully!', 'alert-success', $event->user));
        } else {
            event(new NotificationEvent('There was an error creating the user', 'alert-danger', $event->user));
        }
    }

    public function failed(CreateUserEvent $event)
    {
        event(new NotificationEvent('There was an error creating the user', 'alert-danger', $event->user));
    }
}
