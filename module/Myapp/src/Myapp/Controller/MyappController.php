<?php
/**
* This class allows the view to load the Contact class
* 
* @package    MyappController
* @author      paul heika <bike8@hotmail.com>
*/
namespace Myapp\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Myapp\Model\Contact;


class MyappController extends AbstractActionController
{
    public function indexAction()
    {   $contact = new Contact();
        $contact->load(1)->getData();
        return new ViewModel(array('contact' => $contact));
    }
    /**
     * viewAction() uses the url parameter to load the Contact and 
     * to store the result. 
     */
    public function viewAction()
    {
        
        if (isset($_GET["id"])) {
            $id = htmlspecialchars($_GET["id"]);
        } else {
            $id = "";
        }
        $contact = new Contact();
        $contact->load($id)->getData();
        return new ViewModel(array('contact' => $contact));
    }


}
