<?php
class Gen02 extends TPage
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

            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP02);
            
            $frm = new TFormDin($this,Message::GEN02_TITLE);

            $frm->addGroupField('gpx1', Message::GEN02_GPX1_TITLE);
                $html = $frm->addHtmlField('conf', '');
                
                $path = TGeneratorHelper::getPathNewSystem();
                TGeneratorHelper::mkDir($path);
                $html->add(TGeneratorHelper::showMsg(true, Message::GEN02_MKDIR_SYSTEM.$path));
                TCopyFilesHelper::systemSkeletonToNewSystem();
                $html->add(TGeneratorHelper::showMsg(true, Message::GEN02_COPY_SYSTEM_SKELETON));
                TGeneratorHelper::createFileConstants();
                $html->add(TGeneratorHelper::showMsg(true, Message::GEN02_CREATED_CONSTANTS));
                TGeneratorHelper::createFileConfigDataBase();
                $html->add(TGeneratorHelper::showMsg(true, Message::GEN02_CREATED_CONFIG_DATABASE));
                //TGeneratorHelper::createFileAutoload();
                //$html->add(TGeneratorHelper::showMsg(true, Message::GEN02_CREATED_AUTOLOAD));
                //TGeneratorHelper::createFileIndex();
                //$html->add(TGeneratorHelper::showMsg(true, Message::GEN02_CREATED_INDEX));
                $html->add('<br>');
                $html->add('<br>');
                $html->add(Message::SEL_TABLES_GENERATE);              
            $frm->closeGroup();


            $listTablesAll = TGeneratorHelper::loadTablesFromDatabase();            
            $listTablesAll = ArrayHelper::convertArrayFormDin2Adianti($listTablesAll);
            FormDinHelper::debug($listTablesAll);
            $checkList = new TFormDinCheckList('gd','Lista de Tabelas',false,$listTablesAll,null);
            $checkList->addColumnHidden('idSelected','TABLE_SCHEMA','left','20%');
            $checkList->addColumn('TABLE_SCHEMA','TABLE_SCHEMA','left','20%');
            $checkList->addColumn('TABLE_NAME','TABLE_NAME','left','60%');
            $checkList->addColumn('COLUMN_QTD','COLUMN_QTD','center','10%');
            $checkList->addColumn('TABLE_TYPE','TABLE_TYPE','left','10%');
    
            $frm->addCheckList($checkList,false);

    
            $frm->setActionLink(Message::BUTTON_LABEL_BACK,'back',false,'fa:chevron-circle-left','green');
            $frm->setActionLink(_t('Clear'),'clear',false,'fa:eraser','red');
            $frm->setAction(Message::BUTTON_LABEL_GEN_STRUCTURE,'next',false,'fa:chevron-circle-right','green');

            $this->form = $frm->show();

            /*
            $grid = new TFormDinGrid($this
                                    ,'gd'               // id do gride
                                    ,'Lista de Tabelas' // titulo do gride
                                );    
            //$grid->setHeight(2500);
            $grid->addColumn('idSelected',  'Code', null, 'center');
            $grid->addColumn('TABLE_SCHEMA', 'TABLE_SCHEMA');
            $grid->addColumn('TABLE_NAME', 'TABLE_NAME');
            $grid->addColumn('COLUMN_QTD', 'COLUMN_QTD');
            $grid->addColumn('TABLE_TYPE', 'TABLE_TYPE');
            $grid->enableDefaultButtons(false);
            $this->datagrid = $grid->show();
            $panelGrid = $grid->getPanelGroupGrid();
            $this->form->addContent([$panelGrid]);
            */

            // wrap the page content using vertical box
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add( $pagestep );
            $vbox->add( $this->form );
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
        AdiantiCoreApplication::loadPage('Gen01');
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
                
            FormDinHelper::debug($param,'$param');
            FormDinHelper::debug($data,'$data');
/*
            $listTableSelected = null;
            foreach( $param as $key => $valey ) {
                if(strpos($key, 'idTableSelected') !== false){
                    $listTableSelected[]=$valey;
                }
            }
            if( CountHelper::count($listTableSelected) == 0 ){
                new TMessage('error', Message::WARNING_NO_TABLE);
            } else {
                TSysgenSession::setValue('idTableSelected',$listTableSelected);
                AdiantiCoreApplication::loadPage('Gen03'); //POG para recarregar a pagina
            }
*/
            //$this->form->setData($data);    // put the data back to the form
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    
    /**
     * Load the data into the datagrid
    function onReload()
    {
        $this->datagrid->clear();

        $listTablesAll = TGeneratorHelper::loadTablesFromDatabase();            
        $listTablesAll = ArrayHelper::convertArrayFormDin2Adianti($listTablesAll);

        foreach( $listTablesAll as $idRow => $ObjRow ) {
            // add an regular object to the datagrid
            $item = new StdClass;
            $item->idSelected  = new TCheckButton('idTableSelected'.$idRow);
            $item->idSelected->setIndexValue($ObjRow->idSelected);
            $item->TABLE_SCHEMA   = $ObjRow->TABLE_SCHEMA;
            $item->TABLE_NAME     = $ObjRow->TABLE_NAME;
            $item->COLUMN_QTD     = $ObjRow->COLUMN_QTD;
            $item->TABLE_TYPE     = $ObjRow->TABLE_TYPE;
            $this->datagrid->addItem($item);
            $this->form->addField($item->idSelected); // important!
        }

    }
    */

    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}