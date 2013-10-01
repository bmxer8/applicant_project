<?php
namespace Myapp\Model;

require_once("AbstractModel.php");
use AbstractModel; //needed for Zend to see this class

Class Contact extends AbstractModel
{	protected $_table = "contacts";
	protected $_pk	  = "id";
        
        
}