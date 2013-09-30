<?php
namespace Myapp\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Myapp\Model\Contact;
//use Myapp\Model\AbstractModel;


class MyappController extends AbstractActionController
{
    public function indexAction()
    {   $contact = new Contact();
        $contact->load(1)->getData();
        return new ViewModel(array('contact' => $contact));
    }

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
