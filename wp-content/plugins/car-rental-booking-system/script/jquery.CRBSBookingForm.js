/******************************************************************************/
/******************************************************************************/

;(function($,doc,win)
{
	"use strict";
	
	var CRBSBookingForm=function(object,option)
	{
		/**********************************************************************/
		
        var $self=this;
		var $this=$(object);
		
		var $optionDefault;
		var $option=$.extend($optionDefault,option);
        
        var $googleMap;
        var $googleMapMarker;
		var $googleMapHeightInterval;
        
        var $startLocation;

        /**********************************************************************/
        
        this.setup=function()
        {
            var helper=new CRBSHelper();
            helper.getMessageFromConsole();
            
            $self.e('select,input[type="hidden"]').each(function()
            {
                if($(this)[0].hasAttribute('data-value'))
                    $(this).val($(this).attr('data-value'));
            });
            
            $self.init();
        };
            
        /**********************************************************************/
        
        this.init=function()
        {
            var helper=new CRBSHelper();
            
            if(helper.isMobile())
            {
                $self.e('input[name="crbs_pickup_date"]').attr('readonly','readonly');
                $self.e('input[name="crbs_return_date"]').attr('readonly','readonly');
            }
           
            /***/
            
            $(window).resize(function() 
			{
                try
                {
                    $('select').selectmenu('close');
                }
                catch(e) {}
                
                try
                {
                    $('.crbs-datepicker').datepicker('hide');
                }
                catch(e) {}
                
                try
                {
                    $('.crbs-timepicker').timepicker('hide');
                }
                catch(e) {}
                
                try
                {
                    $self.e('.ui-timepicker-wrapper').css({opacity:0});
                }
                catch(e) {}
                
                try
                {
                    var currCenter=$googleMap.getCenter();
                    google.maps.event.trigger($googleMap,'resize');
                    $googleMap.setCenter(currCenter);
                }
                catch(e) {}
			});
            
            $self.setWidthClass();
                          
            /***/
            
            $self.e('.crbs-main-navigation-default a').on('click',function(e)
            {
                e.preventDefault();
                
                var navigation=parseInt($(this).parent('li').data('step'),10);
                var step=parseInt($self.e('input[name="crbs_step"]').val(),10);
                
                if(navigation-step===0) return;
                
                $self.goToStep(navigation-step);
            });
            
            $self.e('.crbs-button-step-next').on('click',function(e)
            {
                e.preventDefault();
                $self.goToStep(1);
            });
            
            $self.e('.crbs-button-step-prev').on('click',function(e)
            {
                e.preventDefault();
                $self.goToStep(-1);
            });
            
             /***/
            
            $self.e('form[name="crbs-form"]').on('click','.crbs-form-field',function(e)
            {
                if($(this).find(':input[type="file"]').length) return;
                
                e.preventDefault();
                $(this).find(':input').focus(); 
                
                var select=$(this).find('select');
                
                if(select.length)
                    select.selectmenu('open');
            });
            
            $self.e('.crbs-button-checkbox>a').on('click',function(e)
            {
                e.preventDefault();
                
                if($(this).hasClass('crbs-state-selected')) return;
                
                var section=$(this).parent(':first');
                
                section.children('a').removeClass('crbs-state-selected');
                
                $(this).addClass('crbs-state-selected');
            });
            
            $self.e('#crbs-location-info-frame .crbs-button').on('click',function(e)
            {
                e.preventDefault();
                
                var section=$self.e('#crbs-location-info-frame').children('div.crbs-state-open');
                
                if(parseInt(section.length)===1)
                {
                    var locationId=section.attr('data-location-id');
                    var checkbox=section.find('.crbs-location-info-frame-button .crbs-button-checkbox a');
                    
                    if(checkbox.eq(0).hasClass('crbs-state-selected'))
                    {
                        $self.e('[name="crbs_pickup_date"]').val('');
                        $self.e('[name="crbs_pickup_time"]').val('');
                        $('[name="crbs_pickup_location_id"]').val(locationId).selectmenu('refresh');
                    }
                    if(checkbox.eq(1).hasClass('crbs-state-selected'))
                    {
                        $self.e('[name="crbs_return_date"]').val('');
                        $self.e('[name="crbs_return_time"]').val('');
                        $('[name="crbs_return_location_id"]').val(locationId).selectmenu('refresh');
                    }    
                }
                
                $self.closeLocationFrame();
            });
                    
            /***/
            
            $self.e('.crbs-main-content-step-2').on('click','.crbs-booking-extra-list .crbs-button.crbs-button-style-2',function(e)
            {
                e.preventDefault();
				
				if($(this).hasClass('crbs-state-selected-mandatory')) return;
				
                $(this).toggleClass('crbs-state-selected'); 
                
				$self.setBookingExtraIdField();
                
                $self.createSummaryPriceElement();
            });
            
            $self.e('.crbs-main-content-step-2').on('blur','.crbs-booking-extra-list input[type="text"]',function(e)
            {
               if(isNaN($(this).val())) $(this).val(1);
               $self.createSummaryPriceElement();
            });
            
            $self.e('.crbs-main-content-step-2').on('click','.crbs-booking-extra-list .crbs-column-2',function()
            {
                $(this).find('input[type="text"]').select();
            });
            
            $self.e('.crbs-main-content-step-2').on('blur','.crbs-booking-extra-list input[type="text"]',function()
            {
                if(!$(this)[0].hasAttribute('data-quantity-max')) return;
                
                var value=$(this).val();
                
                if(isNaN(value)) value=1;
                
                value=parseInt(value,10);
                
                if(value>parseInt($(this).attr('data-quantity-max'),10))
                    $(this).val($(this).attr('data-quantity-max'));
                
                $self.createSummaryPriceElement();
            });
            
            /***/
            
            $self.e('.crbs-main-content-step-2').on('click','.crbs-vehicle-list .crbs-button.crbs-button-style-2',function(e)
            {
                e.preventDefault();
                
                if($(this).hasClass('crbs-state-selected')) return;
                
                $self.e('.crbs-vehicle-list .crbs-button.crbs-button-style-2').removeClass('crbs-state-selected');
                
                $(this).addClass('crbs-state-selected');
                
                $self.e('input[name="crbs_vehicle_id"]').val(parseInt($(this).parents('.crbs-vehicle').attr('data-id'),10));
                
                $self.getGlobalNotice().addClass('crbs-hidden');
                
                $self.createSummaryPriceElement();
                
                if(parseInt($option.scroll_to_booking_extra_after_select_vehicle_enable)===1)
                {
                    var header=$self.e('.crbs-booking-extra>.crbs-header');
                    if(header.length===1) $.scrollTo(header,200,{offset:-50});
                }
                
                $self.changeBookingExtraPrice();
            });
            
            /***/
            
            $self.e('.crbs-main-content-step-2').on('click','.crbs-vehicle .crbs-vehicle-content .crbs-vehicle-description-button',function(e)
            {
                e.preventDefault();
                
                var button=$(this);
                
                var section=$(this).parents('.crbs-vehicle:first').find('.crbs-vehicle-description');
                
                var height=parseInt(section.children('div').actual('outerHeight',{includeMargin:true}),10);
                
                if(button.hasClass('crbs-state-open'))
                {
                    section.animate({height:0},150,function()
                    {
                        button.removeClass('crbs-state-open');
                        $(window).scroll();
                    });                      
                }
                else
                {
                    section.animate({height:height},150,function()
                    {
                        button.addClass('crbs-state-open');
                        $(window).scroll();
                    });                        
                }
            });            
            
            /***/
            
            $self.e('.crbs-main-content-step-3').on('change','input[name="crbs_client_sign_up_enable"]',function(e)
            { 
                var value=parseInt($(this).val())===1 ? 1 : 0;
                var section=$(this).parents('.crbs-form-panel:first').find('.crbs-form-panel-content>.crbs-disable-section');
                
                if(value===0) section.removeClass('crbs-hidden');
                else section.addClass('crbs-hidden');
                
                $(window).scroll();
            });
            
            $self.e('.crbs-main-content-step-3').on('change','input[name="crbs_client_billing_detail_enable"]',function(e)
            { 
                var value=parseInt($(this).val())===1 ? 1 : 0;
                var section=$(this).parents('.crbs-form-panel:first').find('.crbs-form-panel-content>.crbs-disable-section');
                
                if(value===0) section.removeClass('crbs-hidden');
                else section.addClass('crbs-hidden');
                
                $(window).scroll();
            });
            
            $self.e('.crbs-main-content-step-3').on('click','.crbs-sign-up-password-generate',function(e)
            {
                e.preventDefault();
                
                var helper=new CRBSHelper();
                var password=helper.generatePassword(8);
                
                $self.e('input[name="crbs_client_sign_up_password"],input[name="crbs_client_sign_up_password_retype"]').val(password);
            });
            
            $self.e('.crbs-main-content-step-3').on('click','.crbs-sign-up-password-show',function(e)
            {
                e.preventDefault();
                
                var password=$self.e('input[name="crbs_client_sign_up_password"]');
                password.attr('type',(password.attr('type')==='password' ? 'text' : 'password'));
            });
            
            $self.e('.crbs-main-content-step-3').on('click','.crbs-button-sign-up',function(e)
            {
                e.preventDefault();
                
                $self.e('.crbs-client-form-sign-up').removeClass('crbs-hidden');
                $self.e('input[name="crbs_client_account"]').val(1);
            });
            
            $self.e('.crbs-main-content-step-3').on('click','.crbs-button-sign-in',function(e)
            {
                e.preventDefault();
                
                $self.getGlobalNotice().addClass('crbs-hidden');
                
                $self.preloader(true);
            
                $self.setAction('user_sign_in');
       
                $self.post($self.e('form[name="crbs-form"]').serialize(),function(response)
                {
                    if(parseInt(response.user_sign_in,10)===1)
                    {
                        $self.e('.crbs-main-content-step-3 .crbs-client-form').html('');
                 
                        if(typeof(response.client_form_sign_up)!=='undefined')
                            $self.e('.crbs-main-content-step-3 .crbs-client-form').append(response.client_form_sign_up);  
       
                        if(typeof(response.summary)!=='undefined')
                            $self.e('.crbs-main-content-step-3>.crbs-layout-25x75 .crbs-layout-column-left:first').html(response.summary[0]);                        
                        
                        $self.createSelectField();
                    }
                    else
                    {
                        if(typeof(response.error.global[0])!=='undefined')
                            $self.getGlobalNotice().removeClass('crbs-hidden').html(response.error.global[0].message);
                    }
                    
                    $self.preloader(false);
                });
            });
            
            $self.e('#crbs-payment').on('click','ul>li>a',function(e)
            {
                e.preventDefault();
                
                $(this).parents('.crbs-payment').find('li>a').removeClass('crbs-state-selected');
                $(this).addClass('crbs-state-selected');
                
                $self.getGlobalNotice().addClass('crbs-hidden');
                
                $self.e('input[name="crbs_payment_id"]').val($(this).attr('data-payment-id'));
            });
    
            $self.e('>*').on('click','.crbs-form-checkbox',function()
            {
                var text=$(this).nextAll('input[type="hidden"]');
                
				var value=1;
				if(!$(this).hasClass('crbs-state-selected-mandatory'))
					value=parseInt(text.val())===1 ? 0 : 1;
                
                if(value===1) $(this).addClass('crbs-state-selected');
                else $(this).removeClass('crbs-state-selected');
                
                text.val(value).trigger('change');
            });
            
            /***/            
            
            $self.e('.crbs-main-content-step-4').on('click','.crbs-summary .crbs-summary-header a',function(e)
            {
                e.preventDefault();
                $self.goToStep(parseInt($(this).attr('data-step'),10)-4);
            });
            
            $self.e('.crbs-main-content-step-4').on('click','.crbs-coupon-code-section a',function(e)
            {
                e.preventDefault();
                
                $self.setAction('coupon_code_check');
       
                $self.post($self.e('form[name="crbs-form"]').serialize(),function(response)
                {
                    $self.e('.crbs-summary-price-element').replaceWith(response.html);
                    
                    var object=$self.e('.crbs-coupon-code-section');
                    
                    object.qtip(
                    {
                        show            :	
                        { 
                            target      :	$(this) 
                        },
                        style           :	
                        { 
                            classes     :	(response.error===1 ? 'crbs-qtip crbs-qtip-error' : 'crbs-qtip crbs-qtip-success')
                        },
                        content         : 	
                        { 
                            text        :	response.message 
                        },
                        position        : 	
                        { 
                            my          :	($option.is_rtl ? 'bottom right' : 'bottom left'),
                            at          :	($option.is_rtl ? 'top right' : 'top left'),
                            container   :   object.parent()
                        }
                    }).qtip('show');	
                    
                });
            });
            
            /***/
			
            $('.crbs-datepicker').datepicker(
            {
                autoSize                                                        :   true,
                dateFormat                                                      :   $option.date_format_js,
                beforeShow                                                      :   function(date)
                {
                    var helper=new CRBSHelper();
                    var locationId=$self.getLocationIdByField($(this));
           
                    var minDate=$option.location_booking_period[locationId].from;
                    var maxDate=$option.location_booking_period[locationId].to;
                    
                    if(!helper.isEmpty(maxDate))
                    {
                        if(helper.isEmpty(minDate)) minDate=0;
                    }
                    
                    $(this).datepicker('option','minDate',null);
                    $(this).datepicker('option','maxDate',null);
                    
                    if(!helper.isEmpty(minDate))
                        $(this).datepicker('option','minDate',parseInt(minDate));
                    else $(this).datepicker('option','minDate',$option.current_date);
						
                    if($(date).attr('name')==='crbs_pickup_date')
                    {
                        if(!helper.isEmpty(maxDate))
                            $(this).datepicker('option','maxDate',parseInt(minDate)+parseInt(maxDate)); 
                    }
					
					if($(date).attr('name')==='crbs_return_date')
					{
						try
						{
							var datePickup=$self.e('[name="crbs_pickup_date"]').val();
							var dateParse=$.datepicker.parseDate($option.date_format_js,datePickup);
							
							if(dateParse!==null)
							{
								$(this).datepicker('option','minDate',datePickup); 
							}
						}
						catch(e)
						{
							
						}
					}
                
                    $(this).datepicker('refresh');
                },
                beforeShowDay                                                   :   function(date)
                {
                    var dateField=$(this);
                    
                    var helper=new CRBSHelper();
                    
                    var locationId=$self.getLocationIdByField($(this));
                    
                    var date=$.datepicker.formatDate('dd-mm-yy',date);
                    
                    for(var i in $option.location_date_exclude[locationId])
                    {
                        var r=helper.compareDate([date,$option.location_date_exclude[locationId][i].start,$option.location_date_exclude[locationId][i].stop]);
                        if(r) return([false,'','']);
                    }
                    
                    /***/
                    
                    var temp=date.split('-');
                    var date=new Date(temp[2],temp[1]-1,temp[0]);
                    
                    var dayWeek=parseInt(date.getUTCDay(),10)+1;
               
                    var test=true;
               
                    if(dateField.attr('name')==='crbs_return_date')
                    {
                        if(parseInt($option.location_after_business_hour_return_enable[locationId])===1)
                        {
                            test=false;
                        }
                    }
                    
                    if(test)
                    {
                        if((!$option.location_business_hour[locationId][dayWeek].start) || (!$option.location_business_hour[locationId][dayWeek].stop)) 
                            return([false,'','']);
                    }
                    
                    /***/
                    
                    return([true,'','']);
                },
                onSelect                                                        :   function(date)
                {
                    var dateField=$(this);
                                        
                    var timeField=dateField.parent('div').parent('div').find('.crbs-timepicker');

                    var locationId=$self.getLocationIdByField(dateField);

                    timeField.timepicker(
                    { 
                        appendTo                                                :   $this,
                        showOn                                                  :   [],
                        showOnFocus                                             :   false,
                        timeFormat                                              :   $option.time_format,
                        step                                                    :   $option.timepicker_step,
                        disableTouchKeyboard                                    :   true
                    });

                    var dayWeek=parseInt(dateField.datepicker('getDate').getUTCDay(),10)+1;

                    if(new String(typeof($option.location_business_hour[locationId][dayWeek]))!=='undefined')
                    {
                        timeField.timepicker('option','minTime',$option.location_business_hour[locationId][dayWeek].start);
                        timeField.timepicker('option','maxTime',$option.location_business_hour[locationId][dayWeek].stop);                        
                    }
                    else
                    {
                        timeField.timepicker('option','minTime','');
                        timeField.timepicker('option','maxTime','');                            
                    }
					
                    if(dateField.attr('name')==='crbs_pickup_date')
                    {
                        if(parseInt($option.location_after_business_hour_pickup_enable[locationId],10)===1)
                        {
		                    var temp=$option.current_date.split('-');
							var date=new Date(temp[2],temp[1]-1,temp[0]);
                    
							if(dayWeek===parseInt(date.getUTCDay(),10)+1)
							{
								
							}
							else
							{
								timeField.timepicker('option','minTime','00:00');
							}
							
                            timeField.timepicker('option','maxTime','23:59');                            
                        }
                    }
                    
                    if(dateField.attr('name')==='crbs_return_date')
                    {
                        if(parseInt($option.location_after_business_hour_return_enable[locationId],10)===1)
                        {
                            timeField.timepicker('option','minTime','00:00');
                            timeField.timepicker('option','maxTime','23:59');                            
                        }
                    }
                    
                    timeField.val('').timepicker('show');
                    timeField.blur();

                    $self.setTimepicker(timeField);
                }
            });
            
            $this.on('focusin','.crbs-timepicker',function()
			{
                var helper=new CRBSHelper();
                
                var dateField=$(this).parent('div').parent('div').find('.crbs-datepicker');
                
                if(helper.isEmpty(dateField.val()))
                {
                    $(this).timepicker('remove');
                    dateField.click();
                    return;
                }
                else
                {
                    if(helper.isEmpty($(this).val()))
                        $(this).timepicker('show');
                }
            });
            
            /***/
            
            $self.createSelectField();
              
            /***/
    
            $self.e('.crbs-form-field').has('select').css({cursor:'pointer'});
            
            /***/
            
            $self.e('#crbs-location-info-frame .crbs-location-info-frame-header a').on('click',function(e)
            {
                e.preventDefault();
                $self.closeLocationFrame();
            });       
            
            /***/
            
            $(document).keypress(function(e) 
            {
                if(parseInt(e.which,10)===13) 
                {
                    switch($(e.target).attr('name'))
                    {
                        case 'crbs_client_sign_in_login':
                        case 'crbs_client_sign_in_password':
                        
                            $self.e('.crbs-main-content-step-3 .crbs-button-sign-in').trigger('click');
                        
                        break;
                    }
                }
            });
            
            /***/
            
            $self.e('.crbs-form-field-location-autocomplete input[type="text"]').each(function()
            {
                $self.googleMapAutocompleteCreate($(this));
            });
            
            /***/
            
            $self.e('.crbs-button-widget-submit').on('click',function(e)
            {
                e.preventDefault();
               
                var data={};
                    
                /***/
                
                data.pickup_location_id=$self.e('[name="crbs_pickup_location_id"]').val();
                if(data.pickup_location_id<0)
                {
                    var json=JSON.parse($self.e('[name="crbs_pickup_location_address_data"]').val());

                    data.pickup_location_address_data_lat=json.lat;
                    data.pickup_location_address_data_lng=json.lng;
                    data.pickup_location_address_data_formatted_address=json.formatted_address;
                }
                
                data.pickup_date=$self.e('[name="crbs_pickup_date"]').val();
                data.pickup_time=$self.e('[name="crbs_pickup_time"]').val();
                
                /***/
                
                data.return_location_id=$self.e('[name="crbs_return_location_id"]').val();
                if(data.return_location_id<-1)
                {
                    var json=JSON.parse($self.e('[name="crbs_return_location_address_data"]').val());

                    data.return_location_address_data_lat=json.lat;
                    data.return_location_address_data_lng=json.lng;
                    data.return_location_address_data_formatted_address=json.formatted_address;
                }
                
                data.return_date=$self.e('[name="crbs_return_date"]').val();
                data.return_time=$self.e('[name="crbs_return_time"]').val();                
                
                /***/
                
                data.driver_age=$self.e('[name="crbs_driver_age"]').val();
                
                /***/

                data.widget_submit=1;

                /***/
                
                var url=$option.widget.booking_form_url;
                
                if(url.indexOf('?')===-1) url+='?';
                if(url.indexOf('&')!==-1) url+='&';
                
                url+=decodeURIComponent($.param(data));
                
                window.location=url;
            });
            
            /***/
            
            $self.createDriverAgeField();
            $self.setDefaultVehicle();
            
            /***/
            
            if(parseInt(helper.urlParam('widget_submit'))===1)    
            {
                $self.goToStep(1,function()
                {
                    $self.googleMapCreate();
                    $self.googleMapInit();
                    $this.removeClass('crbs-hidden');
					$self.googleMapStartCustomizeHeight();
                });
            }
            else 
            {
                $self.googleMapCreate();
                $self.googleMapInit();
                $this.removeClass('crbs-hidden'); 
				$self.googleMapStartCustomizeHeight();
            }
            
            /***/
        };
		
		/**********************************************************************/
		
		this.setPayment=function()
		{
			var paymentId=0;
			var paymentSelected=$self.e('.crbs-payment>li>a.crbs-state-selected');
			
			if(paymentSelected.length===1)
				paymentId=paymentSelected.attr('data-payment-id');
			
			$self.e('input[name="crbs_payment_id"]').val(paymentId);
		};
        
        /**********************************************************************/
        
        this.changeBookingExtraPrice=function()
        {            
            var vehicleSelected=$self.e('.crbs-vehicle-list .crbs-vehicle .crbs-button.crbs-button-style-2.crbs-state-selected').parents('.crbs-vehicle');
            
            var vehicleId=parseInt(vehicleSelected.length,10)===1 ? vehicleSelected.attr('data-id') : 0;
             
            $self.e('.crbs-booking-extra-list>ul>li').each(function()
            {
                var vehiclePrice=JSON.parse($(this).attr('data-vehicle_price'));
                var price=vehicleId in vehiclePrice ? vehiclePrice[vehicleId] : vehiclePrice[0];
                
                $(this).find('.booking-form-extra-price>span').html(price);
            });
        };
        
        /**********************************************************************/
        
        this.createFileField=function()
        {
            $self.e('input[type="file"][name="crbs_driver_license_file"]').fileupload(
            {
                url                                                             :   $option.ajax_url,
                dataType                                                        :   'json',
                formData                                                        :   {'action':'crbs_driver_license_upload'},
                done                                                            :   function(e,data) 
                {
					$self.setFileUploadField(true,data.result);
                }
            });  
			
			$self.e('input[type="file"][name="crbs_driver_license_file"]').parent('.crbs-file-upload').next('.crbs-file-remove').children('span:last-child').on('click',function(e)
			{
				e.preventDefault();
				
				$self.setFileUploadField(false,[]);
			});
        };
		
		/**********************************************************************/
		
		this.setFileUploadField=function(upload,data)
		{
			var fileUploadField=$self.e('input[name="crbs_driver_license_file"]');
			
			if(upload)
			{
				fileUploadField.parent('.crbs-file-upload').addClass('crbs-hidden');
				fileUploadField.parent('.crbs-file-upload').next('.crbs-file-remove').removeClass('crbs-hidden').find('span>span').html(data.name);
			}
			else
			{
				fileUploadField.parent('.crbs-file-upload').removeClass('crbs-hidden');
				fileUploadField.parent('.crbs-file-upload').next('.crbs-file-remove').addClass('crbs-hidden').find('span>span').html('');
				
				data={name:'',type:'',tmp_name:''};
			}
			
            $self.e('input[name="crbs_driver_license_file_name"]').val(data.name);
            $self.e('input[name="crbs_driver_license_file_type"]').val(data.type);
            $self.e('input[name="crbs_driver_license_file_tmp_name"]').val(data.tmp_name);
		};
        
        /**********************************************************************/
        
        this.createSelectField=function()
        {
            $self.e('select').selectmenu(
            {
                appendTo                                                        :   $this,
                open                                                            :   function(event,ui)
                {
                    var select=$(this);
                    var selectmenu=$('#'+select.attr('id')+'-menu').parent('div');
                    
                    var field=select.parents('.crbs-form-field:first');
                    
                    var left=parseInt(selectmenu.css('left'),10)-1;
                    
                    var borderWidth=parseInt(field.css('border-left-width'),10)+parseInt(field.css('border-right-width'),10);
                    
                    var width=field[0].getBoundingClientRect().width-borderWidth;
                    
                    selectmenu.css({width:width,left:left});
                },
                change                                                          :   function(event,ui)
                {
                    var name=$(this).attr('name');
                    
                    if(name==='crbs_navigation_responsive')
                    {
                        var navigation=parseInt($(this).val(),10);
                        
                        var step=parseInt($self.e('input[name="crbs_step"]').val(),10);    
                
                        if(navigation-step===0) return;

                        $self.goToStep(navigation-step);
                    }
                    else if($.inArray(name,['crbs_vehicle_passenger_count','crbs_vehicle_bag_count','crbs_vehicle_category'])>-1)
                    {
                        $self.setAction('vehicle_filter');

                        $self.post($self.e('form[name="crbs-form"]').serialize(),function(response)
                        {       
                            $self.getGlobalNotice().addClass('crbs-hidden');
                            
                            var vehicleList=$self.e('.crbs-vehicle-list>ul');
                            vehicleList.html('');
                            
                            if((typeof(response.error)!=='undefined') && (typeof(response.error.global)!=='undefined'))
                            {
                                $self.getGlobalNotice().removeClass('crbs-hidden').html(response.error.global[0].message);
                            }
                            else
                            {
                                vehicleList.html(response.html);
                            }
                            
                            $self.e('.crbs-vehicle-list').find('.crbs-button.crbs-button-style-2').removeClass('crbs-state-selected');
                            
                            $self.preloadVehicleImage();
                            
                            $self.e('input[name="crbs_vehicle_id"]').val(0);
                            $self.createSummaryPriceElement();
                        });
                    }
                    else if($.inArray(name,['crbs_pickup_location_id'])>-1)
                    {
                        $self.e('[name="crbs_pickup_date"]').val('');
                        $self.e('[name="crbs_pickup_time"]').val('');
                        
                        $self.createDriverAgeField();
                        $self.setDefaultVehicle();
                        
                        $self.showHideCustomerLocation();
						$self.forceToUseTheSameLocation('pickup');
                    }
                    else if($.inArray(name,['crbs_return_location_id'])>-1)
                    {
                        $self.e('[name="crbs_return_date"]').val('');
                        $self.e('[name="crbs_return_time"]').val('');   
                        
                        $self.showHideCustomerLocation();
						$self.forceToUseTheSameLocation('return');
                    }
                }
            });
                        
            $self.e('.ui-selectmenu-button .ui-icon.ui-icon-triangle-1-s').attr('class','crbs-meta-icon-arrow-vertical');  
        };
		
		/**********************************************************************/
		
		this.forceToUseTheSameLocation=function(type)
		{
			if(parseInt($option.location_the_same_enable,10)!==1) return;
			
			if(type==='pickup')
			{
				var locationId=$self.e('[name="crbs_pickup_location_id"]').val();
				if(locationId>0)
				{
					$self.e('[name="crbs_return_location_id"]').val(locationId).selectmenu('refresh');
				}
			}
			else
			{
				var locationId=$self.e('[name="crbs_return_location_id"]').val();
				if(locationId>0)
				{
					$self.e('[name="crbs_pickup_location_id"]').val(locationId).selectmenu('refresh');
				}				
			}
		};
        
        /**********************************************************************/
        
        this.showHideCustomerLocation=function()
        {
            var object=['crbs_pickup_location_id','crbs_return_location_id'];
            
            for(var i in object)
            {
                var location=$self.e('[name="'+object[i]+'"]');
                var customerLocation=location.parents('.crbs-form-panel-content').find('.crbs-customer-location');
  
                if(customerLocation.length===1)
                {
                    if(location.val()<-1)
                        customerLocation.removeClass('crbs-hidden');
                    else customerLocation.addClass('crbs-hidden');
                }         
            }
        };
        
        /**********************************************************************/
        
        this.createDriverAgeField=function()
        {
            var field=$self.e('[name="crbs_driver_age"]');
            var pickupLocationId=$self.getLocationIdByField($self.e('[name="crbs_pickup_location_id"]'));
            
            if(parseInt(field.length)!==1) return;
            
            field.children('option').remove();
            
            if(typeof($option.location_driver_age[pickupLocationId])!=='undefined')
            {
                var min=$option.location_driver_age[pickupLocationId].min;
                var max=$option.location_driver_age[pickupLocationId].max;
                
                for(var i=min;i<=max;i++)
                    field.append($('<option value="'+i+'">'+i+'</option>'));
            }
            
            if(!field.children('option').length)
                field.append($('<option value="0">0</option>'))
            
            $self.e('[name="crbs_driver_age"]').selectmenu('refresh');
        };
		
		/**********************************************************************/
		
		this.setBookingExtraIdField=function()
		{
			var data=[];
			$self.e('.crbs-booking-extra-list .crbs-button.crbs-button-style-2').each(function()
            {
				if($(this).hasClass('crbs-state-selected'))
					data.push($(this).attr('data-id'));
			});
                
            $self.e('input[name="crbs_booking_extra_id"]').val(data.join(','));
		};
        
        /**********************************************************************/
        
        this.setDefaultVehicle=function()
        {
            var pickupLocationId=parseInt($self.e('[name="crbs_pickup_location_id"]').val());
            var vehicleIdDefault=parseInt($option.location_vehicle_id_default[pickupLocationId],10);
            
            $self.e('[name="crbs_vehicle_id"]').val(vehicleIdDefault);
        };
        
        /**********************************************************************/
        
        this.setTimepicker=function(field)
        {
            $self.e('.ui-timepicker-wrapper').css({opacity:1,'width':field.parent('div').outerWidth()+1});
        };
        
        /**********************************************************************/
        
        this.getLocationIdByField=function(field)
        {
            var locationId;
            
            if($.inArray(field.attr('name'),['crbs_pickup_date','crbs_pickup_time','crbs_pickup_location_id'])>-1)
            {
                locationId=parseInt($self.e('[name="crbs_pickup_location_id"]').val());
            }
            else if($.inArray(field.attr('name'),['crbs_return_date','crbs_return_time','crbs_return_location_id'])>-1)
            {
                locationId=parseInt($self.e('[name="crbs_return_location_id"]').val());
                if(locationId===-1)
                    locationId=$self.getLocationIdByField($('[name="crbs_pickup_date"]'));
            }
            
            return(Math.abs(locationId));
        };
        
        /**********************************************************************/
        /**********************************************************************/
        
        this.setMainNavigation=function()
        {
            var step=parseInt($self.e('input[name="crbs_step"]').val(),10);
     
            var element=$self.e('.crbs-main-navigation-default').find('li');
            
            element.removeClass('crbs-state-selected').removeClass('crbs-state-completed');
            
            element.filter('[data-step="'+step+'"]').addClass('crbs-state-selected');

            var i=0;
            element.each(function()
            {
                if((++i)>=step) return;
                
                $(this).addClass('crbs-state-completed');
            });
        };
                
        /**********************************************************************/
        /**********************************************************************/

        this.setAction=function(name)
        {
            $self.e('input[name="action"]').val('crbs_'+name);
        };

        /**********************************************************************/
        
        this.e=function(selector)
        {
            return($this.find(selector));
        };

        /**********************************************************************/
        
        this.goToStep=function(stepDelta,callback)
        {   
            if(parseInt($option.widget.mode)===1)
            {
                callback();
                return;
            }
            
            $self.preloader(true);
            
            $self.setAction('go_to_step');
            
            var step=$self.e('input[name="crbs_step"]');
            var stepRequest=$self.e('input[name="crbs_step_request"]');
            stepRequest.val(parseInt(step.val(),10)+stepDelta);
            
            $self.post($self.e('form[name="crbs-form"]').serialize(),function(response)
            {
                response.step=parseInt(response.step,10);
             
                if(parseInt(response.step,10)===5)
                {
					if(parseInt(response.payment_id,10)===-1)
					{
						if(parseInt(response.thank_you_page_enable,10)!==1)
						{
							window.location.href=response.payment_url;
							return;
						}						
					}
				}
	
                $self.getGlobalNotice().addClass('crbs-hidden');
                
                $self.e('.crbs-main-content>div').css('display','none');
                $self.e('.crbs-main-content>div:eq('+(response.step-1)+')').css('display','block');
                
                $self.e('input[name="crbs_step"]').val(response.step);
                
                $self.setMainNavigation();
                
                $self.googleMapDuplicate(-1);
                
                if($self.googleMapExist())
                    google.maps.event.trigger($googleMap,'resize');
                
                $('select[name="crbs_navigation_responsive"]').val(response.step);
                $('select[name="crbs_navigation_responsive"]').selectmenu('refresh');
				  
                if(parseInt(response.step,10)===1)
                    $self.googleMapStartCustomizeHeight();
                else $self.googleMapStopCustomizeHeight();
				  
                switch(parseInt(response.step,10))
                {
                    case 2:
                        
                        if(typeof(response.vehicle)!=='undefined')
                            $self.e('.crbs-vehicle-list>ul').html(response.vehicle);
                        
                        if(typeof(response.booking_extra)!=='undefined')
                            $self.e('.crbs-booking-extra').html(response.booking_extra);                        
                        
                        if(typeof(response.summary)!=='undefined')
                            $self.e('.crbs-main-content-step-2>.crbs-layout-25x75 .crbs-layout-column-left:first').html(response.summary[0]);  
                        
                        $self.preloadVehicleImage();
						
						$self.setBookingExtraIdField();
						$self.createSummaryPriceElement();
                        
                    case 3:
                        
                        if((typeof(response.client_form_sign_id)!=='undefined') && (typeof(response.client_form_sign_up)!=='undefined'))
                        {
                            $self.e('.crbs-main-content-step-3 .crbs-client-form').html('');

                            if(typeof(response.client_form_sign_id)!=='undefined')
                                $self.e('.crbs-main-content-step-3 .crbs-client-form').prepend(response.client_form_sign_id);                        

                            if(typeof(response.client_form_sign_up)!=='undefined')
                                $self.e('.crbs-main-content-step-3 .crbs-client-form').append(response.client_form_sign_up); 
                        }
                        
                        if(typeof(response.summary)!=='undefined')
                            $self.e('.crbs-main-content-step-3>.crbs-layout-25x75 .crbs-layout-column-left:first').html(response.summary[0]);
                        
                        if(typeof(response.payment)!=='undefined')
                            $self.e('.crbs-main-content-step-3>.crbs-layout-25x75 .crbs-layout-column-right #crbs-payment').html(response.payment);
						
                        $self.createSelectField();
                        $self.createFileField();
						$self.setPayment();
						
                    break;
                    
                    case 4:
                        
                        if(typeof(response.summary)!=='undefined')
                        {
                            $self.e('.crbs-main-content-step-4>.crbs-layout-33x33x33>.crbs-layout-column-left').html(response.summary[0]);
                        
                            $self.e('.crbs-main-content-step-4>.crbs-layout-33x33x33>.crbs-layout-column-center').html(response.summary[1]);
                        
                            $self.e('.crbs-main-content-step-4>.crbs-layout-33x33x33>.crbs-layout-column-right').html(response.summary[2]);
                        }
                        
                    break;
                }
				                
                $('.qtip').remove();
                
                if($.inArray(response.step,[4])>-1)   
                    $self.googleMapDuplicate(response.step);
                
                if(typeof(response.error)!=='undefined')
                {
                    if(typeof(response.error.local)!=='undefined')
                    {
                        for(var index in response.error.local)
                        {
                            var selector,object;
                            
                            var sName=response.error.local[index].field.split('-');

                            if(isNaN(sName[1])) selector='[name="'+sName[0]+'"]:eq(0)';
                            else selector='[name="'+sName[0]+'[]"]:eq('+sName[1]+')';                                    
                                    
                            object=$self.e(selector).prevAll('label');
                                 
                            object.qtip(
                            {
                                show            :	
                                { 
                                    target      :	$(this) 
                                },
                                style           :	
                                { 
                                    classes     :	(response.error===1 ? 'crbs-qtip crbs-qtip-error' : 'crbs-qtip crbs-qtip-success')
                                },
                                content         : 	
                                { 
                                    text        :	response.error.local[index].message 
                                },
                                position        : 	
                                { 
									my          :	($option.is_rtl ? 'bottom right' : 'bottom left'),
									at          :	($option.is_rtl ? 'top right' : 'top left'),
                                    container   :   object.parent()
                                }
                            }).qtip('show');	
                        }
                    }
                    
                    if(typeof(response.error.global[0])!=='undefined')
                        $self.getGlobalNotice().removeClass('crbs-hidden').html(response.error.global[0].message);
                }
                
                if(parseInt(response.step,10)===5)
                {
                    $self.e('.crbs-main-navigation-default').addClass('crbs-hidden');
                    $self.e('.crbs-main-navigation-responsive').addClass('crbs-hidden');
                    
					if($.inArray(parseInt(response.payment_id,10),[1,2,3,4])>-1)
					{
						var helper=new CRBSHelper();
						
						if(!helper.isEmpty(response.payment_info))
							$self.e('.crbs-booking-complete-payment-'+response.payment_prefix).append('<p>'+response.payment_info+'</p>');
					}
					
					switch(parseInt(response.payment_id,10))
					{
						case -2:
							
							$self.e('.crbs-booking-complete-payment-none').css('display','block');
							$self.e('.crbs-booking-complete-payment-none>a').attr('href',response.button_back_to_home_url_address).text(response.button_back_to_home_label);
							
						break;
						
						case -1:
							
							if(parseInt(response.thank_you_page_enable,10)!==1)
							{
								window.location.href=response.payment_url;
							}
							else
							{
								$self.e('.crbs-booking-complete-payment-woocommerce').css('display','block');
								$self.e('.crbs-booking-complete-payment-woocommerce>a').attr('href',response.payment_url);
							}
							
						break;
						
						case 1:
							
							$self.e('.crbs-booking-complete-payment-cash').css('display','block');
							$self.e('.crbs-booking-complete-payment-cash>a').attr('href',response.button_back_to_home_url_address).text(response.button_back_to_home_label);
							
						break;
						
						case 2:
							
							var section=$self.e('.crbs-booking-complete-payment-stripe');

							section.css('display','block');

							$self.e('form[name="crbs-form"]').after(response.form);

							section.find('a').on('click',function(e)
							{
								e.preventDefault();
								$self.e('form[name="crbs-form-stripe"] button').click();
							});
							
							/***/
							
							var pickupLocationId=$self.getLocationIdByField($self.e('[name="crbs_pickup_location_id"]'));
							var value=parseInt($option.location_payment_stripe_window_open_default_enable[pickupLocationId],10);
							
							if(value===1)
							{
								var stripeClick=setInterval(function()
								{
									$self.e('form[name="crbs-form-stripe"] button').click();
									if($('iframe.stripe_checkout_app').css('display')==='block')
									{
										clearInterval(stripeClick);
									}
								},500);  
							}
							
						break;
						
						case 3:
							
							var counter=5;

							$self.e('.crbs-booking-complete-payment-paypal').css('display','block');

							var interval=setInterval(function()
							{
								counter--;
								$self.e('.crbs-booking-complete-payment-paypal>span').html(counter);

								var pickupLocationId=parseInt($self.e('[name="crbs_pickup_location_id"]').val());

								if(counter===0)
								{
									clearInterval(interval);

									var form=$self.e('form[name="crbs-form-paypal"][data-location-id="'+pickupLocationId+'"]');

									for(var i in response.form)
										form.find('input[name="'+i+'"]').val(response.form[i]);

									form.submit();
							  }

							},1000);  
							
						break;
						
						case 4:
							
							$self.e('.crbs-booking-complete-payment-wire_transfer').css('display','block');
							$self.e('.crbs-booking-complete-payment-wire_transfer>a').attr('href',response.button_back_to_home_url_address).text(response.button_back_to_home_label);
							
						break;
					}
                }
                                
				$self.preloader(false);
				
                if(typeof(callback)!=='undefined') callback();

				$self.createStickySidebar();
				$(window).scroll();
				
                var offset=20;
                
                if($('#wpadminbar').length===1)
                    offset+=$('#wpadminbar').height();
                
                $.scrollTo($('.crbs-main'),{offset:-1*offset});
            });
        };
		
        /**********************************************************************/
        
		this.post=function(data,callback)
		{
			$.post($option.ajax_url,data,function(response)
			{ 
				callback(response); 
			},'json');
		};    
        
        /**********************************************************************/
        
        this.preloader=function(action)
        {
            $('#crbs-preloader').css('display',(action ? 'block' : 'none'));
        };
        
        /**********************************************************************/
        
        this.preloadVehicleImage=function()
        {
            $self.e('.crbs-vehicle-list .crbs-vehicle-image img').one('load',function()
            {
                $(this).parent('.crbs-vehicle-image').animate({'opacity':1},300);
            }).each(function() 
            {
                if(this.complete) $(this).load();
            });
        };
        
        /**********************************************************************/
        /**********************************************************************/
       	   
        this.googleMapExist=function()
        {
            return(typeof($googleMap)==='undefined' ? false : true);
        };
        
        /**********************************************************************/
       
        this.googleMapDuplicate=function(step)
        {
            if(!$self.googleMapExist()) return;
            
            if(step===4)
            {
                var map=$self.e('.crbs-google-map>#crbs_google_map');
                if(map.children('div').length)
                {
                    $self.e('.crbs-google-map-summary').append(map);   
                    $self.googleMapCreateMarker($self.e('[name="crbs_pickup_location_id"]').val());
                }
            }
            else
            {
                var map=$self.e('.crbs-google-map-summary>#crbs_google_map');
                if(map.children('div').length)
                {
                    $self.e('.crbs-google-map').append(map);
                    $self.googleMapCreateMarker(-1);
                }
            }
            
            google.maps.event.trigger($googleMap,'resize');
        };
        
        /**********************************************************************/
        
        this.googleMapAutocompleteCreate=function(text)
        {
            var option={};
            var id=$(text).attr('id');
            var helper=new CRBSHelper();
            
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
                if(helper.isEmpty($(this).val()))
                {
                    text.siblings('input[type="hidden"]').val('');              
                }
            });
                    
            if(id.indexOf('pickup')>-1)
            {
                if($.isArray($option.customer_pickup_location_restriction_country))
                {
                    option.componentRestrictions={};
                    option.componentRestrictions.country=$option.customer_pickup_location_restriction_country;
                }
                
                if((parseInt($option.customer_pickup_location_restriction_radius,10)>=0) && (!helper.isEmpty($option.customer_pickup_location_restriction_coordinate_lat)) && (!helper.isEmpty($option.customer_pickup_location_restriction_coordinate_lng)))
                {
                    var circle=new google.maps.Circle(
                    {
                        center                                                  :   new google.maps.LatLng($option.customer_pickup_location_restriction_coordinate_lat,$option.customer_pickup_location_restriction_coordinate_lng),
                        radius                                                  :   $option.customer_pickup_location_restriction_radius*1000
                    });

                    option.strictBounds=true;
                    option.bounds=circle.getBounds();
                }                
            }
            
            if(id.indexOf('return')>-1)
            {
                if($.isArray($option.customer_return_location_restriction_country))
                {
                    option.componentRestrictions={};
                    option.componentRestrictions.country=$option.customer_return_location_restriction_country;
                }
                
                if((parseInt($option.customer_return_location_restriction_radius,10)>=0) && (!helper.isEmpty($option.customer_return_location_restriction_coordinate_lat)) && (!helper.isEmpty($option.customer_return_location_restriction_coordinate_lng)))
                {
                    var circle=new google.maps.Circle(
                    {
                        center                                                  :   new google.maps.LatLng($option.customer_return_location_restriction_coordinate_lat,$option.customer_return_location_restriction_coordinate_lng),
                        radius                                                  :   $option.customer_return_location_restriction_radius*1000 
                    });

                    option.strictBounds=true;
                    option.bounds=circle.getBounds();
                }                
            }
            
            var autocomplete=new google.maps.places.Autocomplete(document.getElementById(id),option);
            autocomplete.addListener('place_changed',function(id)
            {
                var place=autocomplete.getPlace();
                var field=text.siblings('input[type="hidden"]');
                
                if(typeof(place.geometry)!=='undefined')
                {
                    var placeData=
                    {
                        lat                                                     :   place.geometry.location.lat(),
                        lng                                                     :   place.geometry.location.lng(),
                        formatted_address                                       :   text.val()
                    };
                
                    field.val(JSON.stringify(placeData));
                }
                else field.val('');
            });  
        };
        
        /**********************************************************************/
        
        this.googleMapCreateMarker=function(locationIdSelected)
        {
            if(!$self.googleMapExist()) return;
            
            for(var i in $googleMapMarker)
                $googleMapMarker[i].setMap(null);
            
            $googleMapMarker=[];
            
            if(Object.keys($option.location_coordinate).length)
            {
                var bound=new google.maps.LatLngBounds();
                
                for(var i in $option.location_coordinate)
                {
                    if(locationIdSelected!==-1)
                    {
                        if(locationIdSelected!==i) continue;
                    }
                    
                    var coordinate=new google.maps.LatLng($option.location_coordinate[i].lat,$option.location_coordinate[i].lng);
                    
                    var marker=new google.maps.Marker(
                    {
                        id                                                      :   i,
                        map                                                     :   $googleMap,
                        position                                                :   coordinate,
                        icon                                                    :  
                        {
                            path                                                :   'M21,0A21,21,0,0,1,42,21c0,16-21,29-21,29S0,37,0,21A21,21,0,0,1,21,0Z',
                            fillColor                                           :   '#'+$option.booking_form_color[1],
                            strokeColor                                         :   '#'+$option.booking_form_color[1],
                            fillOpacity                                         :   1,
                            labelOrigin                                         :   new google.maps.Point(21,21),
                            anchor                                              :   new google.maps.Point(21,50)
                        },
                        label                                                   : 
                        {
                            text                                                :   ''+(parseInt($option.vehicle_count_enable,10)===1 ? $option.location_info[i].vehicle_count : ' '), 
                            color                                               :   '#'+$option.booking_form_color[3],
                            fontSize                                            :   '14px',
                            fontWeight                                          :   '400',
                            fontFamily                                          :   'Lato'
                        }
                    });
                    
                    if(locationIdSelected===-1)
                    {
                        marker.addListener('click',function() 
                        {
                            $self.openLocationFrame($(this)[0].id);
                        });
                    }    
                
                    if(locationIdSelected===-1)
                        bound.extend(coordinate);
                    
                    $googleMapMarker.push(marker);
                }
                
                if((locationIdSelected===-1) && ($googleMapMarker.length>1))
                    $googleMap.fitBounds(bound);
                else $googleMap.setCenter(coordinate);
            }            
        };
        
        /**********************************************************************/
        
        this.googleMapInit=function()
        {
            if(!$self.googleMapExist()) return;
            
            if(Object.keys($option.location_coordinate).length)
            {
                $self.googleMapCreateMarker(-1);
            }
            else
            {
                if((navigator.geolocation) && ($.inArray(1,$option.geolocation_enable))) 
                {
                    navigator.geolocation.getCurrentPosition(function(position)
                    {
                        $startLocation=new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                        $googleMap.setCenter($startLocation);
                    },
                    function()
                    {
                        $self.googleMapUseDefaultLocation();
                    });
                } 
                else
                {
                    $self.googleMapUseDefaultLocation();
                }
            }
        };
        
        /**********************************************************************/
        
        this.googleMapUseDefaultLocation=function()
        {
            if(!$self.googleMapExist()) return;
            
            $startLocation=new google.maps.LatLng($option.client_coordinate.lat,$option.client_coordinate.lng);
            $googleMap.setCenter($startLocation);            
        };
        
        /**********************************************************************/
        
        this.googleMapCreate=function()
        {
            if($self.e('#crbs_google_map').length!==1) return;
            
            var option= 
            {
                draggable                                                       :	$option.gooogleMapOption.draggable.enable,
                scrollwheel                                                     :	$option.gooogleMapOption.scrollwheel.enable,
                mapTypeId                                                       :	google.maps.MapTypeId[$option.gooogleMapOption.mapControl.id],
                mapTypeControl                                                  :	$option.gooogleMapOption.mapControl.enable,
                mapTypeControlOptions                                           :	
                {
                    style                                                       :	google.maps.MapTypeControlStyle[$option.gooogleMapOption.mapControl.style],
                    position                                                    :	google.maps.ControlPosition[$option.gooogleMapOption.mapControl.position],
                },
                zoom                                                            :	$option.gooogleMapOption.zoomControl.level,
                zoomControl                                                     :	$option.gooogleMapOption.zoomControl.enable,
                zoomControlOptions                                              :	
                {
                    position                                                    :	google.maps.ControlPosition[$option.gooogleMapOption.zoomControl.position]
                },
                streetViewControl                                               :   false,
                styles                                                          :   $option.gooogleMapOption.style
            };
            
            $googleMap=new google.maps.Map($self.e('#crbs_google_map')[0],option);
        };
        
        /**********************************************************************/
        
		this.setWidthClass=function()
		{
			var width=$this.parent().width();
			
			var className=null;
			var classPrefix='crbs-width-';
			
			if(width>=1220) className='1220';
			else if(width>=960) className='960';
			else if(width>=768) className='768';
			else if(width>=480) className='480';
			else if(width>=300) className='300';
            else className='300';
			
			var oldClassName=$self.getValueFromClass($this,classPrefix);
			if(oldClassName!==false) $this.removeClass(classPrefix+oldClassName);
			
			$this.addClass(classPrefix+className);
			
            if(width>=960) $this.removeClass('crbs-widthlt-960');
            else $this.addClass('crbs-widthlt-960');
            
			if($self.prevWidth!==width)
            {
				$self.prevWidth=width;
                $(window).resize();
                                
                $self.createStickySidebar();
				
				if(parseInt($option.widget.mode,10)!==1)
                {
                    if($.inArray(className,['300','480'])>-1)
                        $self.googleMapStopCustomizeHeight();
                    else $self.googleMapStartCustomizeHeight();
                }
            };
                        
			setTimeout($self.setWidthClass,500);
		};
       
		/**********************************************************************/
		
		this.getValueFromClass=function(object,pattern)
		{
			try
			{
				var reg=new RegExp(pattern);
				var className=$(object).attr('class').split(' ');

				for(var i in className)
				{
					if(reg.test(className[i]))
						return(className[i].substring(pattern.length));
				}
			}
			catch(e) {}

			return(false);		
		};
        
        /**********************************************************************/
        
        this.createSummaryPriceElement=function()
        {
            $self.setAction('create_summary_price_element');
  
            $self.post($self.e('form[name="crbs-form"]').serialize(),function(response)
            {    
                $self.e('.crbs-summary-price-element').replaceWith(response.html);
                $(window).scroll();
            });   
        };
        
        /**********************************************************************/
        
        this.createStickySidebar=function()
        {
            if(parseInt($option.summary_sidebar_sticky_enable,10)!==1) return;
            
            var className=$self.getValueFromClass($this,'crbs-width-');
            
            if($.inArray(className,['300','480','768'])>-1)
            {
                $self.removeStickySidebar();
                return;
            }       
            
            var step=parseInt($self.e('input[name="crbs_step"]').val(),10);
            
            var offset=30;
            var adminBar=$('#wpadminbar');
            
            if(adminBar.length===1)
                offset+=adminBar.actual('height');
            
            $self.e('.crbs-main-content>.crbs-main-content-step-'+step+'>.crbs-layout-25x75 .crbs-layout-column-left:first').stick_in_parent({offset_top:offset,recalc_every:1});
        };
        
        /**********************************************************************/
        
        this.removeStickySidebar=function()
        {
            if(parseInt($option.summary_sidebar_sticky_enable,10)!==1) return;
            
            var step=parseInt($self.e('input[name="crbs_step"]').val(),10);
            $self.e('.crbs-main-content>.crbs-main-content-step-'+step+'>.crbs-layout-25x75 .crbs-layout-column-left:first').trigger('sticky_kit:detach');
        };
        
        /**********************************************************************/
        
        this.getGlobalNotice=function()
        {
            var step=parseInt($self.e('input[name="crbs_step"]').val(),10);
            return($self.e('.crbs-main-content-step-'+step+' .crbs-notice'));
        };
    
        /**********************************************************************/
        
        this.openLocationFrame=function(locationId)
        {
            var frame=$self.e('#crbs-location-info-frame');
            
            $self.closeLocationFrame();
            
            frame.css({display:'block'});
            frame.children('div[data-location-id="'+locationId+'"]').addClass('crbs-state-open').css({display:'block'});
        };
        
        /**********************************************************************/
        
        this.closeLocationFrame=function()
        {
            var frame=$self.e('#crbs-location-info-frame');
            frame.css({display:'none'});
            frame.children('div').removeClass('crbs-state-open').css({display:'none'});            
        };
		
		/**********************************************************************/
		
        this.googleMapStartCustomizeHeight=function()
        {
            if(parseInt($option.widget.mode,10)===1) return;
            
            if($googleMapHeightInterval>0) return;
            
            $googleMapHeightInterval=window.setInterval(function()
            {
                $self.googleMapCustomizeHeight();
            },500);
        };
        
        /**********************************************************************/
       
        this.googleMapStopCustomizeHeight=function()
        {
            if(parseInt($option.widget.mode,10)===1) return;
            
            clearInterval($googleMapHeightInterval);
            $self.e('#crbs_google_map').height('719px');
            
            $googleMapHeightInterval=0;
        };        
        
        /**********************************************************************/
       
        this.googleMapCustomizeHeight=function()
        {
            if(parseInt($option.widget.mode,10)===1) return;
			
            var columnLeft=$self.e('.crbs-main-content-step-1>div>.crbs-layout-column-left');
            
            $self.e('#crbs_google_map').height(parseInt(columnLeft.actual('outerHeight'),10));
            
            google.maps.event.trigger($googleMap,'resize');
        };
		        
        /**********************************************************************/
        /**********************************************************************/
	};
	
	/**************************************************************************/
	
	$.fn.CRBSBookingForm=function(option) 
	{
        console.log('--------------------------------------------------------------------------------------------');
        console.log('Car Rental Booking System for WordPress ver. '+option.plugin_version);
        console.log('https://1.envato.market/car-rental-booking-system-for-wordpress');
        console.log('--------------------------------------------------------------------------------------------');
        
		var form=new CRBSBookingForm(this,option);
        return(form);
	};
	
	/**************************************************************************/

})(jQuery,document,window);

/******************************************************************************/
/******************************************************************************/