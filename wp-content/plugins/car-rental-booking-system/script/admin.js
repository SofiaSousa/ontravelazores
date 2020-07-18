/******************************************************************************/
/******************************************************************************/

;(function($,doc,win) 
{
	"use strict";
    
    /**************************************************************************/
    
    if(parseInt(crbsData.jqueryui_buttonset_enable,10)!==1)
    {
        $('.to .to-radio-button,.to .to-checkbox-button').addClass('to-jqueryui-buttonset-disable');
    }
    
    /**************************************************************************/
    
    var $GET=[];
    
    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g,function() 
    {
        function decode(s) 
        {
            return(decodeURIComponent(s.split('+').join(' ')));
        }
        $GET[decode(arguments[1])]=decode(arguments[2]);
    });
    
    /**************************************************************************/
    
    if(($GET['taxonomy']==='crbs_vehicle_c') && ($GET['post_type']==='crbs_booking'))
    {
        var link=$('#menu-posts-crbs_booking').find('a[href="edit-tags.php?taxonomy=crbs_vehicle_c&post_type=crbs_booking"]');
        
        if(link.length===1)
        {
            link.addClass('current');
            link.parent('li').addClass('current');
        }
    }
        
    /**************************************************************************/
    
    var menu=$('#menu-posts-crbs_booking .wp-menu-name');
    if(menu.text()==='Car Rental Booking System')
        menu.html('Car Rental<br/>Booking System');
    
    /**************************************************************************/
    
    
})(jQuery,document,window);

/******************************************************************************/

function toCreateCustomDateTimePicker(dateFormat,timeFormat)
{
    jQuery('.to').on('focusin','.to-timepicker-custom',function()
    {
        var width=jQuery(this).outerWidth();

        jQuery(this).timepicker(
        { 
            timeFormat                                                      :   timeFormat,
            appendTo                                                        :   jQuery(this).parent()
        });

        jQuery(this).on('showTimepicker',function()
        {
            jQuery(this).next('.ui-timepicker-wrapper').width(width);
        });
    }); 

    jQuery('.to').on('focusin','.to-datepicker-custom',function()
    {
        jQuery(this).datepicker(
        { 
            inline                                                          :	true,
            dateFormat                                                      :	dateFormat,
            prevText                                                        :   '&#8592;',
            nextText                                                        :   '&#8594;'
        });
    });        
};

/******************************************************************************/

function toCreateAutocomplete(field)
{
    jQuery(field).each(function()
    {
        var $field=jQuery(this);
        var id=(new CRBSHelper()).getRandomString(16);
        
        $field.attr('id',id).on('keypress',function(e)
        {
            if(e.which===13)
            {
                e.preventDefault();
                return(false);
            }
        });
        
        $field.on('change',function()
        {
            if(!jQuery.trim(jQuery(this).val()).length)
            {
                var name=jQuery(this).attr('name');
                jQuery('input[name="'+name+'_coordinate_lat"]').val('');
                jQuery('input[name="'+name+'_coordinate_lng"]').val('');                   
            }
        });

        var autocomplete=new google.maps.places.Autocomplete(document.getElementById(id));
        autocomplete.addListener('place_changed',function()
        {
            var name=$field.attr('name');
            var place=autocomplete.getPlace();
            
            jQuery('input[name="'+name+'_coordinate_lat"]').val(place.geometry.location.lat());
            jQuery('input[name="'+name+'_coordinate_lng"]').val(place.geometry.location.lng());
        });
    });
};

/******************************************************************************/
/******************************************************************************/