<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_menus extends MY_Controller {

	public function __construct(){
		parent::__construct();
		//$this->check_isvalidated();
                $this->load->model('M_configuracoes');
                $this->load->helper('url');
                $this->load->library('grocery_CRUD');
               
	}
	
     
         public function _example_output($output = null)
                
	{  $this->load->model('Getuser');
        $data['title'] = "Página Inicial - Controle de Estoque ARS";
        $data['headline'] = "Controle de Estoque";
        $this->load->view('v_header', $data);    
             
          //   $this->load->view('v_header');
                $this->load->view('v_menu');
		$this->load->view('v_itens_menu.php',$output);
                $this->load->view('v_footer');
	}
        

        
        
         public function index($id){
          
            $this->load->model('controleacesso');
            $controller="menus";
             if(Controleacesso::acesso($controller) == true){
       
            try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('menu');
			$crud->set_subject('Menus');
                        $crud->where('pai_menu',$id);
			$crud->required_fields('id_menu');
                        $crud->order_by('nome_menu');
                        // $crud->set_relation( 'pai_menu',  'menu', 'nome_menu');
                        //$crud->set_relation( 'acesso_menu',  'funcoes', 'nome_funcao');
			
                        $crud->set_relation_n_n( 'acesso_menu', 'acesso_menu','funcoes', 'menu_id','funcao_id' ,'nome_funcao');
                        $crud->columns('nome_menu','apelido',  'acesso_menu');
                        $crud->display_as('nome_menu','Nome do menu')
				 ->display_as('pai_menu','Menu Relacionado')
                                ->display_as('acesso_menu','Permissão de acesso')
				 ;
                        $crud->unset_add();
			$output = $crud->render();

			$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
                  //fim do controle de acesso
                 } 
           else{
               $this->load->view('admin/v_header_adm');
               $this->load->view('admin/v_menu');
               echo 'Acesso negado';
              // $this->load->view('v_acesso_negado');
           }
            
        }


}
        
//}