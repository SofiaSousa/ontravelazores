<?php

/******************************************************************************/
/******************************************************************************/

class CRBSWidget extends WP_Widget 
{
	/**************************************************************************/
	
    function __construct($id,$name,$widget_options,$control_options) 
	{
		parent::__construct($id,$name,$widget_options,$control_options);
    }
	
	/**************************************************************************/
	
	function register($class)
	{
        add_action('widgets_init',function() use ($class) { register_widget($class); });
	}
	
	/**************************************************************************/
	
	function widget($argument,$instance)
	{
		$data=array();
		$Validation=new CRBSValidation();
		
		if(is_array($argument['_data']))
		{
			foreach($argument['_data'] as $index=>$value)
				$data[$index]=$value;
		}
		
		$title=null;
		
		if(isset($instance['title']))
			$title=apply_filters('widget_title',$instance['title']);
		
		$data['html']['start']=$argument['before_widget'];
		if($Validation->isNotEmpty($title)) 
			$data['html']['start'].=$argument['before_title'].$title.$argument['after_title'];
		
		$data['html']['stop']=$argument['after_widget'];

		$data['instance']=$instance;

		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.$argument['_data']['file']);
		echo apply_filters('widget_text',$Template->output(true));
	}
	
	/**************************************************************************/
	
	function form($instance)
	{
		$data=array();
		
		if(is_array($instance['_data']['field']))
		{
			foreach($instance['_data']['field'] as $value)
			{
				CRBSHelper::removeUIndex($instance,$value);
				CRBSHelper::removeUIndex($data['option'][$value],'id','name','value');

				$data['option'][$value]['id']=$this->get_field_id($value);
				$data['option'][$value]['name']=$this->get_field_name($value);
				$data['option'][$value]['value']=$instance[$value];
			}
		}

		$Template=new CRBSTemplate($data,PLUGIN_CRBS_TEMPLATE_PATH.'admin/'.$instance['_data']['file']);
		echo $Template->output(true);
	}
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/