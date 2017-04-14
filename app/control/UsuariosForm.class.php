<?php
/**
 * UsuariosForm Registration
 * @author  <Tiago Fernando Schirmer>
 */
class UsuariosForm extends TPage
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
        $this->form = new TForm('form_Usuarios');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 500px';
        
        // add a table inside form
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        // add a row for the form title
        $row = $table->addRow();
        $row->class = 'tformtitle'; // CSS class
        $row->addCell( new TLabel('Usuarios') )->colspan = 2;
        


        // create the form fields
        $codigo_usuario                 = new TEntry('codigo_usuario');
        $nome_usuario                   = new TEntry('nome_usuario');
        $login_usuario                  = new TEntry('login_usuario');
        $senha_usuario                  = new TEntry('senha_usuario');


        // define the sizes
        $codigo_usuario->setSize(100);
        $nome_usuario->setSize(200);
        $login_usuario->setSize(200);
        $senha_usuario->setSize(200);



        // add one row for each form field
        $table->addRowSet( new TLabel('codigo_usuario:'), $codigo_usuario );
        $table->addRowSet( new TLabel('nome_usuario:'), $nome_usuario );
        $table->addRowSet( new TLabel('login_usuario:'), $login_usuario );
        $table->addRowSet( new TLabel('senha_usuario:'), $senha_usuario );


        $this->form->setFields(array($codigo_usuario,$nome_usuario,$login_usuario,$senha_usuario));


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
            
            // get the form data into an active record Usuarios
            $object = $this->form->getData('Usuarios');
            $this->form->validate(); // form validation
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
                $object = new Usuarios($key); // instantiates the Active Record
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
}
