<?php 

trait SimpleDomValidator
{
	private function validateLibrary():void
	{
		if(!class_exists('simple_html_dom_node') && !property_exists('simple_html_dom', 'find'))
			throw new Exception("It is not simplehtmldom library");	
	}
}