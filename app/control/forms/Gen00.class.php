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
            
            $formDin = new TFormDin('Configurações do PHP e informações iniciais');
            //$formDin->addSelectField('DBMS', 'Escolha o tipo de Banco de Dados:', true, $dbType, null, null, null, null, null, null, ' ', 0);
            $dbType = FormDinHelper::getListDBMS();
            $formDin->addSelectField('DBMS', 'Escolha o tipo de Banco de Dados:', true, $dbType);
            $formDin->addTextField('GEN_SYSTEM_ACRONYM','Sigla do Sistema', 50, true);
            $formDin->addTextField('GEN_SYSTEM_NAME', 'Nome do sistem', 50, true);

            $this->form = $formDin->getAdiantiObj();

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