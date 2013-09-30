<?php
namespace Myapp\Model;

require_once("AbstractModel.php");
use AbstractModel;

Class Contact extends AbstractModel
{	protected $_table = "contacts";
	protected $_pk	  = "id";
        
        
}