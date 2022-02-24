<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-24 11:11:58
 * @modify date 2022-02-24 14:28:10
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Mail\Agent\Phpmailer\Phpmailer;

use Zein\Mail\FactoryInterface;
use Zein\Mail\Template;
use Zein\Mail\Error;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
    private $Mailer;

    /**
     * Dsn Property
     */
    private $Dsn;

    use Error;

    /**
     * Get Core mailer instance
     * 
     * @param string $Dsn;
     * @return Mailer
     */
    public function getInstance(string $Dsn)
    {
        $this->Mailer = new PHPMailer(true);
        $this->Dsn = $this->dsnParse($Dsn);
        return $this;
    }

    /**
     * Parse DSN 
     * 
     * @param string $Dsn
     * @return array
     */
    private function dsnParse(string $Dsn)
    {
        return explode(',', $Dsn);
    }

    /**
     * initialize mailer Property
     * 
     * @return void
     */
    private function init()
    {
        $this->MailerSMTPDebug = $this->Dsn[5]??SMTP::DEBUG_OFF;
        $this->Mailer->isSMTP();
        $this->Mailer->Host       = $this->Dsn[0];
        $this->Mailer->SMTPAuth   = true;
        $this->Mailer->Username   = $this->Dsn[1];
        $this->Mailer->Password   = $this->Dsn[2];
        $this->Mailer->SMTPSecure = $this->Dsn[3];
        $this->Mailer->Port       = $this->Dsn[4];
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
        $this->Mailer->Priority = $Priority;
        $this->Mailer->setFrom($From['mailAddress'], $From['label']);
        $this->Mailer->addAddress($To, $To);
        if (!empty($ReplyTo)) $this->Mailer->addReplyTo($ReplyTo, $ReplyTo);
        $this->Mailer->isHTML(true);                                 
        $this->Mailer->Subject = $Subject;

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
        $this->Mailer->addAttachment($Path, $OptionalName);
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
            $this->init();
            $this->Template->{$TemplateMethod}($DataToParse);
            $this->Mailer->Body    = $this->Template->getContents();
            $this->Mailer->AltBody = strip_tags($this->Template->getContents());
            $this->Mailer->send();
        } catch (Exception $e) {
            $this->Error = $e;
        }
    }
}