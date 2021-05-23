<?php


namespace MisterIcy\RnR;


final class Mailer
{
    private \Swift_Mailer $mailer;

    public function __construct()
    {
        global $transport;
        $this->mailer = new \Swift_Mailer($transport);
    }

    public function createMessage(
        array $to,
        string $subject,
        string $content
    ) {
        $message = new \Swift_Message();
        $message->setFrom(
            [$_ENV['MAIL_FROM'] => $_ENV['MAIL_FROM_NAME']]
        )
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($content, 'text/html', 'UTF-8');

        return $this->mailer->send($message);
    }
}
