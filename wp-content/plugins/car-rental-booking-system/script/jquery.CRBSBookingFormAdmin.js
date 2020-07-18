/******************************************************************************/
/******************************************************************************/

;(function($,doc,win) 
{
	"use strict";
	
	var CRBSBookingFormAdmin=function(object,option)
	{
		/**********************************************************************/
		
        var $self=this;
		var $this=$(object);
        
		var $optionDefault;
		var $option=$.extend($optionDefault,option);
        
		/**********************************************************************/
		
        this.init=function()
        {
    
        };
        
        /**********************************************************************/
        
        this.googleMapAutocompleteCreate=function(text)
        {
            var id=$(text).attr('id');
            
            text.on('keypress',function(e)
            {
                if(e.which===13)
                {
                    e.preventDefault();
                    return(false);
                }
            });
            
            text.on('change',function()
            {
                var helper=new CRBSHelper();
                
                if(helper.isEmpty($(this).val()))
                {
                    text.siblings('input[type="hidden"]').val('');              
                }
            });
            
            var autocomplete=new google.maps.places.Autocomplete(document.getElementById(id));
            autocomplete.addListener('place_changed',function(id)
            {
                var place=autocomplete.getPlace();
                if(typeof(place.geometry)!=='undefined')
                {
                    text.siblings('.to-coordinate-lat').val(place.geometry.location.lat());
                    text.siblings('.to-coordinate-lng').val(place.geometry.location.lng());
                }
                else
                {
                    text.siblings('.to-coordinate-lat').val('');
                    text.siblings('.to-coordinate-lng').val('');                    
                }
            });             
        };

        /**********************************************************************/
	};
	
	/**************************************************************************/
	
	$.fn.CRBSBookingFormAdmin=function(option) 
	{
        var bookingForm=new CRBSBookingFormAdmin(this,option);
        return(bookingForm);            
	};
	
	/**************************************************************************/

})(jQuery,document,window);

/******************************************************************************/
/******************************************************************************/