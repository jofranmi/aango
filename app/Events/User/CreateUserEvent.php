<?php

namespace App\Events\User;

use App\Events\AbstractEvent;
use Illuminate\Http\Request;

class CreateUserEvent extends AbstractEvent
{
    /**
     * @var string $name
     */
    public $name;

    /**
     * @var string $email
     */
    public $email;

    /**
     * @var string $password
     */
    public $password;

    /**
     * @var int $customerId
     */
    public $customerId;

    /**
     * @var int $userTypesId
     */
    public $userTypesId;

    /**
     * Create a new event instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();

        $this->name = $request->name;
        $this->email = $request->email;
        $this->password = $request->password;
        $this->customerId = $request->customer;
        $this->userTypesId = $request->type;
    }
}
