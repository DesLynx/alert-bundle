<?php

namespace DesLynx\AlertBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * Send an alert email.
 *
 * @author Geordie Vial <contact@geordie-vial.fr>
 */
class AlertHelper
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;
    private string $env;
    private string $from;
    /** @var string[] */
    private array $to;
    private string $projectName;
    private ?string $transport;

    /**
     * @param MailerInterface $mailer
     * @param LoggerInterface $logger
     * @param string          $env
     * @param string          $from
     * @param string[]        $to
     * @param string          $projectName
     * @param string|null     $transport
     */
    public function __construct(
        MailerInterface $mailer,
        LoggerInterface $logger,
        string $env,
        string $from,
        array $to,
        string $projectName,
        ?string $transport = null
    ) {
        $this->transport   = $transport;
        $this->projectName = $projectName;
        $this->to          = $to;
        $this->from        = $from;
        $this->env         = $env;
        $this->logger      = $logger;
        $this->mailer      = $mailer;
    }

    /**
     * @param string   $subject
     * @param string   $message
     * @param bool     $isHtml
     * @param string[] $ccs
     *
     * @return void
     */
    public function sendAlert(string $subject, string $message, bool $isHtml = false, array $ccs = []): void
    {
        $email = (new Email())
            ->from(Address::create($this->from));
        foreach ($this->to as $to) {
            $email->addTo(Address::create($to));
        }

        foreach ($ccs as $cc) {
            $email->addCc(Address::create($cc));
        }

        $email
            ->priority(Email::PRIORITY_HIGH)
            ->subject(sprintf('Alert on %s [%s]: %s', $this->projectName, $this->env, $subject));
        if ($isHtml) {
            $email->html($message);
        } else {
            $email->text($message);
        }

        if (null !== $this->transport) {
            $headers = $email->getHeaders();
            $headers->addTextHeader('X-Transport', $this->transport);
        }
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->critical('Transport error while sending the email.');
        }
    }
}
