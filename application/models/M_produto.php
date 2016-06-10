<?php 

	class M_produto extends CI_Model{
            
               public function M_produto()
        {
                // Call the CI_Model constructor
                parent::__construct();
                
        }
        

		public function addProduto($data)
		{
			$this->db->insert('produtos', $data);
		}

		public function listProduto()
		{
			//$this->db->join('apresentacao', 'apresentacao.id_apresentacao = produto.unidade');
			//$this->db->join('categoria', 'categoria.id_categoria = produto.categoria');
			$this->db->order_by('nome_produto', 'asc');
			return $this->db->get('produtos');
		}

		function getProduto($id)
		{
			return $this->db->get_where('produtos', array('id_produto'=> $id));
		}

		function updateProduto($id, $data)
		{
			$this->db->where('id_produto', $id);
			$this->db->update('produtos', $data); 
		}

		function deleteProduto($id)
		{
			$this->db->where('id_produto', $id);
			$this->db->delete('produtos'); 
		}

	}

/* End of file mproduto.php */
/* Location: ./system/application/models/mproduto.php */