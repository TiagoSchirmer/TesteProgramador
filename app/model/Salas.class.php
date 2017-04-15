<?php
/**
 * Salas Active Record
 * @author  <Tiago Fernando Schirmer>
 */
class Salas extends TRecord
{
    const TABLENAME = 'salas';
    const PRIMARYKEY= 'codigo_sala';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome_sala');
    }


}
