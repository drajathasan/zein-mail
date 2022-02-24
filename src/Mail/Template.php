<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-23 07:31:56
 * @modify date 2022-02-24 12:29:08
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Mail;

interface Template
{    
    public function getDsn();
    
    public function getContents();

    /**
     * Get template agent
     */
    public function getAgent();

    /**
     * Set template agent
     */
    public function setAgent(string $agentName);
}