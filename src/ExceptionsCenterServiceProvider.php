<?php

namespace ExceptionsCenter\Laravel;

use Illuminate\Support\ServiceProvider;
use ExceptionsCenter\Laravel\Test\ExceptionTestCommand;

/**
 * Class ExceptionsCenterServiceProvider
 * @package ExceptionsCenter\Laravel
 *
 * @property array commands
 *
 * @author: Damien MOLINA
 */
class ExceptionsCenterServiceProvider extends ServiceProvider {

    /**
     * @var array
     */
    protected $commands = [
        ExceptionTestCommand::class,
    ] ;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->publishes(
	        [__DIR__ . '/ExceptionSender.php' => app_path('Exceptions/ExceptionSender.php')], 'exception-sender'
        ) ;

        $this->commands($this->commands) ;
    }

}
