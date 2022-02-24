<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-22 14:03:16
 * @modify date 2022-02-24 13:06:48
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Mail;

use Zein\Mail\Template;

interface FactoryInterface
{
    public function getInstance(string $Dsn);
    public function getTemplate($callback = '');
    public function getError(string $ExceptionMethod = 'getMessage');
    public function loadTemplate(Template $Template);
    public function compose($From, string $To, string $Subject, string $ReplyTo = '', $Priority = 0);
    public function send(string $TemplateMethod);
}