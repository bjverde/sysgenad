<?php
class Gen00 extends TPage
{

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
            TSession::clear();
            
            // create the HTML Renderer
            $this->html = new THtmlRenderer('app/resources/gen00.html');
            $this->html->enableSection('main');
            
            TPage::include_css('app/resources/sysgen.css');

            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP00);
            
            $frm = new TFormDin($this,Message::GEN00_TITLE);
            
            $frm->addGroupField('gpxTpSystem', Message::GPX_TYPE_SYSTEM);
                //$frm->addHtmlField('info', null, 'ajuda/info_gen00_tpsys_pt-br.php')->setClass('htmlInfo', true);
                //$listTpSystem = TGeneratorHelper::getListTypeSystem();
                //$frm->addRadioField(TableInfo::TP_SYSTEM, Message::FIELD_TP_SYSTEM, true, $listTpSystem, null, true, TGeneratorHelper::TP_SYSTEM_FORM, 3, null, null, null, false);
                $frm->addHiddenField(TableInfo::TP_SYSTEM,TGeneratorHelper::TP_SYSTEM_FORM,true);
            $frm->closeGroup();
        
            $frm->addGroupField('gpxEasyLabel', Message::GPX_EASYLABEL);
                //$frm->addHtmlField('info', null, 'ajuda/info_gen00_easylabel_pt-br.php')->setClass('htmlInfo', true);
                //$listTpSystem = array('Y'=>'Sim','N'=>'Não');
                //$frm->addRadioField('EASYLABEL', Message::FIELD_EASY_LABEL, true, $listTpSystem, null, true, 'Y', 3, null, null, null, false);
                $frm->addHiddenField('EASYLABEL','Y',true);
            $frm->closeGroup();

            $frm->addGroupField('gpx3', Message::GEN00_GPX3_TITLE);
                $html = '<h3>Campos:</h3>
                            <ul>
                                <li>Sigla do Sistema: A sigla do sistema é o ID do sistema. Será utiliza para criar a pasta do novo sistema. Por padrão aparecerá no canto superior esquerdo</li>
                                <li>Versão do sistema: É número da versão. É recomendável utilizar <a href="https://semver.org/lang/pt-BR/" target="_blank">o versionamento semântico</a></li>
                                <li>Nome do sistema: É descrição completa do nome do sistema ou o que significa a sigla</li>
                            </ul>';
                $frm->addHtmlField('aviso',$html,null,null);            
                $dbType = TFormDinPdoConnection::getListDBMS();
                $frm->addSelectField('DBMS', 'Escolha o tipo de Banco de Dados:', true, $dbType);
                $frm->addTextField('GEN_SYSTEM_ACRONYM','Sigla do Sistema', 50, true);
                $frm->addMaskField('GEN_SYSTEM_VERSION', 'Versão do sistema',true,'9.9.9',null,'0.0.0');
                $frm->addTextField('GEN_SYSTEM_NAME', 'Nome do sistem', 50, true);
            $frm->closeGroup();
            
            // O Adianti permite a Internacionalização - A função _t('string') serve
            //para traduzir termos no sistema. Veja ApplicationTranslator escrevendo
            //primeiro em ingles e depois traduzindo
            $frm->setActionLink(_t('Clear'),'clear',false,'fa:eraser','red');
            $frm->setAction('Continuar','onSave',false,'fa:chevron-circle-right','green');
            

            $this->form = $frm->show();

            // wrap the page content using vertical box
            $vbox = new TVBox;
            $vbox->style = 'width: 100%';
            $vbox->add( $pagestep );
            $vbox->add( $this->html );
            $vbox->add( $this->form );
            parent::add($vbox);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
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

    /**
     * Load the data into the datagrid
     */
    function onReload()
    {

    }
    
    /**
     * Clear filters
     */
    public function clear()
    {
        $this->clearFilters();
        $this->onReload();
    }

    public function onSave($param)
    {
        try {
            //$data = $this->form->getData();
            //$this->form->setData($data);
    
            //Função do FormDin para Debug
            //FormDinHelper::d($param,'$param');
            //FormDinHelper::debug($data,'$data');
            //FormDinHelper::debug($_REQUEST,'$_REQUEST');
            //FormDinHelper::debug($_SESSION,'$_SESSION');

            $GEN_SYSTEM_ACRONYM = RequestHelper::get('GEN_SYSTEM_ACRONYM') ;
            TGeneratorHelper::validateFolderName($GEN_SYSTEM_ACRONYM);

            TSession::clear();
            TSession::setValue('DBMS',['TYPE'=>RequestHelper::get('DBMS')]);
            TSession::setValue('GEN_SYSTEM_ACRONYM',$GEN_SYSTEM_ACRONYM);
            TSession::setValue('GEN_SYSTEM_VERSION',RequestHelper::get('GEN_SYSTEM_VERSION'));
            TSession::setValue('GEN_SYSTEM_NAME',RequestHelper::get('GEN_SYSTEM_NAME'));
            TSession::setValue(TableInfo::TP_SYSTEM,RequestHelper::get(TableInfo::TP_SYSTEM));
            TSession::setValue('EASYLABEL',RequestHelper::get('EASYLABEL'));
            AdiantiCoreApplication::loadPage('Gen01');
        } catch (Exception $e) {
            $frm->setMessage($e->getMessage());
        }
    }
}