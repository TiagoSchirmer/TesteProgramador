<?php
/**
 * ReservasList Listing
 * @author  <Tiago Fernando Schirmer>
 */
class ReservasList extends TPage
{
    private $form;     // registration form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TForm('form_search_Reservas');
        $this->form->class = 'tform'; // CSS class
        
        // creates a table
        $table = new TTable;
        $table-> width = '100%';
        $this->form->add($table);
        
        // add a row for the form title
        $row = $table->addRow();
        $row->class = 'tformtitle'; // CSS class
        $row->addCell( new TLabel('Listagem das Reservas') )->colspan = 2;
        

        // create the form fields
        $codigo_reserva                 = new TEntry('codigo_reserva');
        $hora_reserva                   = new TCombo('hora_reserva');
        $dia_reserva                    = new TDate('dia_reserva');
        $codigo_usuario                 = new TDBSeekButton('codigo_usuario','conecta','form_search_Reservas','Usuarios','nome_usuario','codigo_usuario','nome_usuario');
        $nome_usuario                   = new TEntry('nome_usuario');
        $codigo_sala                    = new TDBSeekButton('codigo_sala','conecta','form_search_Reservas','Salas','nome_sala','codigo_sala','nome_sala');
        $nome_sala                      = new TEntry('nome_sala');

        // define the sizes
        $codigo_reserva->setSize(60);
        $hora_reserva->setSize(260);
        $dia_reserva->setSize(260);
        $codigo_usuario->setSize(60);
        $nome_usuario->setSize(200);
        $codigo_sala->setSize(60);
        $nome_sala->setSize(200);
        $nome_usuario->setEditable(False);
        $nome_sala->setEditable(False);
        $dia_reserva->setMask('dd/mm/yyyy');
        
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
        $hora_reserva->addItems($Horarios);

        // add one row for each form field
        $table->addRowSet( new TLabel('Codigo:'), $codigo_reserva );
        $table->addRowSet( new TLabel('Hora:'), $hora_reserva );
        $table->addRowSet( new TLabel('Dia:'), $dia_reserva );
        $table->addRowSet( new TLabel('Usuário:'), array($codigo_usuario,$nome_usuario ));
        $table->addRowSet( new TLabel('Sala:'), array($codigo_sala,$nome_sala) );


        $this->form->setFields(array($codigo_reserva,$hora_reserva,$dia_reserva,$codigo_usuario,$codigo_sala, $nome_sala, $nome_usuario ));


        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Reservas_filter_data') );
        
        // create two action buttons to the form
        $find_button = TButton::create('find', array($this, 'onSearch'), 'Filtrar', 'ico_find.png');
        $new_button  = TButton::create('new',  array('ReservasForm', 'onEdit'),'Nova Reserva', 'ico_new.png');
        
        $this->form->addField($find_button);
        $this->form->addField($new_button);
        
        $buttons_box = new THBox;
        $buttons_box->add($find_button);
        $buttons_box->add($new_button);
        
        // add a row for the form action
        $row = $table->addRow();
        $row->class = 'tformaction'; // CSS class
        $row->addCell($buttons_box)->colspan = 2;
        
        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $codigo_reserva   = new TDataGridColumn('codigo_reserva', 'Codigo', 'right', 60);
        $hora_reserva   = new TDataGridColumn('hora_reserva', 'Hora', 'left', 150);
        $dia_reserva   = new TDataGridColumn('dia_reserva', 'Dia', 'left', 200);
        $codigo_usuario   = new TDataGridColumn('usuarios->nome_usuario', 'Usuário', 'left', 200);
        $codigo_sala   = new TDataGridColumn('salas->nome_sala', 'Sala', 'left', 200);


        // add the columns to the DataGrid
        $this->datagrid->addColumn($codigo_reserva);
        $this->datagrid->addColumn($hora_reserva);
        $this->datagrid->addColumn($dia_reserva);
        $this->datagrid->addColumn($codigo_usuario);
        $this->datagrid->addColumn($codigo_sala);


        // creates the datagrid column actions
        $order_codigo_reserva= new TAction(array($this, 'onReload'));
        $order_codigo_reserva->setParameter('order', 'codigo_reserva');
        $codigo_reserva->setAction($order_codigo_reserva);

        $order_hora_reserva= new TAction(array($this, 'onReload'));
        $order_hora_reserva->setParameter('order', 'hora_reserva');
        $hora_reserva->setAction($order_hora_reserva);


        
       
        
        $action2 = new TDataGridAction(array($this, 'onDelete'));
        $action2->setLabel(_t('Delete'));
        $action2->setImage('ico_delete.png');
        $action2->setField('codigo_reserva');
        
