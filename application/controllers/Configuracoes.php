<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracoes extends MY_Controller {

	public function Configuracoes()  {
		parent::__construct();
		//$this->check_isvalidated();
                $this->load->model('M_configuracoes');
                $this->load->library('grocery_CRUD');
               
	}
	
        public function menus(){
        $this->load->model('Getuser');
        $data['title'] = "Página Inicial - Controle de Estoque ARS";
        $data['headline'] = "Controle de Estoque";
        $this->load->view('v_header', $data);
        $this->load->view('v_menu', $data); 
       
        
        $limite = 15;   
        $data['menus'] = $this->M_configuracoes->menus($limite, $offset);
       
        $this->load->library('pagination');
        $config['base_url'] = base_url()."configuracoes/menus/index/";
        $config['per_page'] = $limite;
        $config['total_rows'] = $this->db->get('menu')->num_rows();
        $config['uri_segment'] = 3;
        $config['first_link'] = "Primeiro";
        $config['last_link'] = "Último";
        $config['num_links'] = 5;        
        $this->pagination->initialize($config);
      
        $data['paginacao'] = $this->pagination->create_links();
       
        $this->load->view('v_listamenus', $data); 
       $this->load->view('v_adm_menus', $data);
       $this->load->view('v_footer');   
        
        }
         public function _example_output($output = null)
	{      $this->load->view('v_header');
                $this->load->view('v_menu');
		$this->load->view('v_itens_menu.php',$output);
                $this->load->view('v_footer');
	}
        public function adm_menus()
	{
            
          //  $this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	// controle de acesso
            $this->load->model('controleacesso');
            $controller="menus";
             if(Controleacesso::acesso($controller) == true){
       
            try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('menu');
			$crud->set_subject('Menus');
                        $crud->where('id_pai','1');
			$crud->required_fields('id_menu');
                        $crud->set_relation( 'pai_menu',  'menu', 'nome_menu');
                        //$crud->field_type('tipo_menu','dropdown',
                        //  array( "1"  => "Principal", "2" => "Sub-Menu"));
                        //$crud->set_relation( 'acesso_menu',  'funcoes', 'nome_funcao');
			$crud->set_relation('tipo_menu','menu_sub', 'tipo_pai' );
                        $crud->set_relation_n_n( 'acesso_menu', 'acesso_menu','funcoes', 'menu_id','funcao_id' ,'nome_funcao');
                        $crud->columns('nome_menu','apelido','acesso_menu');
                        $crud->display_as('nome_menu','Nome do menu')
				 ->display_as('pai_menu','Menu Relacionado')
                                ->display_as('acesso_menu','Permissão de acesso')
				 ;
                       $crud->add_action('Menus', '', 'configuracoes/sub_menu','ui-icon-plus');
			$output = $crud->render();

			$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
                  //fim do controle de acesso
                 } 
           else{
               $this->load->view('v_header');
               $this->load->view('v_menu');
              // $this->load->view('v_acesso_negado');
           }
        }
        
         public function sub_menu($id){
          
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


        public function menu(){
        $this->load->model('Getuser');
        $data['title'] = "Página Inicial - Controle de Estoque ARS";
        $data['headline'] = "Controle de Estoque";
        $this->load->view('v_header', $data);
        $this->load->view('v_menu', $data);
    
        $this->load->model('M_configuracoes','',TRUE);
            $qry = $this->M_configuracoes->listMenu();
            $table = $this->table->generate($qry);
            $tmpl = array ( 'table_open'  => '<table id="tabela" class="table table-striped table-responsive">' );
            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;"); 
            $this->table->set_heading('Editar', 'Codigo', 'Nome', 'Categoria', 'Unidade',  'Excluir');
            $table_row = array();
		foreach ($qry->result() as $produto)
		{
			$table_row = NULL;
			$table_row[] = anchor('produto/edit/' . $produto->id_menu, '<span class="ui-icon ui-icon-pencil"></span>');
			$table_row[] = $produto->id_menu;
			$table_row[] = $produto->nome_menu;
			$table_row[] = $produto->tipo_menu;
			$table_row[] = $produto->pai_menu;
			//$table_row[] = $produto->qtd_minima;
			$table_row[] = anchor('produto/delete/' . $produto->id_menu, '<span class="ui-icon ui-icon-trash"></span>', 
							"onClick=\" return confirm('Tem certeza que deseja remover o registro?')\"");
			$this->table->add_row($table_row);
		}    
		$table = $this->table->generate();
		$data['title'] = "Listagem de Produtos - Controle de Estoque";
		$data['headline'] = "Listagem de Menus";
		$data['include'] = 'menu_list';
		$data['data_table'] = $table;
                //$this->load->view('template',$data);
		$this->load->view('template_lista', $data);
	}
    
}
        
//}