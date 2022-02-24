# Example

```PHP
<?php

use Zein\Mail\{Factory, Template};

require '../vendor/autoload.php';

class IttpMail implements Template
{
    private $Dsn = 'gmail+smtp://<youremail>:<password>@default';
    //private $Dsn = 'ssl://smtp.gmail.com:465,<youremail>.com,<password>,465,465,0';
    private $Agent = 'Symfony\Mailer\Mailer';
    //private $Agent = 'Phpmailer\Phpmailer\Mailer';
    private $Contents;

    /**
     * Get template agent
     */
    public function getAgent()
    {
        return $this->Agent;
    }

    public function getDsn()
    {
        return $this->Dsn;
    }

    public function getContents()
    {
        return $this->Contents;
    }

    public function baspus()
    {
        $Agent = $this->getAgent();
        $this->Contents = <<<HTML
            <h1>Lorem Ipsum {$Agent}</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        HTML;
    }

    /**
     * Set template agent
     */
    public function setAgent(string $agentName)
    {
        $this->Agent = $agentName;
    }
}

$Factory = new Factory(new IttpMail);
$Mail = $Factory->getMail();
$Mail->compose(['mailAddress' => '<sender>', 'label' => 'Mail Test'], '<receiver>', 'SMTP Outgoing Test');
$Mail->send('baspus'); // send email based on template method

var_dump($Mail->getError());
```