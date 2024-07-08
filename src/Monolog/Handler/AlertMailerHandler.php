<?php

declare(strict_types = 1);

namespace DesLynx\AlertBundle\Monolog\Handler;

use DesLynx\AlertBundle\Service\AlertHelper;
use Monolog\Handler\MailHandler;
use Monolog\Logger;

class AlertMailerHandler extends MailHandler
{
    private AlertHelper $alertHelper;

    /**
     * @param AlertHelper $alertHelper The alert mailer helper
     */
    public function __construct(AlertHelper $alertHelper, $level = 400, bool $bubble = true)
    {
        $level = Logger::toMonologLevel($level);
        parent::__construct($level, $bubble);
        $this->alertHelper = $alertHelper;
    }

    /**
     * {@inheritDoc}
     */
    protected function send(string $content, array $records): void
    {
        $this->alertHelper->sendAlert('Log error 500', $content, true);
    }
}
