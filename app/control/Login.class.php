<?php

/**
 * Tela de Entrada de Usuário 
 * @author     Tiago Fernando Schirmer
 */
 class Login extends TPage
{
    public $oForm;
    public $oTable;
    public $oBox;
    /**
     * Constructor method
     */   
    public function __construct()
    {
        parent::__construct();
        
        $this->oForm = new TForm;
        $this->oForm->class = "Formulario";
        $this->oTable = new TTable;
       
        $this->oBox = new THBox;
        $this->oForm->add( $this->oBox);
                
        $this->oBox->add($this->oTable)->class = 'boxlogin';
        
        $oSpIcoUser = new TElement('span');
        $oSpIcoUser->class = "glyphicon glyphicon-user";
        
        $oSpIcoSenha = new TElement('span');
        $oSpIcoSenha->class = "glyphicon glyphicon-lock";
        
        $oUsuarioIco = new TElement('span');
        $oUsuarioIco->id = "icousuario";
        $oUsuarioIco->class = "input-group-addon";
        $oUsuarioIco->add($oSpIcoUser);

        $oSenhaIco = new TElement('span');
        $oSenhaIco->id = "icousuario2";
        $oSenhaIco->class = "input-group-addon";
        $oSenhaIco->add($oSpIcoSenha);        
        
        $oLogin         = new TEntry('login_usuario');
        $oLogin->placeholder = 'Digite o Usuario';
        $oLogin->id='login_usuario';
        
        $oSenha         = new TPassword('senha_usuario');
        $oSenha->placeholder =  'Digite a Senha';
        $oSenha->id='senha_usuario';
        $oBotao         = new TButton('entrar');
        $oBotao->class = 'btn btn-primary';
       
        $sLBotao = new TLabel('Entrar');
        $sLBotao->setFontSize(18);
        $sLBotao->setFontStyle('b');
        $sLBotao->setFontColor('#FFFFFF');
        $sLBotao->id='labelbotao';
        $oAcao = new TAction(array($this,'onLogin'));
        $oBotao->setAction($oAcao,$sLBotao );
        
        $oContainer1 = new TElement('div');
        $oContainer1->add($oUsuarioIco);
        $oContainer1->add($oLogin);
        
        $oContainer2 = new TElement('div');
        $oContainer2->add($oSenhaIco);
        $oContainer2->add($oSenha);
        
        $oRow = $this->oTable->addRow();
        $oRow->addCell($oContainer1);
        $oRow = $this->oTable->addRow();
        $oRow->addCell($oContainer2);
        $oRow = $this->oTable->addRow();
        $oRow->addCell($oBotao);
        
        
        $this->oForm->setFields(array($oLogin, $oSenha, $oBotao));  
        
        parent::add($this->oForm);
      
    }


    /**
     * Método que recebera os dados digitados na tela e fará a autenticação do usuario
     */ 
    public function onLogin()
    {
        $oData = $this->oForm->getData();
      
        try
        {
            TTransaction::open('conecta');
            
            $Criteria = new TCriteria;
            $Criteria->add(new TFilter('login_usuario','=',$oData->login_usuario));
            $Criteria->add(new TFilter('senha_usuario','=',$oData->senha_usuario));
           
            
            $Repository = new TRepository('Usuarios');
            $Usuarios = $Repository->load($Criteria);
            if($Usuarios)
            {
                TSession::setValue('Logado',True);
                TSession::setValue('nome_usuario',$Usuarios[0]->nome_usuario);
                TSession::setValue('codigo_usuario',$Usuarios[0]->codigo_usuario);
                TSession::setValue('login_usuario',$Usuarios[0]->login_usuario);
                TApplication::gotoPage('WelcomeView');  
            }
            else
            {
                new TMessage('info','Usuário ou Senha Invalidos. Senhas para login. usuario: teste senha: teste');
            }
            TTransaction::close();
            
        
        }
        catch (Exception $e)
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
            TTransaction::rollback();
        }
          
    }
    public function Logout()
    {
        new TMessage('info', 'Efetue Login');
    }
    static function Sair()
    {
        TSession::setValue('Logado',False);
        TApplication::gotoPage('Login');
        
    }
}
?>