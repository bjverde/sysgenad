<?php
class Gen03 extends TPage
{
    protected $form; // registration form
    protected $datagrid; // listing
    protected $pageNavigation;

    // trait com onReload, onSearch, onDelete...
    use Adianti\Base\AdiantiStandardListTrait;

    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        
        try
        {   
            
            TPage::include_css('app/resources/sysgen.css');

            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP03);
            
            $frm = new TFormDin($this,Message::GEN03_TITLE);

            if( TSysgenSession::getValue(TableInfo::TP_SYSTEM) != TGeneratorHelper::TP_SYSTEM_REST ){
                $frm->addGroupField('gpx1', Message::GEN02_GPX1_TITLE);
                    $html = $frm->addHtmlField('conf', '');
                $frm->closeGroup();

                $frm->addGroupField('gpx2', Message::GPX_TYPE_CONFIG);
                    $html = $frm->addHtmlField('logType', '');
                $frm->closeGroup();

                /*
                $frm->addGroupField('gpx3',Message::GRID_LIST_FK_COLUMN);
                    $frm->addHtmlField('info', null, 'ajuda/info_gen03_typefields_pt-br.php')->setClass('htmlInfo', true);
                $frm->closeGroup();
                */
            }else{
                $html = $frm->addHtmlField('conf', '');
                $html->add('<br><b>XXXXXX</b><br>');
            }
    
            $frm->setActionLink(Message::BUTTON_LABEL_BACK,'back',false,'fa:chevron-circle-left','green');
            $frm->setActionLink(_t('Clear'),'clear',false,'fa:eraser','red');
            $frm->setAction(Message::BUTTON_LABEL_GEN_STRUCTURE,'next',false,'fa:chevron-circle-right','green');

            $this->form = $frm->show();

            $grid = new TFormDinGrid($this
                                    ,'gd'               // id do gride
                                    ,Message::GRID_LIST_FK_TITLE // titulo do gride
                                );
            //$grid->setCreateDefaultEditButton(false);
            //$grid->setCreateDefaultDeleteButton(false);
            //$grid->addRowNumColumn();
            $grid->addColumn('TABLE_SCHEMA', 'TABLE_SCHEMA');
            $grid->addColumn('TABLE_NAME', 'TABLE_NAME');
            $grid->addColumn('COLUMN_NAME', 'COLUMN_NAME');
            $grid->addColumn('DATA_TYPE', 'DATA_TYPE');
            $grid->addColumn('REFERENCED_TABLE_NAME', 'REFERENCED_TABLE_NAME');
            $grid->addColumn('REFERENCED_COLUMN_NAME', 'REFERENCED_COLUMN_NAME');
            $this->datagrid = $grid->show();
            $panelGroupGrid = $grid->getPanelGroupGrid();

            // wrap the page content using vertical box
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add( $pagestep );
            $vbox->add( $this->form );
            $vbox->add($panelGroupGrid);
            parent::add($vbox);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            FormDinHelper::debug($e,'$e');
        }
    }

    public function back()
    {
        AdiantiCoreApplication::loadPage('Gen02');
    }
    
    /**
     * Clear filters
     */
    public function clear()
    {
        $this->clearFilters();
        $this->onReload();
    }

    public function next($param)
    {
        try {
            $data = $this->form->getData(); // optional parameter: active record class
            $this->form->setData($data);    // put the data back to the form
            AdiantiCoreApplication::loadPage('Gen04'); //POG para recarregar a pagina
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
        $this->datagrid->clear();

        $listFkFieldsTableSelected = TGeneratorHelper::listFKFieldsTablesSelected();
        $listFkFieldsTableSelected = ArrayHelper::convertArrayFormDin2Adianti($listFkFieldsTableSelected);

        foreach( $listFkFieldsTableSelected as $idRow => $ObjRow ) {
            // add an regular object to the datagrid
            $item = new StdClass;
            $item->TABLE_SCHEMA   = $ObjRow->TABLE_SCHEMA;
            $item->TABLE_NAME     = $ObjRow->TABLE_NAME;
            $item->COLUMN_NAME    = $ObjRow->COLUMN_NAME;
            $item->DATA_TYPE      = $ObjRow->DATA_TYPE;
            $item->REFERENCED_TABLE_NAME      = $ObjRow->REFERENCED_TABLE_NAME;
            $item->REFERENCED_COLUMN_NAME     = $ObjRow->REFERENCED_COLUMN_NAME;
            $this->datagrid->addItem($item);
        }
    }

    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}