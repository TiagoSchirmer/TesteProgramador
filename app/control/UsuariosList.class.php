<?php
/**
 * UsuariosList Listing
 * @author  <Tiago Fernando Schirmer>
 */
class UsuariosList extends TStandardList
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
        parent::setActiveRecord('Usuarios');   // defines the active record
        parent::setDefaultOrder('codigo_usuario', 'asc');         // defines the default order
        parent::addFilterField('codigo_usuario', '='); // add a filter field
        parent::addFilterField('nome_usuario', 'like'); // add a filter field
        parent::addFilterField('login_usuario', 'like'); // add a filter field
        
        // creates the form, with a table inside
        $this->form = new TQuickForm('form_search_Usuarios');
        $this->form->class = 'tform'; // CSS class
        $this->form->setFormTitle('Listagem de Usuarios');
        
        // create the form fields
        $codigo_usuario                 = new TEntry('codigo_usuario');
        $nome_usuario                   = new TEntry('nome_usuario');
        $login_usuario                  = new TEntry('login_usuario');

        // add the fields
        $this->form->addQuickField('Codigo', $codigo_usuario,  100);
        $this->form->addQuickField('Nome Usuário', $nome_usuario,  200);
        $this->form->addQuickField('Login', $login_usuario,  200);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Usuarios_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction('Filtrar', new TAction(array($this, 'onSearch')), 'ico_find.png');
        $this->form->addQuickAction('Novo Usuario',  new TAction(array('UsuariosForm', 'onEdit')), 'ico_new.png');
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        
        // creates the datagrid columns
        $codigo_usuario = $this->datagrid->addQuickColumn('Codigo', 'codigo_usuario', 'right', 100, new TAction(array($this, 'onReload')), array('order', 'codigo_usuario'));
        $nome_usuario = $this->datagrid->addQuickColumn('Nome Usuário', 'nome_usuario', 'left', 200, new TAction(array($this, 'onReload')), array('order', 'nome_usuario'));
        $login_usuario = $this->datagrid->addQuickColumn('Login', 'login_usuario', 'left', 100);

        // create the datagrid actions
        $edit_action   = new TDataGridAction(array('UsuariosForm', 'onEdit'));
        $delete_action = new TDataGridAction(array($this, 'onDelete'));
        
        // add the actions to the datagrid
        $this->datagrid->addQuickAction(_t('Edit'), $edit_action, 'codigo_usuario', 'ico_edit.png');
        $this->datagrid->addQuickAction(_t('Delete'), $delete_action, 'codigo_usuario', 'ico_delete.png');
        
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
