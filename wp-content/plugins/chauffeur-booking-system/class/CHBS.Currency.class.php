<?php

/******************************************************************************/
/******************************************************************************/

class CHBSCurrency
{
	/**************************************************************************/
	
	function __construct()
	{
		$this->currency=array
		(
			'AFN'																=>	array
			(
				'name'															=>	__('Afghan afghani','chauffeur-booking-system'),
				'symbol'														=>	'AFN'
			),
			'ALL'																=>	array
			(
				'name'															=>	__('Albanian lek','chauffeur-booking-system'),
				'symbol'														=>	'ALL'
			),
			'DZD'																=>	array
			(
				'name'															=>	__('Algerian dinar','chauffeur-booking-system'),
				'symbol'														=>	'DZD'
			),
			'AOA'																=>	array
			(
				'name'															=>	__('Angolan kwanza','chauffeur-booking-system'),
				'symbol'														=>	'AOA'
			),
			'ARS'																=>	array
			(
				'name'															=>	__('Argentine peso','chauffeur-booking-system'),
				'symbol'														=>	'ARS'
			),
			'AMD'																=>	array
			(
				'name'															=>	__('Armenian dram','chauffeur-booking-system'),
				'symbol'														=>	'AMD'
			),
			'AWG'																=>	array
			(
				'name'															=>	__('Aruban florin','chauffeur-booking-system'),
				'symbol'														=>	'AWG'
			),
			'AUD'																=>	array
			(
				'name'															=>	__('Australian dollar','chauffeur-booking-system'),
				'symbol'														=>	'&#36;',
				'separator'														=>	'.'
			),
			'AZN'																=>	array
			(
				'name'															=>	__('Azerbaijani manat','chauffeur-booking-system'),
				'symbol'														=>	'AZN'
			),
			'BSD'																=>	array
			(
				'name'															=>	__('Bahamian dollar','chauffeur-booking-system'),
				'symbol'														=>	'BSD'
			),
			'BHD'																=>	array
			(
				'name'															=>	__('Bahraini dinar','chauffeur-booking-system'),
				'symbol'														=>	'BHD',
				'separator'														=>	'&#1643;'
			),
			'BDT'																=>	array
			(
				'name'															=>	__('Bangladeshi taka','chauffeur-booking-system'),
				'symbol'														=>	'BDT'
			),
			'BBD'																=>	array
			(
				'name'															=>	__('Barbadian dollar','chauffeur-booking-system'),
				'symbol'														=>	'BBD'
			),
			'BYR'																=>	array
			(
				'name'															=>	__('Belarusian ruble','chauffeur-booking-system'),
				'symbol'														=>	'BYR'
			),
			'BZD'																=>	array
			(
				'name'															=>	__('Belize dollar','chauffeur-booking-system'),
				'symbol'														=>	'BZD'
			),
			'BTN'																=>	array
			(
				'name'															=>	__('Bhutanese ngultrum','chauffeur-booking-system'),
				'symbol'														=>	'BTN'
			),
			'BOB'																=>	array
			(
				'name'															=>	__('Bolivian boliviano','chauffeur-booking-system'),
				'symbol'														=>	'BOB'
			),
			'BAM'																=>	array
			(
				'name'															=>	__('Bosnia and Herzegovina konvertibilna marka','chauffeur-booking-system'),
				'symbol'														=>	'BAM'
			),
			'BWP'																=>	array
			(
				'name'															=>	__('Botswana pula','chauffeur-booking-system'),
				'symbol'														=>	'BWP',
				'separator'														=>	'.'
			),
			'BRL'																=>	array
			(
				'name'															=>	__('Brazilian real','chauffeur-booking-system'),
				'symbol'														=>	'&#82;&#36;'
			),
			'GBP'																=>	array
			(
				'name'															=>	__('British pound','chauffeur-booking-system'),
				'symbol'														=>	'&pound;',
				'position'														=>	'left',
				'separator'														=>	'.',
			),
			'BND'																=>	array
			(
				'name'															=>	__('Brunei dollar','chauffeur-booking-system'),
				'symbol'														=>	'BND',
				'separator'														=>	'.'
			),
			'BGN'																=>	array
			(
				'name'															=>	__('Bulgarian lev','chauffeur-booking-system'),
				'symbol'														=>	'BGN'
			),
			'BIF'																=>	array
			(
				'name'															=>	__('Burundi franc','chauffeur-booking-system'),
				'symbol'														=>	'BIF'
			),
			'KYD'																=>	array
			(
				'name'															=>	__('Cayman Islands dollar','chauffeur-booking-system'),
				'symbol'														=>	'KYD'
			),
			'KHR'																=>	array
			(
				'name'															=>	__('Cambodian riel','chauffeur-booking-system'),
				'symbol'														=>	'KHR'
			),
			'CAD'																=>	array
			(
				'name'															=>	__('Canadian dollar','chauffeur-booking-system'),
				'symbol'														=>	'CAD',
				'separator'														=>	'.'
			),
			'CVE'																=>	array
			(
				'name'															=>	__('Cape Verdean escudo','chauffeur-booking-system'),
				'symbol'														=>	'CVE'
			),
			'XAF'																=>	array
			(
				'name'															=>	__('Central African CFA franc','chauffeur-booking-system'),
				'symbol'														=>	'XAF'
			),
			'GQE'																=>	array
			(
				'name'															=>	__('Central African CFA franc','chauffeur-booking-system'),
				'symbol'														=>	'GQE'
			),
			'XPF'																=>	array
			(
				'name'															=>	__('CFP franc','chauffeur-booking-system'),
				'symbol'														=>	'XPF'
			),
			'CLP'																=>	array
			(
				'name'															=>	__('Chilean peso','chauffeur-booking-system'),
				'symbol'														=>	'CLP'
			),
			'CNY'																=>	array
			(
				'name'															=>	__('Chinese renminbi','chauffeur-booking-system'),
				'symbol'														=>	'&yen;'
			),
			'COP'																=>	array
			(
				'name'															=>	__('Colombian peso','chauffeur-booking-system'),
				'symbol'														=>	'COP'
			),
			'KMF'																=>	array
			(
				'name'															=>	__('Comorian franc','chauffeur-booking-system'),
				'symbol'														=>	'KMF'
			),
			'CDF'																=>	array
			(
				'name'															=>	__('Congolese franc','chauffeur-booking-system'),
				'symbol'														=>	'CDF'
			),
			'CRC'																=>	array
			(
				'name'															=>	__('Costa Rican colon','chauffeur-booking-system'),
				'symbol'														=>	'CRC'
			),
			'HRK'																=>	array
			(
				'name'															=>	__('Croatian kuna','chauffeur-booking-system'),
				'symbol'														=>	'HRK'
			),
			'CUC'																=>	array
			(
				'name'															=>	__('Cuban peso','chauffeur-booking-system'),
				'symbol'														=>	'CUC'
			),
			'CZK'																=>	array
			(
				'name'															=>	__('Czech koruna','chauffeur-booking-system'),
				'symbol'														=>	'&#75;&#269;'
			),
			'DKK'																=>	array
			(
				'name'															=>	__('Danish krone','chauffeur-booking-system'),
				'symbol'														=>	'&#107;&#114;'
			),
			'DJF'																=>	array
			(
				'name'															=>	__('Djiboutian franc','chauffeur-booking-system'),
				'symbol'														=>	'DJF'
			),
			'DOP'																=>	array
			(
				'name'															=>	__('Dominican peso','chauffeur-booking-system'),
				'symbol'														=>	'DOP',
				'separator'														=>	'.'
			),
			'XCD'																=>	array
			(
				'name'															=>	__('East Caribbean dollar','chauffeur-booking-system'),
				'symbol'														=>	'XCD'
			),
			'EGP'                                                               =>	array
			(
				'name'															=>	__('Egyptian pound','chauffeur-booking-system'),
				'symbol'														=>	'EGP'
			),
			'ERN'																=>	array
			(
				'name'															=>	__('Eritrean nakfa','chauffeur-booking-system'),
				'symbol'														=>	'ERN'
			),
			'EEK'																=>	array
			(
				'name'															=>	__('Estonian kroon','chauffeur-booking-system'),
				'symbol'														=>	'EEK'
			),
			'ETB'																=>	array
			(
				'name'															=>	__('Ethiopian birr','chauffeur-booking-system'),
				'symbol'														=>	'ETB'
			),
			'EUR'																=>	array
			(
				'name'															=>	__('European euro','chauffeur-booking-system'),
				'symbol'														=>	'&euro;'
			),
			'FKP'																=>	array
			(
				'name'															=>	__('Falkland Islands pound','chauffeur-booking-system'),
				'symbol'														=>	'FKP'
			),
			'FJD'																=>	array
			(
				'name'															=>	__('Fijian dollar','chauffeur-booking-system'),
				'symbol'														=>	'FJD',
				'separator'														=>	'.'
			),
			'GMD'																=>	array
			(
				'name'															=>	__('Gambian dalasi','chauffeur-booking-system'),
				'symbol'														=>	'GMD'
			),
			'GEL'																=>	array
			(
				'name'															=>	__('Georgian lari','chauffeur-booking-system'),
				'symbol'														=>	'GEL'
			),
			'GHS'																=>	array
			(
				'name'															=>	__('Ghanaian cedi','chauffeur-booking-system'),
				'symbol'														=>	'GHS'
			),
			'GIP'																=>	array
			(
				'name'															=>	__('Gibraltar pound','chauffeur-booking-system'),
				'symbol'														=>	'GIP'
			),
			'GTQ'																=>	array
			(
				'name'															=>	__('Guatemalan quetzal','chauffeur-booking-system'),
				'symbol'														=>	'GTQ',
				'separator'														=>	'.'
			),
			'GNF'																=>	array
			(
				'name'															=>	__('Guinean franc','chauffeur-booking-system'),
				'symbol'														=>	'GNF'
			),
			'GYD'																=>	array
			(
				'name'															=>	__('Guyanese dollar','chauffeur-booking-system'),
				'symbol'														=>	'GYD'
			),
			'HTG'																=>	array
			(
				'name'															=>	__('Haitian gourde','chauffeur-booking-system'),
				'symbol'														=>	'HTG'
			),
			'HNL'																=>	array
			(
				'name'															=>	__('Honduran lempira','chauffeur-booking-system'),
				'symbol'														=>	'HNL',
				'separator'														=>	'.'
			),
			'HKD'																=>	array
			(
				'name'															=>	__('Hong Kong dollar','chauffeur-booking-system'),
				'symbol'														=>	'&#36;',
				'separator'														=>	'.'
			),
			'HUF'																=>	array
			(
				'name'															=>	__('Hungarian forint','chauffeur-booking-system'),
				'symbol'														=>	'&#70;&#116;'
			),
			'ISK'																=>	array
			(
				'name'															=>	__('Icelandic krona','chauffeur-booking-system'),
				'symbol'														=>	'ISK'
			),
			'INR'																=>	array
			(
				'name'															=>	__('Indian rupee','chauffeur-booking-system'),
				'symbol'														=>	'&#8377;',
				'separator'														=>	'.'
			),
			'IDR'																=>	array
			(
				'name'															=>	__('Indonesian rupiah','chauffeur-booking-system'),
				'symbol'														=>	'Rp',
				'position'														=>	'left'
			),
			'IRR'																=>	array
			(
				'name'															=>	__('Iranian rial','chauffeur-booking-system'),
				'symbol'														=>	'IRR',
				'separator'														=>	'&#1643;'
			),
			'IQD'																=>	array
			(
				'name'															=>	__('Iraqi dinar','chauffeur-booking-system'),
				'symbol'														=>	'IQD',
				'separator'														=>	'&#1643;'
			),
			'ILS'																=>	array
			(
				'name'															=>	__('Israeli new sheqel','chauffeur-booking-system'),
				'symbol'														=>	'&#8362;',
				'separator'														=>	'.'
			),
			'YER'																=>	array
			(
				'name'															=>	__('Yemeni rial','chauffeur-booking-system'),
				'symbol'														=>	'YER'
			),
			'JMD'																=>	array
			(
				'name'															=>	__('Jamaican dollar','chauffeur-booking-system'),
				'symbol'														=>	'JMD'
			),
			'JPY'																=>	array
			(
				'name'															=>	__('Japanese yen','chauffeur-booking-system'),
				'symbol'														=>	'&yen;',
				'separator'														=>	'.'
			),
			'JOD'																=>	array
			(
				'name'															=>	__('Jordanian dinar','chauffeur-booking-system'),
				'symbol'														=>	'JOD'
			),
			'KZT'																=>	array
			(
				'name'															=>	__('Kazakhstani tenge','chauffeur-booking-system'),
				'symbol'														=>	'KZT'
			),
			'KES'																=>	array
			(
				'name'															=>	__('Kenyan shilling','chauffeur-booking-system'),
				'symbol'														=>	'KES'
			),
			'KGS'																=>	array
			(
				'name'															=>	__('Kyrgyzstani som','chauffeur-booking-system'),
				'symbol'														=>	'KGS'
			),
			'KWD'																=>	array
			(
				'name'															=>	__('Kuwaiti dinar','chauffeur-booking-system'),
				'symbol'														=>	'KWD',
				'separator'														=>	'&#1643;'
			),
			'LAK'																=>	array
			(
				'name'															=>	__('Lao kip','chauffeur-booking-system'),
				'symbol'														=>	'LAK'
			),
			'LVL'																=>	array
			(
				'name'															=>	__('Latvian lats','chauffeur-booking-system'),
				'symbol'														=>	'LVL'
			),
			'LBP'																=>	array
			(
				'name'															=>	__('Lebanese lira','chauffeur-booking-system'),
				'symbol'														=>	'LBP'
			),
			'LSL'																=>	array
			(
				'name'															=>	__('Lesotho loti','chauffeur-booking-system'),
				'symbol'														=>	'LSL'
			),
			'LRD'																=>	array
			(
				'name'															=>	__('Liberian dollar','chauffeur-booking-system'),
				'symbol'														=>	'LRD'
			),
			'LYD'																=>	array
			(
				'name'															=>	__('Libyan dinar','chauffeur-booking-system'),
				'symbol'														=>	'LYD'
			),
			'LTL'																=>	array
			(
				'name'															=>	__('Lithuanian litas','chauffeur-booking-system'),
				'symbol'														=>	'LTL'
			),
			'MOP'																=>	array
			(
				'name'															=>	__('Macanese pataca','chauffeur-booking-system'),
				'symbol'														=>	'MOP'
			),
			'MKD'																=>	array
			(
				'name'															=>	__('Macedonian denar','chauffeur-booking-system'),
				'symbol'														=>	'MKD'
			),
			'MGA'																=>	array
			(
				'name'															=>	__('Malagasy ariary','chauffeur-booking-system'),
				'symbol'														=>	'MGA'
			),
			'MYR'																=>	array
			(
				'name'															=>	__('Malaysian ringgit','chauffeur-booking-system'),
				'symbol'														=>	'&#82;&#77;',
				'separator'														=>	'.'
			),
			'MWK'																=>	array
			(
				'name'															=>	__('Malawian kwacha','chauffeur-booking-system'),
				'symbol'														=>	'MWK'
			),
			'MVR'																=>	array
			(
				'name'															=>	__('Maldivian rufiyaa','chauffeur-booking-system'),
				'symbol'														=>	'MVR'
			),
			'MRO'																=>	array
			(
				'name'															=>	__('Mauritanian ouguiya','chauffeur-booking-system'),
				'symbol'														=>	'MRO'
			),
			'MUR'																=>	array
			(
				'name'															=>	__('Mauritian rupee','chauffeur-booking-system'),
				'symbol'														=>	'MUR'
			),
			'MXN'																=>	array
			(
				'name'															=>	__('Mexican peso','chauffeur-booking-system'),
				'symbol'														=>	'&#36;',
				'separator'														=>	'.'
			),
			'MMK'																=>	array
			(
				'name'															=>	__('Myanma kyat','chauffeur-booking-system'),
				'symbol'														=>	'MMK'
			),
			'MDL'																=>	array
			(
				'name'															=>	__('Moldovan leu','chauffeur-booking-system'),
				'symbol'														=>	'MDL'
			),
			'MNT'																=>	array
			(
				'name'															=>	__('Mongolian tugrik','chauffeur-booking-system'),
				'symbol'														=>	'MNT'
			),
			'MAD'																=>	array
			(
				'name'															=>	__('Moroccan dirham','chauffeur-booking-system'),
				'symbol'														=>	'MAD'
			),
			'MZM'																=>	array
			(
				'name'															=>	__('Mozambican metical','chauffeur-booking-system'),
				'symbol'														=>	'MZM'
			),
			'NAD'																=>	array
			(
				'name'															=>	__('Namibian dollar','chauffeur-booking-system'),
				'symbol'														=>	'NAD'
			),
			'NPR'																=>	array
			(
				'name'															=>	__('Nepalese rupee','chauffeur-booking-system'),
				'symbol'														=>	'NPR'
			),
			'ANG'																=>	array
			(
				'name'															=>	__('Netherlands Antillean gulden','chauffeur-booking-system'),
				'symbol'														=>	'ANG'
			),
			'TWD'																=>	array
			(
				'name'															=>	__('New Taiwan dollar','chauffeur-booking-system'),
				'symbol'														=>	'&#78;&#84;&#36;',
				'separator'														=>	'.'
			),
			'NZD'																=>	array
			(
				'name'															=>	__('New Zealand dollar','chauffeur-booking-system'),
				'symbol'														=>	'&#36;',
				'separator'														=>	'.'
			),
			'NIO'																=>	array
			(
				'name'															=>	__('Nicaraguan cordoba','chauffeur-booking-system'),
				'symbol'														=>	'NIO',
				'separator'														=>	'.'
			),
			'NGN'																=>	array
			(
				'name'															=>	__('Nigerian naira','chauffeur-booking-system'),
				'symbol'														=>	'NGN',
				'separator'														=>	'.'
			),
			'KPW'																=>	array
			(
				'name'															=>	__('North Korean won','chauffeur-booking-system'),
				'symbol'														=>	'KPW',
				'separator'														=>	'.'
			),
			'NOK'																=>	array
			(
				'name'															=>	__('Norwegian krone','chauffeur-booking-system'),
				'symbol'														=>	'&#107;&#114;'
			),
			'OMR'																=>	array
			(
				'name'															=>	__('Omani rial','chauffeur-booking-system'),
				'symbol'														=>	'OMR',
				'separator'														=>	'&#1643;'
			),
			'TOP'																=>	array
			(
				'name'															=>	__('Paanga','chauffeur-booking-system'),
				'symbol'														=>	'TOP'
			),
			'PKR'																=>	array
			(
				'name'															=>	__('Pakistani rupee','chauffeur-booking-system'),
				'symbol'														=>	'PKR',
				'separator'														=>	'.'
			),
			'PAB'																=>	array
			(
				'name'															=>	__('Panamanian balboa','chauffeur-booking-system'),
				'symbol'														=>	'PAB',
				'separator'														=>	'.'
			),
			'PGK'																=>	array
			(
				'name'															=>	__('Papua New Guinean kina','chauffeur-booking-system'),
				'symbol'														=>	'PGK'
			),
			'PYG'																=>	array
			(
				'name'															=>	__('Paraguayan guarani','chauffeur-booking-system'),
				'symbol'														=>	'PYG'
			),
			'PEN'																=>	array
			(
				'name'															=>	__('Peruvian nuevo sol','chauffeur-booking-system'),
				'symbol'														=>	'PEN'
			),
			'PHP'																=>	array
			(
				'name'															=>	__('Philippine peso','chauffeur-booking-system'),
				'symbol'														=>	'&#8369;'
			),
			'PLN'																=>	array
			(
				'name'															=>	__('Polish zloty','chauffeur-booking-system'),
				'symbol'														=>	'zł',
				'position'														=>	'right',
			),
			'QAR'																=>	array
			(
				'name'															=>	__('Qatari riyal','chauffeur-booking-system'),
				'symbol'														=>	'QAR',
				'separator'														=>	'&#1643;'
			),
			'RON'																=>	array
			(
				'name'															=>	__('Romanian leu','chauffeur-booking-system'),
				'symbol'														=>	'lei'
			),
			'RUB'																=>	array
			(
				'name'															=>	__('Russian ruble','chauffeur-booking-system'),
				'symbol'														=>	'RUB'
			),
			'RWF'																=>	array
			(
				'name'															=>	__('Rwandan franc','chauffeur-booking-system'),
				'symbol'														=>	'RWF'
			),
			'SHP'																=>	array
			(
				'name'															=>	__('Saint Helena pound','chauffeur-booking-system'),
				'symbol'														=>	'SHP'
			),
			'WST'																=>	array
			(
				'name'															=>	__('Samoan tala','chauffeur-booking-system'),
				'symbol'														=>	'WST'
			),
			'STD'																=>	array
			(
				'name'															=>	__('Sao Tome and Principe dobra','chauffeur-booking-system'),
				'symbol'														=>	'STD'
			),
			'SAR'																=>	array
			(
				'name'															=>	__('Saudi riyal','chauffeur-booking-system'),
				'symbol'														=>	'SAR',
				'separator'														=>	'&#1643;'
			),
			'SCR'																=>	array
			(
				'name'															=>	__('Seychellois rupee','chauffeur-booking-system'),
				'symbol'														=>	'SCR'
			),
			'RSD'																=>	array
			(
				'name'															=>	__('Serbian dinar','chauffeur-booking-system'),
				'symbol'														=>	'RSD'
			),
			'SLL'																=>	array
			(
				'name'															=>	__('Sierra Leonean leone','chauffeur-booking-system'),
				'symbol'														=>	'SLL'
			),
			'SGD'																=>	array
			(
				'name'															=>	__('Singapore dollar','chauffeur-booking-system'),
				'symbol'														=>	'&#36;',
				'separator'														=>	'.'
			),
			'SYP'																=>	array
			(
				'name'															=>	__('Syrian pound','chauffeur-booking-system'),
				'symbol'														=>	'SYP',
				'separator'														=>	'&#1643;'
			),
			'SKK'																=>	array
			(
				'name'															=>	__('Slovak koruna','chauffeur-booking-system'),
				'symbol'														=>	'SKK'
			),
			'SBD'																=>	array
			(
				'name'															=>	__('Solomon Islands dollar','chauffeur-booking-system'),
				'symbol'														=>	'SBD'
			),
			'SOS'																=>	array
			(
				'name'															=>	__('Somali shilling','chauffeur-booking-system'),
				'symbol'														=>	'SOS'
			),
			'ZAR'																=>	array
			(
				'name'															=>	__('South African rand','chauffeur-booking-system'),
				'symbol'														=>	'&#82;'
			),
			'KRW'																=>	array
			(
				'name'															=>	__('South Korean won','chauffeur-booking-system'),
				'symbol'														=>	'&#8361;',
				'separator'														=>	'.'
			),
			'XDR'																=>	array
			(
				'name'															=>	__('Special Drawing Rights','chauffeur-booking-system'),
				'symbol'														=>	'XDR'
			),
			'LKR'																=>	array
			(
				'name'															=>	__('Sri Lankan rupee','chauffeur-booking-system'),
				'symbol'														=>	'LKR',
				'separator'														=>	'.'
			),
			'SDG'																=>	array
			(
				'name'															=>	__('Sudanese pound','chauffeur-booking-system'),
				'symbol'														=>	'SDG'
			),
			'SRD'																=>	array
			(
				'name'															=>	__('Surinamese dollar','chauffeur-booking-system'),
				'symbol'														=>	'SRD'
			),
			'SZL'																=>	array
			(
				'name'															=>	__('Swazi lilangeni','chauffeur-booking-system'),
				'symbol'														=>	'SZL'
			),
			'SEK'																=>	array
			(
				'name'															=>	__('Swedish krona','chauffeur-booking-system'),
				'symbol'														=>	'&#107;&#114;'
			),
			'CHF'																=>	array
			(
				'name'															=>	__('Swiss franc','chauffeur-booking-system'),
				'symbol'														=>	'&#67;&#72;&#70;',
				'separator'														=>	'.'
			),
			'TJS'																=>	array
			(
				'name'															=>	__('Tajikistani somoni','chauffeur-booking-system'),
				'symbol'														=>	'TJS'
			),
			'TZS'																=>	array
			(
				'name'															=>	__('Tanzanian shilling','chauffeur-booking-system'),
				'symbol'														=>	'TZS'
			),
			'THB'																=>	array
			(
				'name'															=>	__('Thai baht','chauffeur-booking-system'),
				'symbol'														=>	'&#3647;'
			),
			'TTD'																=>	array
			(
				'name'															=>	__('Trinidad and Tobago dollar','chauffeur-booking-system'),
				'symbol'														=>	'TTD'
			),
			'TND'																=>	array
			(
				'name'															=>	__('Tunisian dinar','chauffeur-booking-system'),
				'symbol'														=>	'TND'
			),
			'TRY'																=>	array
			(
				'name'															=>	__('Turkish new lira','chauffeur-booking-system'),
				'symbol'														=>	'&#84;&#76;'
			),
			'TMM'																=>	array
			(
				'name'															=>	__('Turkmen manat','chauffeur-booking-system'),
				'symbol'														=>	'TMM'
			),
			'AED'																=>	array
			(
				'name'															=>	__('UAE dirham','chauffeur-booking-system'),
				'symbol'														=>	'AED'
			),
			'UGX'																=>	array
			(
				'name'															=>	__('Ugandan shilling','chauffeur-booking-system'),
				'symbol'														=>	'UGX'
			),
			'UAH'																=>	array
			(
				'name'															=>	__('Ukrainian hryvnia','chauffeur-booking-system'),
				'symbol'														=>	'UAH'
			),
			'USD'																=>	array
			(
				'name'															=>	__('United States dollar','chauffeur-booking-system'),
				'symbol'														=>	'&#36;',
				'position'														=>	'left',
				'separator'														=>	'.',
                'separator2'													=>  ','
			),
			'UYU'																=>	array
			(
				'name'															=>	__('Uruguayan peso','chauffeur-booking-system'),
				'symbol'														=>	'UYU'
			),
			'UZS'																=>	array
			(
				'name'															=>	__('Uzbekistani som','chauffeur-booking-system'),
				'symbol'														=>	'UZS'
			),
			'VUV'																=>	array
			(
				'name'															=>	__('Vanuatu vatu','chauffeur-booking-system'),
				'symbol'														=>	'VUV'
			),
			'VEF'																=>	array
			(
				'name'															=>	__('Venezuelan bolivar','chauffeur-booking-system'),
				'symbol'														=>	'VEF'
			),
			'VND'																=>	array
			(
				'name'															=>	__('Vietnamese dong','chauffeur-booking-system'),
				'symbol'														=>	'VND'
			),
			'XOF'																=>	array
			(
				'name'															=>	__('West African CFA franc','chauffeur-booking-system'),
				'symbol'														=>	'XOF'
			),
			'ZMK'																=>	array
			(
				'name'															=>	__('Zambian kwacha','chauffeur-booking-system'),
				'symbol'														=>	'ZMK'
			),
			'ZWD'																=>	array
			(
				'name'															=>	__('Zimbabwean dollar','chauffeur-booking-system'),
				'symbol'														=>	'ZWD'
			),
			'RMB'																=>	array
			(
				'name'															=>	__('Chinese Yuan','chauffeur-booking-system'),
				'symbol'														=>	'&yen;',
				'separator'														=>	'.'
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
        return(CHBSOption::getOption('currency'));
    }
    
    /**************************************************************************/
    
    static function getFormCurrency()
    {
        if(array_key_exists('currency',$_GET))
            $currency=CHBSHelper::getGetValue('currency',false);
        else $currency=CHBSHelper::getPostValue('currency');
        
        return($currency);
    }
    
    /**************************************************************************/
    
    static function getExchangeRate()
    {
        $rate=1;
        
        if(CHBSCurrency::getBaseCurrency()!=CHBSCurrency::getFormCurrency())
        {
            $rate=0;
            $dictionary=CHBSOption::getOption('currency_exchange_rate');
            
            if(array_key_exists(CHBSCurrency::getFormCurrency(),$dictionary))
                $rate=$dictionary[CHBSCurrency::getFormCurrency()];
        }
        
        return($rate);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/