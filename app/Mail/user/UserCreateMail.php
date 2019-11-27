<?php

namespace App\Mail\user;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string $password
     */
    public $password;

    /**
     * @var User $user
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your user account created')
            ->view('mail.user.create');
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): UserCreateMail
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): UserCreateMail
    {
        $this->user = $user;

        return $this;
    }
}
