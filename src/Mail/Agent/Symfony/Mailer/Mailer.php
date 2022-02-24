<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-24 11:11:58
 * @modify date 2022-02-24 14:28:02
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Mail\Agent\Symfony\Mailer;

use Zein\Mail\FactoryInterface;
use Zein\Mail\Template;
use Zein\Mail\Error;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer as CoreMailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Exception\TransportException;

class Mailer implements FactoryInterface
{
    /**
     * Error property
     */
    private $Error;

    /**
     * Template instance
     */
    private $Template;

    /**
     * Mailer instance
     */
    private $Email;

    /**
     * Mailer instance
     */
    private $Mailer;

    use Error;

    /**
     * Get Core mailer instance
     * 
     * @param string $Dsn;
     * @return Mailer
     */
    public function getInstance(string $Dsn)
    {
        $this->Mailer = new CoreMailer(Transport::fromDsn($Dsn));
        return $this;
    }

    /**
     * Get template instance
     * 
     * @return instance Template
     */
    public function getTemplate($callback = '')
    {
        if (is_callable($callback)) return $callback($this->Template);
        return $this->Template;
    }

    /**
     * Load template
     * 
     * @param instance Template
     */
    public function loadTemplate(Template $Template)
    {
        $this->Template = $Template;
    }

    /**
     * Compose mailer base information
     * 
     * @param array|string $From
     * @param string $To
     * @param string $Subject
     * @param string $ReplyTo
     * @param int|string $Priority
     * 
     * @return Mailer
     */
    public function compose($From, string $To, string $Subject, string $ReplyTo = '', $Priority = 0)
    {
        $this->Email = new Email;
        $this->Email
            ->from((is_string($From) ? $From : new Address($From['mailAddress'], $From['label'])))
            ->to($To)
            ->replyTo((empty($ReplyTo) ? $To : $ReplyTo))
            ->priority(($Priority == 0 ? Email::PRIORITY_HIGH : $Priority))
            ->subject($Subject);

        return $this;
    }

    /**
     * Set mail attachment
     * 
     * @param string $Path
     * @param string $OptionalName
     * @return Mailer
     */
    public function setAttachment(string $Path, string $OptionalName = '')
    {
        $this->Email->attachFromPath($Path, $OptionalName);
        return $this;
    }
    
    /**
     * Send Email
     * 
     * @param string $TemplateMethod
     * @return void
     */
    public function send(string $TemplateMethod, array $DataToParse = [])
    {
        try {
            $this->Template->{$TemplateMethod}($DataToParse);
            $this->Email->html($this->Template->getContents());
            $this->Mailer->send($this->Email);
        } catch (TransportException $e) {
            $this->Error = $e;
        }
    }
}