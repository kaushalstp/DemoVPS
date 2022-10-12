

export default { 
	fields:[				
		{
			type: "select",	        
	        label: "Send To",
	        model: "send_to",
	        values: function() {
		      return [
		        { id: "all", name: "All"},
		        { id: "selectedids", name: "Selected Users" },			        	       
		      ]
    		}, 
    		selectOptions: {
	            noneSelectedText: ' ',
	        },    		
    		required: true,	     		   	 	           		
		},
		{
			type: "input",
	        inputType: "text",
	        label: "Subject",
	        model: "subject",	
	        required: true,	        
		},									
		{
			type: "textArea",
	        inputType: "textArea",
	        label: "Message",
	        model: "text",	
	        required: true,		              
	        rows:5,	 
		},
		{
			type: "textArea",
	        inputType: "userlist",
	        label: "User IDs",
	        model: "userlist",	        
	        visible: function(model) {
			    //visible if business is selected
			    return model && model.send_to == "selectedids";
			},
			required: function(model) {
			    //visible if business is selected
			    return model && model.send_to == "selectedids";
			},

		},		
	]
}