<?php
/**
 * Usuarios Active Record
 * @author  <Tiago Fernando Schirmer>
 */
class Usuarios extends TRecord
{
    const TABLENAME = 'usuarios';
    const PRIMARYKEY= 'codigo_usuario';
    const IDPOLICY =  'max'; 
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome_usuario');
        parent::addAttribute('login_usuario');
        parent::addAttribute('senha_usuario');
    }


}
