<?php
/**
 * ReservasForm Registration
 * @author  <your name here>
 */
class ReservasForm extends TPage
{
    protected $form; // form
    private $hora_reserva;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TForm('form_Reservas');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 500px';
        
        // add a table inside form
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        // add a row for the form title
        $row = $table->addRow();
        $row->class = 'tformtitle'; // CSS class
        $row->addCell( new TLabel('Reservas') )->colspan = 2;
        


        // create the form fields
        $codigo_reserva                 = new TEntry('codigo_reserva');
        $codigo_sala                    = new TDBSeekButton('codigo_sala','conecta','form_Reservas','Salas','nome_sala','codigo_sala','nome_sala');
        $nome_sala                      = new TEntry('nome_sala');
        $dia_reserva                    = new TDate('dia_reserva');
        $hora_reserva                   = new TCombo('hora_reserva');
        $codigo_usuario                 = new TEntry('codigo_usuario');

        
        

        // define the sizes
        $codigo_reserva->setSize(100);
        $codigo_sala->setSize(55);
        $nome_sala->setSize(200);
        $dia_reserva->setSize(258);
        $hora_reserva->setSize(280);
        $codigo_usuario->setSize(280);
        
        $codigo_reserva->setEditable(False);
        $nome_sala->setEditable(False);
        $codigo_usuario->setEditable(False);
        $codigo_usuario->setValue(TSession::getValue('nome_usuario'));
        $dia_reserva->setMask('dd/mm/yyyy'); 
    //    var_dump(TSession::getValue('nome_usuario'));
                
        $exitAction = new TAction(array($this, 'exitAction'));
        $dia_reserva->setExitAction($exitAction);
        

        // validations
        $dia_reserva->addValidation('Dia', new TRequiredValidator);
        $hora_reserva->addValidation('Hora', new TRequiredValidator);


        // add one row for each form field
        $table->addRowSet( new TLabel('Codigo:'), $codigo_reserva );
        $table->addRowSet( new TLabel('Sala:'), array($codigo_sala,$nome_sala) );
        $table->addRowSet( $label_dia_reserva = new TLabel('Dia:'), $dia_reserva );
        $label_dia_reserva->setFontColor('#FF0000');
        $table->addRowSet( $label_hora_reserva = new TLabel('Hora Disponivel:'), $hora_reserva );
        $label_hora_reserva->setFontColor('#FF0000');
        $table->addRowSet( new TLabel('Usuario:'), $codigo_usuario );


        $this->form->setFields(array($codigo_reserva,$codigo_sala,$dia_reserva,$hora_reserva,$codigo_usuario));


        // create the form actions
        $save_button = TButton::create('save', array($this, 'onSave'), _t('Save'), 'ico_save.png');
        $new_button  = TButton::create('new',  array($this, 'onEdit'), _t('New'),  'ico_new.png');
        
        $this->form->addField($save_button);
        $this->form->addField($new_button);
        
        $buttons_box = new THBox;
        $buttons_box->add($save_button);
        $buttons_box->add($new_button);
        
        // add a row for the form action
        $row = $table->addRow();
        $row->class = 'tformaction'; // CSS class
        $row->addCell($buttons_box)->colspan = 2;
        
        parent::add($this->form);
    }

    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    function onSave()
    {
        try
        {
            TTransaction::open('conecta'); // open a transaction
            
            // get the form data into an active record Reservas
            $object = $this->form->getData('Reservas');
            $this->form->validate(); // form validation
            $object->codigo_usuario = TSession::getValue('codigo_usuario');
           // var_dump($object);
            $object->store(); // stores the object
            
            $this->form->setData($object); // keep form data
            TTransaction::close(); // close the transaction
            
            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                $key=$param['key'];  // get the parameter $key
                TTransaction::open('conecta'); // open a transaction
                $object = new Reservas($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
     public static function exitAction($param)
    {
        try
        {
            TTransaction::open('conecta');
            
            $Criteria = new TCriteria;
            $Criteria->add(new TFilter('codigo_sala','=',$param['codigo_sala']));
            $object = new TRepository('Reservas');
            $dados = $object->load();
            foreach($dados as $dado)
            {
                var_dump($dado->codigo_sala);
            }
            
            if($param["codigo_sala"])
            {
                $Horarios  = array('07:00 - 08:00' => '07:00 - 08:00',
                                   '08:00 - 09:00' => '08:00 - 09:00',
                                   '09:00 - 10:00' => '09:00 - 10:00',
                                   '10:00 - 11:00' => '10:00 - 11:00',
                                   '11:00 - 12:00' => '11:00 - 12:00',
                                   '12:00 - 13:00' => '12:00 - 13:00',
                                   '13:00 - 14:00' => '13:00 - 14:00',
                                   '14:00 - 15:00' => '14:00 - 15:00',
                                   '15:00 - 16:00' => '15:00 - 16:00',
                                   '16:00 - 17:00' => '16:00 - 17:00',
                                   '17:00 - 18:00' => '17:00 - 18:00',
                                   '18:00 - 19:00' => '18:00 - 19:00',
                                   '19:00 - 20:00' => '19:00 - 20:00');
              
                TCombo::reload('form_Reservas','hora_reserva',$Horarios);
                
                
            }
            else
            {
                new TMessage('info','Selecione uma Sala');
            }
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
       
    }
    public function show()
    {
        parent::show();
        $dados = new stdClass();
        $dados->codigo_usuario = TSession::getValue('nome_usuario');
        TForm::sendData('form_Reservas',$dados);
    
    }
}
