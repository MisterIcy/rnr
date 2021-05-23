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
     * Creates and sends a message
     *
     * @param array<string, string> $to Array of receipients
     * @param string $subject Message's Subject
     * @param string $content Message's Body
     * @return int Number of successfully delivered emails
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
