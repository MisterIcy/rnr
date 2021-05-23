<?php


namespace MisterIcy\RnR;


final class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private \Swift_Mailer $mailer;

    /**
     * Mailer constructor.
     */
    public function __construct()
    {
        global $transport;
        $this->mailer = new \Swift_Mailer($transport);
    }

    /**
     * @param array<string, string> $to
     * @param string $subject
     * @param string $content
     * @return int
     */
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
