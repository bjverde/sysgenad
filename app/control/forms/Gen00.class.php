<?php
class Gen00 extends TPage
{
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
            
            $frm = new TFormDin('Configurações do PHP e informações iniciais');
            //$formDin->addSelectField('DBMS', 'Escolha o tipo de Banco de Dados:', true, $dbType, null, null, null, null, null, null, ' ', 0);
            $dbType = FormDinHelper::getListDBMS();
            $frm->addSelectField('DBMS', 'Escolha o tipo de Banco de Dados:', true, $dbType);
            $frm->addTextField('GEN_SYSTEM_ACRONYM','Sigla do Sistema', 50, true);
            //$frm->addTextField('GEN_SYSTEM_VERSION', 'Versão do sistema', 10, true, 10, '0.0.0');
            $frm->addMaskField('GEN_SYSTEM_VERSION', 'Versão do sistema',true,'9.9.9');
            $frm->addTextField('GEN_SYSTEM_NAME', 'Nome do sistem', 50, true);

            $this->form = $frm->getAdiantiObj();

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
    
    function loadPage()
    {}
}