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
            // create the HTML Renderer
            $this->html = new THtmlRenderer('app/resources/gen00.html');
            $this->html->enableSection('main');
            
            $pagestep = GenStepHelper::getStepPage(GenStepHelper::STEP00);
            
            $frm = new TFormDin(Message::GEN00_TITLE);

            $html = '<h3>Campos:</h3>
            <ul>
                <li>Sigla do Sistema: A sigla do sistema é o ID do sistema. Será utiliza para criar a pasta do novo sistema. Por padrão aparecerá no canto superior esquerdo</li>
                <li>Versão do sistema: É número da versão. É recomendável utilizar <a href="https://semver.org/lang/pt-BR/" target="_blank">o versionamento semântico</a></li>
                <li>Nome do sistema: É descrição completa do nome do sistema ou o que significa a sigla</li>
            </ul>';
            $frm->addHtmlField('aviso',$html,null,null);
            //$formDin->addSelectField('DBMS', 'Escolha o tipo de Banco de Dados:', true, $dbType, null, null, null, null, null, null, ' ', 0);
            $dbType = FormDinHelper::getListDBMS();
            $frm->addSelectField('DBMS', 'Escolha o tipo de Banco de Dados:', true, $dbType);
            $frm->addTextField('GEN_SYSTEM_ACRONYM','Sigla do Sistema', 50, true);
            $frm->addMaskField('GEN_SYSTEM_VERSION', 'Versão do sistema',true,'9.9.9');
            $frm->addTextField('GEN_SYSTEM_NAME', 'Nome do sistem', 50, true);

            $this->form = $frm->show();


            // add form actions
            // O Adianti permite a Internacionalização - A função _t('string') serve
            //para traduzir termos no sistema. Veja ApplicationTranslator escrevendo
            //primeiro em ingles e depois traduzindo
            $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'far:check-circle green');
            $this->form->addActionLink(_t('Clear'),  new TAction([$this, 'clear']), 'fa:eraser red');


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

            $data = $this->form->getData();
            $this->form->setData($data);
    
            //Função do FormDin para Debug
            FormDinHelper::d($param,'$param');
            FormDinHelper::debug($data,'$data');
            FormDinHelper::debug($_REQUEST,'$_REQUEST');

            $GEN_SYSTEM_ACRONYM = strtolower($frm->get('GEN_SYSTEM_ACRONYM'));
            TGeneratorHelper::validateFolderName($GEN_SYSTEM_ACRONYM);
            $_SESSION[APLICATIVO]=null;
            $_SESSION[APLICATIVO]['DBMS']['TYPE']=$frm->get('DBMS');
            $_SESSION[APLICATIVO]['GEN_SYSTEM_ACRONYM']=$GEN_SYSTEM_ACRONYM;
            $_SESSION[APLICATIVO]['GEN_SYSTEM_VERSION']=$frm->get('GEN_SYSTEM_VERSION');
            $_SESSION[APLICATIVO]['GEN_SYSTEM_NAME']=$frm->get('GEN_SYSTEM_NAME');
            $_SESSION[APLICATIVO][TableInfo::TP_SYSTEM]=$frm->get(TableInfo::TP_SYSTEM);
            $_SESSION[APLICATIVO]['EASYLABEL']=$frm->get('EASYLABEL');
            $frm->redirect('gen01.php', 'Redirect realizado com sucesso.', true);

            //TSession::setValue('registration_course', ['course_id' => $param['code'],
            //'course_description' => $param['description']] );
            //AdiantiCoreApplication::loadPage('MultiStepRegistration3View');
        } catch (Exception $e) {
            $frm->setMessage($e->getMessage());
        }
    }
}