<?php
/**
 * Reservas Active Record
 * @author  <Tiago Fernando Schirmer>
 */
class Reservas extends TRecord
{
    const TABLENAME = 'reservas';
    const PRIMARYKEY= 'codigo_reserva';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $salas;
    private $usuarios;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('hora_reserva');
        parent::addAttribute('dia_reserva');
        parent::addAttribute('codigo_usuario');
        parent::addAttribute('codigo_sala');
    }

    
    /**
     * Method set_salas
     * Sample of usage: $reservas->salas = $object;
     * @param $object Instance of Salas
     */
    public function set_salas(Salas $object)
    {
        $this->salas = $object;
        $this->codigo_salas = $object->id;
    }
    
    /**
     * Method get_salas
     * Sample of usage: $reservas->salas->attribute;
     * @returns Salas instance
     */
    public function get_salas()
    {
        // loads the associated object
        if (empty($this->salas))
            $this->salas = new Salas($this->codigo_sala);
        // returns the associated object
        return $this->salas;
    }
    
    
    /**
     * Method set_usuarios
     * Sample of usage: $reservas->usuarios = $object;
     * @param $object Instance of Usuarios
     */
    public function set_usuarios(Usuarios $object)
    {
        $this->usuarios = $object;
        $this->codigo_usuarios = $object->id;
    }
    
    /**
     * Method get_usuarios
     * Sample of usage: $reservas->usuarios->attribute;
     * @returns Usuarios instance
     */
    public function get_usuarios()
    {
        // loads the associated object
        if (empty($this->usuarios))
            $this->usuarios = new Usuarios($this->codigo_usuario);
    
        // returns the associated object
        return $this->usuarios;
    }
    


}
