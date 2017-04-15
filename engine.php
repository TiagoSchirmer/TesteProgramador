<?php
require_once 'init.php';

// define the Home controller for breadcrumb
TXMLBreadCrumb::setHomeController('WelcomeView');

class TApplication extends AdiantiCoreApplication
{
    static public function run($debug = FALSE)
    {
        new TSession;
        
        if ( (TSession::getValue('Logado'))||($_REQUEST['class']=='Login'))
        {
            if ($_REQUEST)
            {
                parent::run($debug);
            }
        }
         else
        {
           TScript::create('__adianti_goto_page("index.php?class=Login&method=logout")');
       
        }         
    }
    
}

TApplication::run(TRUE);

