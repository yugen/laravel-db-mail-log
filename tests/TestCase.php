<?php

namespace Berglab\DbMailLog\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setup():void
    {
        parent::setup();
        $this->setupDatabase();
    }

    public function setupDatabase($argument)
    {
        $this->createEmailLogTable();
    }

    protected function createEmailLogTable()
    {
        include_once(__DIR__.'/../migrations/create_email_log_table.php);
    }
    
}
