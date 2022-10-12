<?php


return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',	
	//'tempDir'               => base_path('../temp/')
	'tempDir'               => public_path('temp') 	
	//'tempDir'               => base_path('storage/app/mpdf')
	//'tempDir' => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
];
