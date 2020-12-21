<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Class CreateSuperUser
 * @package App\Console\Commands
 */
class CreateSuperUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:super-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for create super user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function handle()
    {
        $this->info('Start create new super user');
        $user = User::factory()->make();
        $validationRules = $user->validationRules();
        $inputArgs = $this->getInputArgs($validationRules);
        $user->fill($inputArgs);
        $user->role = User::ROLE_ADMIN;
        $user->save();
        $this->info("User with email {$user->email} was created");
    }

    /**
     * @param array
     * @return array
     */
    private function getInputArgs(array $validationRules)
    {
        $result = [];
        foreach ($validationRules as $attribute => $rule) {
            $value = $this->ask(ucfirst($attribute) . ' ?');
            $result[$attribute] = $value;
        }
        return $result;
    }
}
