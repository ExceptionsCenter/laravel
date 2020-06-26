<?php

namespace ExceptionsCenter\Laravel\Test;

use Illuminate\Console\Command;
use ExceptionsCenter\Laravel\ExceptionSender;
use ExceptionsCenter\Laravel\API\Sender\LaravelExceptionSender;
use ExceptionsCenter\Laravel\Exceptions\CheckExceptionCenterException;

/**
 * Class ExceptionTestCommand
 * @package ExceptionsCenter\Laravel\Test
 *
 * @author: Damien MOLINA
 */
class ExceptionTestCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exception:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify if the Exception Center request is correctly sent';

    /**
     * Make a clear comment
     *
     * @return void
     */
    private function clearComment() {
        $this->comment("If It is, please run php artisan config:clear");
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        $this->comment("Generation of a ExceptionCenterTestException instance...\n") ;

        $class = config('app.exceptions.model', ExceptionSender::class) ;

        if(is_null($class)) {
            $this->alert("The model is not defined in the config file") ;
            $this->clearComment() ;
            return ;
        }

        /** @var LaravelExceptionSender $center */
        $center = new $class ;
        if(! $center->isEnabled()) {
            $this->alert("The sender is currently disabled") ;
            $this->clearComment() ;
            return ;
        }

        if(is_null($center->getProjectKey())) {
            $this->alert("The project key hasn't been declared in the config file") ;
            $this->clearComment() ;
            return;
        }

        $response = $center::make(new CheckExceptionCenterException)->send() ;

        if(boolval($response)) {
            $this->comment('Exception successfully sent!') ;
        } else {
            $this->comment("An error occurred :(");
        }
    }

}