        $this->datagrid->addAction($action2);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // create the page container
        $container = TVBox::pack( $this->form, $this->datagrid, $this->pageNavigation);
        parent::add($container);
    }
    
    /**
     * method onInlineEdit()
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    function onInlineEdit($param)
    {
        try
        {
            // get the parameter $key
            $field = $param['field'];
            $key   = $param['key'];
            $value = $param['value'];
            
            TTransaction::open('conecta'); // open a transaction with database
            $object = new Reservas($key); // instantiates the Active Record
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * method onSearch()
     * Register the filter in the session when the user performs a search
     */
    function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue('ReservasList_filter_codigo_reserva',   NULL);
        TSession::setValue('ReservasList_filter_hora_reserva',   NULL);
        TSession::setValue('ReservasList_filter_dia_reserva',   NULL);
        TSession::setValue('ReservasList_filter_codigo_usuario',   NULL);
        TSession::setValue('ReservasList_filter_codigo_sala',   NULL);

        if (isset($data->codigo_reserva) AND ($data->codigo_reserva)) {
            $filter = new TFilter('codigo_reserva', '=', "$data->codigo_reserva"); // create the filter
            TSession::setValue('ReservasList_filter_codigo_reserva',   $filter); // stores the filter in the session
        }


        if (isset($data->hora_reserva) AND ($data->hora_reserva)) {
            $filter = new TFilter('hora_reserva', 'like', "%{$data->hora_reserva}%"); // create the filter
            TSession::setValue('ReservasList_filter_hora_reserva',   $filter); // stores the filter in the session
        }


        if (isset($data->dia_reserva) AND ($data->dia_reserva)) {
            $filter = new TFilter('dia_reserva', 'like', "%{$data->dia_reserva}%"); // create the filter
            TSession::setValue('ReservasList_filter_dia_reserva',   $filter); // stores the filter in the session
        }


        if (isset($data->codigo_usuario) AND ($data->codigo_usuario)) {
            $filter = new TFilter('codigo_usuario', '=', "$data->codigo_usuario"); // create the filter
            TSession::setValue('ReservasList_filter_codigo_usuario',   $filter); // stores the filter in the session
        }


        if (isset($data->codigo_sala) AND ($data->codigo_sala)) {
            $filter = new TFilter('codigo_sala', '=', "$data->codigo_sala"); // create the filter
            TSession::setValue('ReservasList_filter_codigo_sala',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Reservas_filter_data', $data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * method onReload()
     * Load the datagrid with the database objects
     */
    function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'conecta'
            TTransaction::open('conecta');
            
            // creates a repository for Reservas
            $repository = new TRepository('Reservas');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'codigo_reserva';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('ReservasList_filter_codigo_reserva')) {
                $criteria->add(TSession::getValue('ReservasList_filter_codigo_reserva')); // add the session filter
            }


            if (TSession::getValue('ReservasList_filter_hora_reserva')) {
                $criteria->add(TSession::getValue('ReservasList_filter_hora_reserva')); // add the session filter
            }


            if (TSession::getValue('ReservasList_filter_dia_reserva')) {
                $criteria->add(TSession::getValue('ReservasList_filter_dia_reserva')); // add the session filter
            }


            if (TSession::getValue('ReservasList_filter_codigo_usuario')) {
                $criteria->add(TSession::getValue('ReservasList_filter_codigo_usuario')); // add the session filter
            }


            if (TSession::getValue('ReservasList_filter_codigo_sala')) {
                $criteria->add(TSession::getValue('ReservasList_filter_codigo_sala')); // add the session filter
            }

            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                  
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method onDelete()
     * executed whenever the user clicks at the delete button
     * Ask if the user really wants to delete the record
     */
    function onDelete($param)
    {
    
        try
        {
            TTransaction::open('conecta');
            $Dados = new Reservas($param['key']);
            if($Dados->codigo_usuario == TSession::getValue('codigo_usuario'))
            {
                  // define the delete action
                  $action = new TAction(array($this, 'Delete'));
                  $action->setParameters($param); // pass the key parameter ahead
        
                  // shows a dialog to the user
                  new TQuestion(TAdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
            }
            else
            {
                new TMessage('info','Exclusão de Agendamento Somente pelo proprio usuario');
            }
            
            TTransaction::close();
        
        }
        catch (Exception $e)
        {
              new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
              TTransaction::rollback(); // undo all pending operations
        
        }
      
    }
    
    /**
     * method Delete()
     * Delete a record
     */
    function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('conecta'); // open a transaction with database
            $object = new Reservas($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            $this->onReload( $param ); // reload the listing
            new TMessage('info', TAdiantiCoreTranslator::translate('Record deleted')); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
