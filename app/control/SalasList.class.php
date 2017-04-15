<?php
/**
 * SalasList Listing
 * @author  <Tiago Fernando Schirmer>
 */
class SalasList extends TStandardList
{
    protected $form;     
    protected $datagrid; 
    protected $pageNavigation;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('conecta');            // defines the database
        parent::setActiveRecord('Salas');   // defines the active record
        parent::setDefaultOrder('codigo_sala', 'asc');         // defines the default order
        parent::addFilterField('codigo_sala', '='); // add a filter field
        parent::addFilterField('nome_sala', 'like'); // add a filter field
        parent::setLimit(5);
                
        // creates the form, with a table inside
        $this->form = new TQuickForm('form_search_Salas');
        $this->form->class = 'tform'; // CSS class
        $this->form->setFormTitle('Listagem de Salas');
        
        // create the form fields
        $codigo_sala                 = new TEntry('codigo_sala');
        $nome_sala                   = new TEntry('nome_sala');
        
        // add the fields
        $this->form->addQuickField('Codigo', $codigo_sala,  100);
        $this->form->addQuickField('Nome Usuário', $nome_sala,  200);
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Salas_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction('Filtrar', new TAction(array($this, 'onSearch')), 'ico_find.png');
        $this->form->addQuickAction('Nova Sala',  new TAction(array('SalasForm', 'onEdit')), 'ico_new.png');
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        
        
        // creates the datagrid columns
        $codigo_sala = $this->datagrid->addQuickColumn('Codigo', 'codigo_sala', 'right', 100, new TAction(array($this, 'onReload')), array('order', 'codigo_sala'));
        $nome_sala = $this->datagrid->addQuickColumn('Nome Usuário', 'nome_sala', 'left', 200, new TAction(array($this, 'onReload')), array('order', 'nome_sala'));
        
        // create the datagrid actions
        $edit_action   = new TDataGridAction(array('SalasForm', 'onEdit'));
        $delete_action = new TDataGridAction(array($this, 'onDelete'));
        
        // add the actions to the datagrid
        $this->datagrid->addQuickAction(_t('Edit'), $edit_action, 'codigo_sala', 'ico_edit.png');
        $this->datagrid->addQuickAction(_t('Delete'), $delete_action, 'codigo_sala', 'ico_delete.png');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // create the page container
        $container = TVBox::pack( $this->form, $this->datagrid, $this->pageNavigation);
        parent::add($container);
    }
}
