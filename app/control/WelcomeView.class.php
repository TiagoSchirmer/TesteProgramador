<?php
/**
 * Tela Inicial
 * @author     Tiago Fernando Schirmer
 */
 class WelcomeView extends TPage
{
    /**
     * Constructor method
     */   
    public function __construct()
    {
        parent::__construct();
      //  TSession::setValue('Logado',False);
        var_dump(TSession::getValue('Logado'));
        var_dump(TSession::getValue('nome_usuario'));
        var_dump(TSession::getValue('codigo_usuario'));
        var_dump(TSession::getValue('login_usuario'));
    }
}
?>
