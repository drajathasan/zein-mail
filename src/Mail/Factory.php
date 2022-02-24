<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-22 14:01:56
 * @modify date 2022-02-24 13:06:24
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Mail;

use Exception;

class Factory
{

    use Error;

    /**
     * Error property
     *
     * @var string
     */
    private $Error;

    /**
     * Provider instance
     *
     * @var object
     */
    private $Agent;

    /**
     * Default prefix namespace for call provider
     * 
     * @var string
     */
    private $PrefixNamespace = '\Zein\Mail\Agent\\';

    /**
     * Template instance
     * 
     * @var object
     */
    private $Template;

    
    public function __construct(object $template)
    {
        if (!empty($template)) 
        {
            // Set template instance
            $this->Template = $template;

            // set provider
            $Class = $this->PrefixNamespace . $template->getAgent();
            $this->Agent = new $Class;
        }
    }

    /**
     * Set Agent Instance
     *
     * @param string $providerName
     * @return void
     */
    public function setAgent(string $agentName)
    {
        $Class = $this->PrefixNamespace . $agentName;

        if (!class_exists($Class)) 
            throw new Exception("Agent {$agentName} not found!", 1);

        $this->Agent = new $Class;

        return $this;
    }

    /**
     * Get PDF provider
     */
    public function getAgent()
    {
        return $this->Agent;
    }

    /**
     * Get PDF provider instance
     *
     * @return void
     */
    public function getMail()
    {
        if (func_num_args() === 0)
        {
            $Agent = $this->Agent->getInstance($this->Template->getDsn());
        }
        else
        {
            $Agent = call_user_func_array([$this->Agent, 'getInstance'], func_get_args());
        }
        
        // Load template
        $Agent->loadTemplate($this->Template);
        
        return  $Agent;
    }
}