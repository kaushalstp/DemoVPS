

export default { 
	fields:[		
		{
			type: "input",
	        inputType: "hidden",
	        label: "",
	        model: "userid",	        
		},
		{
			type: "input",
	        inputType: "text",
	        label: "Title",
	        model: "title",	        
		},
		{
			type: "input",
	        inputType: "text",
	        label: "First Name",
	        model: "first_name",
	        required: true,	        
		},
		{
			type: "input",
	        inputType: "text",
	        label: "Last Name",
	        model: "last_name",
	        required: true,	        
		},		
		{
			type: "input",
	        inputType: "text",
	        label: "Email",
	        model: "email",
	        disabled:true,	        
		},		
		{
			type: "input",
	        inputType: "text",
	        label: "Phone No",
	        model: "phone",	        
		},		
		{
			type: "input",
	        inputType: "text",
	        label: "Company Website",
	        model: "website",	        
		},		
		{
			type: "textArea",
	        inputType: "textArea",
	        label: "Address",
	        model: "address",	        
		},		
	]
}