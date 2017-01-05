<?php

/*
 * @copyright  Copyright (c) 2011 by  ESS-UA.
 */

class Ess_M2ePro_Block_Adminhtml_Wizard_Amazon_Welcome_Content extends Mage_Adminhtml_Block_Widget
{
    // ########################################

    public function __construct()
    {
        parent::__construct();

        // Initialization block
        //------------------------------
        $this->setId('wizardAmazonWelcomeContent');
        //------------------------------

        $this->setTemplate('M2ePro/wizard/amazon/welcome/content.phtml');
    }

    // ########################################
}