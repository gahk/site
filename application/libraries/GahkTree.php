<?php
class GahkTree
{

	// Nicholas Swiatecki <Nicholas@Swiatecki.com>
	// modeled around https://github.com/mbostock/d3/wiki/Tree-Layout
    // property declaration
    public $name = "";
    public $children = [];

    function __construct($nodeName) {
       $this->name = $nodeName;
   	}

    // method declaration
    public function getName() {
        return $this->name;
    }

    public function addChild(GahkTree $child){

    	array_push($this->children, $child);

    }


}
?>