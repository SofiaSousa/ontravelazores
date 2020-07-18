<?php

/******************************************************************************/
/******************************************************************************/

class CRBSCurrency
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->currency=array
		(
			'AFN'			=>	array
			(
				'name'		=>	__('Afghan afghani','car-rental-booking-system'),
				'symbol'	=>	'AFN'
			),
			'ALL'			=>	array
			(
				'name'		=>	__('Albanian lek','car-rental-booking-system'),
				'symbol'	=>	'ALL'
			),
			'DZD'			=>	array
			(
				'name'		=>	__('Algerian dinar','car-rental-booking-system'),
				'symbol'	=>	'DZD'
			),
			'AOA'			=>	array
			(
				'name'		=>	__('Angolan kwanza','car-rental-booking-system'),
				'symbol'	=>	'AOA'
			),
			'ARS'			=>	array
			(
				'name'		=>	__('Argentine peso','car-rental-booking-system'),
				'symbol'	=>	'ARS'
			),
			'AMD'			=>	array
			(
				'name'		=>	__('Armenian dram','car-rental-booking-system'),
				'symbol'	=>	'AMD'
			),
			'AWG'			=>	array
			(
				'name'		=>	__('Aruban florin','car-rental-booking-system'),
				'symbol'	=>	'AWG'
			),
			'AUD'			=>	array
			(
				'name'		=>	__('Australian dollar','car-rental-booking-system'),
				'symbol'	=>	'&#36;',
				'separator'	=>	'.'
			),
			'AZN'			=>	array
			(
				'name'		=>	__('Azerbaijani manat','car-rental-booking-system'),
				'symbol'	=>	'AZN'
			),
			'BSD'			=>	array
			(
				'name'		=>	__('Bahamian dollar','car-rental-booking-system'),
				'symbol'	=>	'BSD'
			),
			'BHD'			=>	array
			(
				'name'		=>	__('Bahraini dinar','car-rental-booking-system'),
				'symbol'	=>	'BHD',
				'separator'	=>	'&#1643;'
			),
			'BDT'			=>	array
			(
				'name'		=>	__('Bangladeshi taka','car-rental-booking-system'),
				'symbol'	=>	'BDT'
			),
			'BBD'			=>	array
			(
				'name'		=>	__('Barbadian dollar','car-rental-booking-system'),
				'symbol'	=>	'BBD'
			),
			'BYR'			=>	array
			(
				'name'		=>	__('Belarusian ruble','car-rental-booking-system'),
				'symbol'	=>	'BYR'
			),
			'BZD'			=>	array
			(
				'name'		=>	__('Belize dollar','car-rental-booking-system'),
				'symbol'	=>	'BZD'
			),
			'BTN'			=>	array
			(
				'name'		=>	__('Bhutanese ngultrum','car-rental-booking-system'),
				'symbol'	=>	'BTN'
			),
			'BOB'			=>	array
			(
				'name'		=>	__('Bolivian boliviano','car-rental-booking-system'),
				'symbol'	=>	'BOB'
			),
			'BAM'			=>	array
			(
				'name'		=>	__('Bosnia and Herzegovina konvertibilna marka','car-rental-booking-system'),
				'symbol'	=>	'BAM'
			),
			'BWP'			=>	array
			(
				'name'		=>	__('Botswana pula','car-rental-booking-system'),
				'symbol'	=>	'BWP',
				'separator'	=>	'.'
			),
			'BRL'			=>	array
			(
				'name'		=>	__('Brazilian real','car-rental-booking-system'),
				'symbol'	=>	'&#82;&#36;'
			),
			'GBP'			=>	array
			(
				'name'		=>	__('British pound','car-rental-booking-system'),
				'symbol'	=>	'&pound;',
				'position'	=>	'left',
				'separator'	=>	'.',
			),
			'BND'			=>	array
			(
				'name'		=>	__('Brunei dollar','car-rental-booking-system'),
				'symbol'	=>	'BND',
				'separator'	=>	'.'
			),
			'BGN'			=>	array
			(
				'name'		=>	__('Bulgarian lev','car-rental-booking-system'),
				'symbol'	=>	'BGN'
			),
			'BIF'			=>	array
			(
				'name'		=>	__('Burundi franc','car-rental-booking-system'),
				'symbol'	=>	'BIF'
			),
			'KYD'			=>	array
			(
				'name'		=>	__('Cayman Islands dollar','car-rental-booking-system'),
				'symbol'	=>	'KYD'
			),
			'KHR'			=>	array
			(
				'name'		=>	__('Cambodian riel','car-rental-booking-system'),
				'symbol'	=>	'KHR'
			),
			'CAD'			=>	array
			(
				'name'		=>	__('Canadian dollar','car-rental-booking-system'),
				'symbol'	=>	'CAD',
				'separator'	=>	'.'
			),
			'CVE'			=>	array
			(
				'name'		=>	__('Cape Verdean escudo','car-rental-booking-system'),
				'symbol'	=>	'CVE'
			),
			'XAF'			=>	array
			(
				'name'		=>	__('Central African CFA franc','car-rental-booking-system'),
				'symbol'	=>	'XAF'
			),
			'GQE'			=>	array
			(
				'name'		=>	__('Central African CFA franc','car-rental-booking-system'),
				'symbol'	=>	'GQE'
			),
			'XPF'			=>	array
			(
				'name'		=>	__('CFP franc','car-rental-booking-system'),
				'symbol'	=>	'XPF'
			),
			'CLP'			=>	array
			(
				'name'		=>	__('Chilean peso','car-rental-booking-system'),
				'symbol'	=>	'CLP'
			),
			'CNY'			=>	array
			(
				'name'		=>	__('Chinese renminbi','car-rental-booking-system'),
				'symbol'	=>	'&yen;'
			),
			'COP'			=>	array
			(
				'name'		=>	__('Colombian peso','car-rental-booking-system'),
				'symbol'	=>	'COP'
			),
			'KMF'			=>	array
			(
				'name'		=>	__('Comorian franc','car-rental-booking-system'),
				'symbol'	=>	'KMF'
			),
			'CDF'			=>	array
			(
				'name'		=>	__('Congolese franc','car-rental-booking-system'),
				'symbol'	=>	'CDF'
			),
			'CRC'			=>	array
			(
				'name'		=>	__('Costa Rican colon','car-rental-booking-system'),
				'symbol'	=>	'CRC'
			),
			'HRK'			=>	array
			(
				'name'		=>	__('Croatian kuna','car-rental-booking-system'),
				'symbol'	=>	'HRK'
			),
			'CUC'			=>	array
			(
				'name'		=>	__('Cuban peso','car-rental-booking-system'),
				'symbol'	=>	'CUC'
			),
			'CZK'			=>	array
			(
				'name'		=>	__('Czech koruna','car-rental-booking-system'),
				'symbol'	=>	'&#75;&#269;'
			),
			'DKK'			=>	array
			(
				'name'		=>	__('Danish krone','car-rental-booking-system'),
				'symbol'	=>	'&#107;&#114;'
			),
			'DJF'			=>	array
			(
				'name'		=>	__('Djiboutian franc','car-rental-booking-system'),
				'symbol'	=>	'DJF'
			),
			'DOP'			=>	array
			(
				'name'		=>	__('Dominican peso','car-rental-booking-system'),
				'symbol'	=>	'DOP',
				'separator'	=>	'.'
			),
			'XCD'			=>	array
			(
				'name'		=>	__('East Caribbean dollar','car-rental-booking-system'),
				'symbol'	=>	'XCD'
			),
			'EGP'	=>	array
			(
				'name'		=>	__('Egyptian pound','car-rental-booking-system'),
				'symbol'	=>	'EGP'
			),
			'ERN'			=>	array
			(
				'name'		=>	__('Eritrean nakfa','car-rental-booking-system'),
				'symbol'	=>	'ERN'
			),
			'EEK'			=>	array
			(
				'name'		=>	__('Estonian kroon','car-rental-booking-system'),
				'symbol'	=>	'EEK'
			),
			'ETB'			=>	array
			(
				'name'		=>	__('Ethiopian birr','car-rental-booking-system'),
				'symbol'	=>	'ETB'
			),
			'EUR'			=>	array
			(
				'name'		=>	__('European euro','car-rental-booking-system'),
				'symbol'	=>	'&euro;'
			),
			'FKP'			=>	array
			(
				'name'		=>	__('Falkland Islands pound','car-rental-booking-system'),
				'symbol'	=>	'FKP'
			),
			'FJD'			=>	array
			(
				'name'		=>	__('Fijian dollar','car-rental-booking-system'),
				'symbol'	=>	'FJD',
				'separator'	=>	'.'
			),
			'GMD'			=>	array
			(
				'name'		=>	__('Gambian dalasi','car-rental-booking-system'),
				'symbol'	=>	'GMD'
			),
			'GEL'			=>	array
			(
				'name'		=>	__('Georgian lari','car-rental-booking-system'),
				'symbol'	=>	'GEL'
			),
			'GHS'			=>	array
			(
				'name'		=>	__('Ghanaian cedi','car-rental-booking-system'),
				'symbol'	=>	'GHS'
			),
			'GIP'			=>	array
			(
				'name'		=>	__('Gibraltar pound','car-rental-booking-system'),
				'symbol'	=>	'GIP'
			),
			'GTQ'			=>	array
			(
				'name'		=>	__('Guatemalan quetzal','car-rental-booking-system'),
				'symbol'	=>	'GTQ',
				'separator'	=>	'.'
			),
			'GNF'			=>	array
			(
				'name'		=>	__('Guinean franc','car-rental-booking-system'),
				'symbol'	=>	'GNF'
			),
			'GYD'			=>	array
			(
				'name'		=>	__('Guyanese dollar','car-rental-booking-system'),
				'symbol'	=>	'GYD'
			),
			'HTG'			=>	array
			(
				'name'		=>	__('Haitian gourde','car-rental-booking-system'),
				'symbol'	=>	'HTG'
			),
			'HNL'			=>	array
			(
				'name'		=>	__('Honduran lempira','car-rental-booking-system'),
				'symbol'	=>	'HNL',
				'separator'	=>	'.'
			),
			'HKD'			=>	array
			(
				'name'		=>	__('Hong Kong dollar','car-rental-booking-system'),
				'symbol'	=>	'&#36;',
				'separator'	=>	'.'
			),
			'HUF'			=>	array
			(
				'name'		=>	__('Hungarian forint','car-rental-booking-system'),
				'symbol'	=>	'&#70;&#116;'
			),
			'ISK'			=>	array
			(
				'name'		=>	__('Icelandic krona','car-rental-booking-system'),
				'symbol'	=>	'ISK'
			),
			'INR'			=>	array
			(
				'name'		=>	__('Indian rupee','car-rental-booking-system'),
				'symbol'	=>	'&#8377;',
				'separator'	=>	'.'
			),
			'IDR'			=>	array
			(
				'name'		=>	__('Indonesian rupiah','car-rental-booking-system'),
				'symbol'	=>	'Rp',
				'position'	=>	'left'
			),
			'IRR'			=>	array
			(
				'name'		=>	__('Iranian rial','car-rental-booking-system'),
				'symbol'	=>	'IRR',
				'separator'	=>	'&#1643;'
			),
			'IQD'			=>	array
			(
				'name'		=>	__('Iraqi dinar','car-rental-booking-system'),
				'symbol'	=>	'IQD',
				'separator'	=>	'&#1643;'
			),
			'ILS'			=>	array
			(
				'name'		=>	__('Israeli new sheqel','car-rental-booking-system'),
				'symbol'	=>	'&#8362;',
				'separator'	=>	'.'
			),
			'YER'			=>	array
			(
				'name'		=>	__('Yemeni rial','car-rental-booking-system'),
				'symbol'	=>	'YER'
			),
			'JMD'			=>	array
			(
				'name'		=>	__('Jamaican dollar','car-rental-booking-system'),
				'symbol'	=>	'JMD'
			),
			'JPY'			=>	array
			(
				'name'		=>	__('Japanese yen','car-rental-booking-system'),
				'symbol'	=>	'&yen;',
				'separator'	=>	'.'
			),
			'JOD'			=>	array
			(
				'name'		=>	__('Jordanian dinar','car-rental-booking-system'),
				'symbol'	=>	'JOD'
			),
			'KZT'			=>	array
			(
				'name'		=>	__('Kazakhstani tenge','car-rental-booking-system'),
				'symbol'	=>	'KZT'
			),
			'KES'			=>	array
			(
				'name'		=>	__('Kenyan shilling','car-rental-booking-system'),
				'symbol'	=>	'KES'
			),
			'KGS'			=>	array
			(
				'name'		=>	__('Kyrgyzstani som','car-rental-booking-system'),
				'symbol'	=>	'KGS'
			),
			'KWD'			=>	array
			(
				'name'		=>	__('Kuwaiti dinar','car-rental-booking-system'),
				'symbol'	=>	'KWD',
				'separator'	=>	'&#1643;'
			),
			'LAK'			=>	array
			(
				'name'		=>	__('Lao kip','car-rental-booking-system'),
				'symbol'	=>	'LAK'
			),
			'LVL'			=>	array
			(
				'name'		=>	__('Latvian lats','car-rental-booking-system'),
				'symbol'	=>	'LVL'
			),
			'LBP'			=>	array
			(
				'name'		=>	__('Lebanese lira','car-rental-booking-system'),
				'symbol'	=>	'LBP'
			),
			'LSL'			=>	array
			(
				'name'		=>	__('Lesotho loti','car-rental-booking-system'),
				'symbol'	=>	'LSL'
			),
			'LRD'			=>	array
			(
				'name'		=>	__('Liberian dollar','car-rental-booking-system'),
				'symbol'	=>	'LRD'
			),
			'LYD'			=>	array
			(
				'name'		=>	__('Libyan dinar','car-rental-booking-system'),
				'symbol'	=>	'LYD'
			),
			'LTL'			=>	array
			(
				'name'		=>	__('Lithuanian litas','car-rental-booking-system'),
				'symbol'	=>	'LTL'
			),
			'MOP'			=>	array
			(
				'name'		=>	__('Macanese pataca','car-rental-booking-system'),
				'symbol'	=>	'MOP'
			),
			'MKD'			=>	array
			(
				'name'		=>	__('Macedonian denar','car-rental-booking-system'),
				'symbol'	=>	'MKD'
			),
			'MGA'			=>	array
			(
				'name'		=>	__('Malagasy ariary','car-rental-booking-system'),
				'symbol'	=>	'MGA'
			),
			'MYR'			=>	array
			(
				'name'		=>	__('Malaysian ringgit','car-rental-booking-system'),
				'symbol'	=>	'&#82;&#77;',
				'separator'	=>	'.'
			),
			'MWK'			=>	array
			(
				'name'		=>	__('Malawian kwacha','car-rental-booking-system'),
				'symbol'	=>	'MWK'
			),
			'MVR'			=>	array
			(
				'name'		=>	__('Maldivian rufiyaa','car-rental-booking-system'),
				'symbol'	=>	'MVR'
			),
			'MRO'			=>	array
			(
				'name'		=>	__('Mauritanian ouguiya','car-rental-booking-system'),
				'symbol'	=>	'MRO'
			),
			'MUR'			=>	array
			(
				'name'		=>	__('Mauritian rupee','car-rental-booking-system'),
				'symbol'	=>	'MUR'
			),
			'MXN'			=>	array
			(
				'name'		=>	__('Mexican peso','car-rental-booking-system'),
				'symbol'	=>	'&#36;',
				'separator'	=>	'.'
			),
			'MMK'			=>	array
			(
				'name'		=>	__('Myanma kyat','car-rental-booking-system'),
				'symbol'	=>	'MMK'
			),
			'MDL'			=>	array
			(
				'name'		=>	__('Moldovan leu','car-rental-booking-system'),
				'symbol'	=>	'MDL'
			),
			'MNT'			=>	array
			(
				'name'		=>	__('Mongolian tugrik','car-rental-booking-system'),
				'symbol'	=>	'MNT'
			),
			'MAD'			=>	array
			(
				'name'		=>	__('Moroccan dirham','car-rental-booking-system'),
				'symbol'	=>	'MAD',
				'position'	=>	'right'
			),
			'MZM'			=>	array
			(
				'name'		=>	__('Mozambican metical','car-rental-booking-system'),
				'symbol'	=>	'MZM'
			),
			'NAD'			=>	array
			(
				'name'		=>	__('Namibian dollar','car-rental-booking-system'),
				'symbol'	=>	'NAD'
			),
			'NPR'			=>	array
			(
				'name'		=>	__('Nepalese rupee','car-rental-booking-system'),
				'symbol'	=>	'NPR'
			),
			'ANG'			=>	array
			(
				'name'		=>	__('Netherlands Antillean gulden','car-rental-booking-system'),
				'symbol'	=>	'ANG'
			),
			'TWD'			=>	array
			(
				'name'		=>	__('New Taiwan dollar','car-rental-booking-system'),
				'symbol'	=>	'&#78;&#84;&#36;',
				'separator'	=>	'.'
			),
			'NZD'			=>	array
			(
				'name'		=>	__('New Zealand dollar','car-rental-booking-system'),
				'symbol'	=>	'&#36;',
				'separator'	=>	'.'
			),
			'NIO'			=>	array
			(
				'name'		=>	__('Nicaraguan cordoba','car-rental-booking-system'),
				'symbol'	=>	'NIO',
				'separator'	=>	'.'
			),
			'NGN'			=>	array
			(
				'name'		=>	__('Nigerian naira','car-rental-booking-system'),
				'symbol'	=>	'NGN',
				'separator'	=>	'.'
			),
			'KPW'			=>	array
			(
				'name'		=>	__('North Korean won','car-rental-booking-system'),
				'symbol'	=>	'KPW',
				'separator'	=>	'.'
			),
			'NOK'			=>	array
			(
				'name'		=>	__('Norwegian krone','car-rental-booking-system'),
				'symbol'	=>	'&#107;&#114;'
			),
			'OMR'			=>	array
			(
				'name'		=>	__('Omani rial','car-rental-booking-system'),
				'symbol'	=>	'OMR',
				'separator'	=>	'&#1643;'
			),
			'TOP'			=>	array
			(
				'name'		=>	__('Paanga','car-rental-booking-system'),
				'symbol'	=>	'TOP'
			),
			'PKR'			=>	array
			(
				'name'		=>	__('Pakistani rupee','car-rental-booking-system'),
				'symbol'	=>	'PKR',
				'separator'	=>	'.'
			),
			'PAB'			=>	array
			(
				'name'		=>	__('Panamanian balboa','car-rental-booking-system'),
				'symbol'	=>	'PAB',
				'separator'	=>	'.'
			),
			'PGK'			=>	array
			(
				'name'		=>	__('Papua New Guinean kina','car-rental-booking-system'),
				'symbol'	=>	'PGK'
			),
			'PYG'			=>	array
			(
				'name'		=>	__('Paraguayan guarani','car-rental-booking-system'),
				'symbol'	=>	'PYG'
			),
			'PEN'			=>	array
			(
				'name'		=>	__('Peruvian nuevo sol','car-rental-booking-system'),
				'symbol'	=>	'PEN'
			),
			'PHP'			=>	array
			(
				'name'		=>	__('Philippine peso','car-rental-booking-system'),
				'symbol'	=>	'&#8369;'
			),
			'PLN'			=>	array
			(
				'name'		=>	__('Polish zloty','car-rental-booking-system'),
				'symbol'	=>	'&#122;&#322;',
				'position'	=>	'right'
			),
			'QAR'			=>	array
			(
				'name'		=>	__('Qatari riyal','car-rental-booking-system'),
				'symbol'	=>	'QAR',
				'separator'	=>	'&#1643;'
			),
			'RON'			=>	array
			(
				'name'		=>	__('Romanian leu','car-rental-booking-system'),
				'symbol'	=>	'lei'
			),
			'RUB'			=>	array
			(
				'name'		=>	__('Russian ruble','car-rental-booking-system'),
				'symbol'	=>	'RUB'
			),
			'RWF'			=>	array
			(
				'name'		=>	__('Rwandan franc','car-rental-booking-system'),
				'symbol'	=>	'RWF'
			),
			'SHP'			=>	array
			(
				'name'		=>	__('Saint Helena pound','car-rental-booking-system'),
				'symbol'	=>	'SHP'
			),
			'WST'			=>	array
			(
				'name'		=>	__('Samoan tala','car-rental-booking-system'),
				'symbol'	=>	'WST'
			),
			'STD'			=>	array
			(
				'name'		=>	__('Sao Tome and Principe dobra','car-rental-booking-system'),
				'symbol'	=>	'STD'
			),
			'SAR'			=>	array
			(
				'name'		=>	__('Saudi riyal','car-rental-booking-system'),
				'symbol'	=>	'SAR',
				'separator'	=>	'&#1643;'
			),
			'SCR'			=>	array
			(
				'name'		=>	__('Seychellois rupee','car-rental-booking-system'),
				'symbol'	=>	'SCR'
			),
			'RSD'			=>	array
			(
				'name'		=>	__('Serbian dinar','car-rental-booking-system'),
				'symbol'	=>	'RSD'
			),
			'SLL'			=>	array
			(
				'name'		=>	__('Sierra Leonean leone','car-rental-booking-system'),
				'symbol'	=>	'SLL'
			),
			'SGD'			=>	array
			(
				'name'		=>	__('Singapore dollar','car-rental-booking-system'),
				'symbol'	=>	'&#36;',
				'separator'	=>	'.'
			),
			'SYP'			=>	array
			(
				'name'		=>	__('Syrian pound','car-rental-booking-system'),
				'symbol'	=>	'SYP',
				'separator'	=>	'&#1643;'
			),
			'SKK'			=>	array
			(
				'name'		=>	__('Slovak koruna','car-rental-booking-system'),
				'symbol'	=>	'SKK'
			),
			'SBD'			=>	array
			(
				'name'		=>	__('Solomon Islands dollar','car-rental-booking-system'),
				'symbol'	=>	'SBD'
			),
			'SOS'			=>	array
			(
				'name'		=>	__('Somali shilling','car-rental-booking-system'),
				'symbol'	=>	'SOS'
			),
			'ZAR'			=>	array
			(
				'name'		=>	__('South African rand','car-rental-booking-system'),
				'symbol'	=>	'&#82;'
			),
			'KRW'			=>	array
			(
				'name'		=>	__('South Korean won','car-rental-booking-system'),
				'symbol'	=>	'&#8361;',
				'separator'	=>	'.'
			),
			'XDR'			=>	array
			(
				'name'		=>	__('Special Drawing Rights','car-rental-booking-system'),
				'symbol'	=>	'XDR'
			),
			'LKR'			=>	array
			(
				'name'		=>	__('Sri Lankan rupee','car-rental-booking-system'),
				'symbol'	=>	'LKR',
				'separator'	=>	'.'
			),
			'SDG'			=>	array
			(
				'name'		=>	__('Sudanese pound','car-rental-booking-system'),
				'symbol'	=>	'SDG'
			),
			'SRD'			=>	array
			(
				'name'		=>	__('Surinamese dollar','car-rental-booking-system'),
				'symbol'	=>	'SRD'
			),
			'SZL'			=>	array
			(
				'name'		=>	__('Swazi lilangeni','car-rental-booking-system'),
				'symbol'	=>	'SZL'
			),
			'SEK'			=>	array
			(
				'name'		=>	__('Swedish krona','car-rental-booking-system'),
				'symbol'	=>	'&#107;&#114;'
			),
			'CHF'			=>	array
			(
				'name'		=>	__('Swiss franc','car-rental-booking-system'),
				'symbol'	=>	'&#67;&#72;&#70;',
				'separator'	=>	'.'
			),
			'TJS'			=>	array
			(
				'name'		=>	__('Tajikistani somoni','car-rental-booking-system'),
				'symbol'	=>	'TJS'
			),
			'TZS'			=>	array
			(
				'name'		=>	__('Tanzanian shilling','car-rental-booking-system'),
				'symbol'	=>	'TZS'
			),
			'THB'			=>	array
			(
				'name'		=>	__('Thai baht','car-rental-booking-system'),
				'symbol'	=>	'&#3647;'
			),
			'TTD'			=>	array
			(
				'name'		=>	__('Trinidad and Tobago dollar','car-rental-booking-system'),
				'symbol'	=>	'TTD'
			),
			'TND'			=>	array
			(
				'name'		=>	__('Tunisian dinar','car-rental-booking-system'),
				'symbol'	=>	'TND'
			),
			'TRY'			=>	array
			(
				'name'		=>	__('Turkish new lira','car-rental-booking-system'),
				'symbol'	=>	'&#84;&#76;'
			),
			'TMM'			=>	array
			(
				'name'		=>	__('Turkmen manat','car-rental-booking-system'),
				'symbol'	=>	'TMM'
			),
			'AED'			=>	array
			(
				'name'		=>	__('UAE dirham','car-rental-booking-system'),
				'symbol'	=>	'AED'
			),
			'UGX'			=>	array
			(
				'name'		=>	__('Ugandan shilling','car-rental-booking-system'),
				'symbol'	=>	'UGX'
			),
			'UAH'			=>	array
			(
				'name'		=>	__('Ukrainian hryvnia','car-rental-booking-system'),
				'symbol'	=>	'UAH'
			),
			'USD'			=>	array
			(
				'name'		=>	__('United States dollar','car-rental-booking-system'),
				'symbol'	=>	'&#36;',
				'position'	=>	'left',
				'separator'	=>	'.',
                'separator2'=>  ','
			),
			'UYU'			=>	array
			(
				'name'		=>	__('Uruguayan peso','car-rental-booking-system'),
				'symbol'	=>	'UYU'
			),
			'UZS'			=>	array
			(
				'name'		=>	__('Uzbekistani som','car-rental-booking-system'),
				'symbol'	=>	'UZS'
			),
			'VUV'			=>	array
			(
				'name'		=>	__('Vanuatu vatu','car-rental-booking-system'),
				'symbol'	=>	'VUV'
			),
			'VEF'			=>	array
			(
				'name'		=>	__('Venezuelan bolivar','car-rental-booking-system'),
				'symbol'	=>	'VEF'
			),
			'VND'			=>	array
			(
				'name'		=>	__('Vietnamese dong','car-rental-booking-system'),
				'symbol'	=>	'VND'
			),
			'XOF'			=>	array
			(
				'name'		=>	__('West African CFA franc','car-rental-booking-system'),
				'symbol'	=>	'XOF'
			),
			'ZMK'			=>	array
			(
				'name'		=>	__('Zambian kwacha','car-rental-booking-system'),
				'symbol'	=>	'ZMK'
			),
			'ZWD'			=>	array
			(
				'name'		=>	__('Zimbabwean dollar','car-rental-booking-system'),
				'symbol'	=>	'ZWD'
			),
			'RMB'			=>	array
			(
				'name'		=>	__('Chinese Yuan','car-rental-booking-system'),
				'symbol'	=>	'&yen;',
				'separator'	=>	'.'
			)
		);
        
        $this->useDefault();
	}
    
    /**************************************************************************/
    
    function useDefault()
    {
        foreach($this->currency as $index=>$value)
        {
            if(!array_key_exists('separator',$value))
                $this->currency[$index]['separator']='.';
            if(!array_key_exists('separator2',$value))
                $this->currency[$index]['separator2']='';
            if(!array_key_exists('position',$value))
                $this->currency[$index]['position']='left';            
        }
    }
	
	/**************************************************************************/
	
	function getCurrency($currency=null)
	{
        if(is_null($currency))
            return($this->currency);
        else return($this->currency[$currency]);
	}
	
	/**************************************************************************/
	
	function isCurrency($currency)
	{
		return(array_key_exists($currency,$this->getCurrency()));
	}
	
	/**************************************************************************/

    static function getBaseCurrency()
    {
        return(CRBSOption::getOption('currency'));
    }
	
	/**************************************************************************/
	
    static function getFormCurrency()
    {
        if(array_key_exists('currency',$_GET))
            $currency=CRBSHelper::getGetValue('currency',false);
        else $currency=CRBSHelper::getPostValue('currency');
        
        return($currency);
    }
    
    /**************************************************************************/
    
    static function getExchangeRate()
    {
        $rate=1;
        
        if(CRBSCurrency::getBaseCurrency()!=CRBSCurrency::getFormCurrency())
        {
            $rate=0;
            $dictionary=CRBSOption::getOption('currency_exchange_rate');
            
            if(array_key_exists(CRBSCurrency::getFormCurrency(),$dictionary))
                $rate=$dictionary[CRBSCurrency::getFormCurrency()];
        }
        
        return($rate);
    }
	
	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/