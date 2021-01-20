<?php 


namespace App\Repositories;



interface RepositoryInterface
{

   /**
    * collections
    */
    public function all();
    
    
    /**
    * saves model
    */
    public function save($input);


     /**
    * saves model
    */
    public function find($id);

      /**
    * updates model
    */
    public function update($id, $column, $val);
    public function isPaymentDue();
}




?>