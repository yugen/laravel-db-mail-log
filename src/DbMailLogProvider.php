<?php

namespace Berglab\DbMailLog;

use Exception;
use Berglab\DbMailLog\Models\Email;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\ServiceProvider;
use Berglab\DbMailLog\Listeners\StoreMailInDatabase;
use Berglab\DbMailLog\Contracts\Email as EmailContract;

class DbMailLogProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/db_mail_log.php' => config_path('db_mail_log.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../config/db_mail_log.php', 'db_mail_log');

        if (! class_exists('CreateEmailLogTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/create_email_log_table.php' => database_path("/migrations/{$timestamp}_create_email_log_table.php"),
            ], 'migrations');
        }

        $this->registerMailLogging();
    }
    
    public function register()
    {
    }

    public static function getEmailLogEntryClass()
    {
        $model = config('db_mail_log.email_model') ?? Email::class;
        
        if (! is_a($model, EmailContract::class, true) || ! is_a($model, Model::class, true)) {
            throw new Exception('Invalid Email Log Entry class.  It must implement Berglab\\DbMailLog\\Contracts\\Email and exted Illuminate\Database\Eloquent\Model');
        }

        return $model;
    }

    public static function getEmailInstance($attributes = [])
    {
        $class = self::getEmailLogEntryClass();

        return new $class($attributes);
    }

    private function registerMailLogging()
    {
        Event::listen(MessageSent::class, StoreMailInDatabase::class);
    }
}
