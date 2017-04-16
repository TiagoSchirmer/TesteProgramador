<?php
/**
 * SalasForm Registration
 * @author  <Tiago Fernando Schirmer>
 */
class SalasForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TForm('form_Salas');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 500px';
        
        // add a table inside form
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        // add a row for the form title
        $row = $table->addRow();
        $row->class = 'tformtitle'; // CSS class
        $row->addCell( new TLabel('Salas') )->colspan = 2;
        


        // create the form fields
        $codigo_sala                 = new TEntry('codigo_sala');
        $nome_sala                   = new TEntry('nome_sala');
        
        // define the sizes
        $codigo_sala->setSize(100);
        $codigo_sala->setEditable(False);
        $nome_sala->setSize(200);
        
        // add one row for each form field
        $table->addRowSet( new TLabel('Codigo:'), $codigo_sala );
        $table->addRowSet( new TLabel('Nome:'), $nome_sala );
        
        $this->form->setFields(array($codigo_sala,$nome_sala));


        // create the form actions
        $save_button    = TButton::create('save', array($this, 'onSave'), 'Salvar', 'ico_save.png');
        $cancel_button  = TButton::create('cancel',  array($this, 'onCancel'), 'Cancelar',  'ico_close.png');
        
        $this->form->addField($save_button);
        $this->form->addField($cancel_button);
        
        $buttons_box = new THBox;
        $buttons_box->add($save_button);
        $buttons_box->add($cancel_button);
        
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
            
            // get the form data into an active record Salas
            $object = $this->form->getData('Salas');
            $this->form->validate(); // form validation
            $object->store(); // stores the object
            $this->form->setData($object); // keep form data
            TTransaction::close(); // close the transaction
            
            // Load SalassList
            TApplication::gotoPage('SalasList');
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
                $object = new Salas($key); // instantiates the Active Record
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
    
    /**
     * method onCancel()
     * Executed whenever the user clicks at the cancel button
     */
    function onCancel()
    {
        TApplication::gotoPage('SalasList');
    }
}


?>