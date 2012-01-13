 <?php
class ClubsController extends AppController {

	var $name = 'Clubs';
    var $scaffold;
    public $paginate = array(
        'limit' => 99,
    );


   function beforeFilter()
	{
		$this->Auth->allow('index');
	}
	
	function index() 
	{
	   $clubs = $this->paginate();
	   
	   if (isset($this->params['requested'])) {
	      return $clubs;
	   } else {
	      $this->set('clubs', $clubs);
      }
   }
}
?>
