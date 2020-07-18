<?php

/******************************************************************************/
/******************************************************************************/

$Currency=new CRBSCurrency();
$BookingForm=new CRBSBookingForm();
$VisualComposer=new CRBSVisualComposer();

vc_map
( 
    array
    (
        'base'                                                                  =>  CRBSBookingForm::getShortcodeName(),
        'name'                                                                  =>  __('Car Rental Booking Form','car-rental-booking-system'),
        'description'                                                           =>  __('Displays booking from.','car-rental-booking-system'), 
        'category'                                                              =>  __('Content','templatica'),  
        'params'                                                                =>  array
        (   
            array
            (
                'type'                                                          =>  'dropdown',
                'param_name'                                                    =>  'booking_form_id',
                'heading'                                                       =>  __('Booking form','car-rental-booking-system'),
                'description'                                                   =>  __('Select booking form which has to be displayed.','car-rental-booking-system'),
                'value'                                                         =>  $VisualComposer->createParamDictionary($BookingForm->getDictionary()),
                'admin_label'                                                   =>  true
            ),
            array
            (
                'type'                                                          =>  'dropdown',
                'param_name'                                                    =>  'currency',
                'heading'                                                       =>  __('Currency','car-rental-booking-system'),
                'description'                                                   =>  __('Select currency of booking form.','car-rental-booking-system'),
                'value'                                                         =>  $VisualComposer->createParamDictionary($Currency->getCurrency()),
                'admin_label'                                                   =>  true
            )  
        )
    )
);  