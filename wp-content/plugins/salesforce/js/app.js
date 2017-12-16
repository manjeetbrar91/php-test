	$(document).ready(function() {
		// $("#user_url").keyup(function(){
			// $("#domain").val(this.value);
		// });
function checkUserPassValidity(name,u_password){
		  var numConsecutiveChars = 1;
		  var username = name.trim().toLowerCase();
		  var pass = u_password.trim().toLowerCase();
		  
		  var invalidCombinations = [];
		  for( var i = 0; i < username.length - numConsecutiveChars; i++ ){
			var curCombination = username[i] + username[i+1];
			invalidCombinations.push( curCombination );
		  }

		  var invalid = false;
		  for( var i = 0; i < invalidCombinations.length; i++ ){
			var curCombination = invalidCombinations[i];
			if( pass.indexOf( curCombination ) !== -1 ){
			  invalid = true;
			  break;
			}
		  }
		  return invalid;
		}		



		$( "#price_start" ).datepicker();
		$( "#price_end" ).datepicker();
		$( "#txtStartDate" ).datepicker();
		$( "#txtEndDate" ).datepicker();
		$.validator.addMethod("letters", function(value, element) 
		{
		  return this.optional(element) || value == value.match(/^[a-zA-Z\s]*$/);
		});
		
		$.validator.addMethod("alphanumeric", function(value, element) 
		{
		  return this.optional(element) || value == value.match(/^[a-zA-Z0-9\s]*$/);
		});
		
		$.validator.addMethod("onlynumbers", function(value, element) 
		{
		  return this.optional(element) || value == value.match(/^[0-9]*$/);
		});
		$.validator.addMethod("letternumberdashpointunderscore", function(value, element) 
		{
		  return this.optional(element) || value == value.match(/^[A-Za-z0-9_.-]*$/);
		});
		$.validator.addMethod("smallcapitalnumber", function(value, element) 
		{
		  return this.optional(element) || value == value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/);
		});
		
		$.validator.addMethod("domainurl", function(value, element) 
		{
		   // return this.optional(element) || value == value.match(/^[0-9\p{L}][0-9\p{L}-\.]{1,61}[0-9\p{L}]\.[0-9\p{L}][\p{L}-]*[0-9\p{L}]+$/);
		   return this.optional(element) || value == value.match(/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/);
		});
		
		
		$("#contact-form").validate({
			rules: {
				first_name:{
					required: true,
					minlength: 2,
					alphanumeric: true,
					maxlength:30
				},
				last_name:{
					required: true,
					minlength: 2,
					alphanumeric: true,
					maxlength:30
				},
				email: {
					required: true,
					// email: true
				},
				mobile: {
                    required: true,
					onlynumbers: true,
                    minlength:10,
                    maxlength:10
					},
				company:{
					required: true,
					minlength: 2,
					maxlength:30
				},
				user_url:{
					required: true,
					domainurl: true,
					
				},
				user_pass:{
					required: true,
					minlength:8,
					smallcapitalnumber:true,
				},
			},
			messages: {
				first_name:{
					required: "Please enter first name",
					minlength: "Please enter a least 2 characters minimum",
					letters: "Please enter alphabets only",
					maxlength: "Please enter maximum 30 characters"
				},
				last_name:{
					required: "Please enter lastname name",
					minlength: "Please enter a least 2 characters minimum",
					letters: "Please enter alphabets only",
					maxlength: "Please enter maximum 30 characters"
				},
				email:{
					required: "Please enter email",
					// email: "Please enter a valid email"
				},
				mobile: {
					required: "Please enter mobile number",
                    onlynumbers: "Please enter numbers only",  // <-- no such method called "matches"!
                    minlength: "Please enter a ten digit mobile number",
                    maxlength: "Please enter a ten digit number only"
				},
				company:{
					required: "Please enter company name",
					minlength: "Please enter a least 2 characters minimum",
					maxlength: "Please enter maximum 30 characters"
				},
				user_url:{
					required: "Please enter domain",
					domainurl: "Please enter domain like abc.com",
				},
				user_pass:{
					required: "Please enter password",
					minlength: "Please enter a least 8 characters minimum",
					smallcapitalnumber: "Password must have at least 8 characters, a lowercase letter, an uppercase letter, a number, no parts of your username.",
				},
			},
			errorContainer: $('#errorContainer'),
			errorLabelContainer: $('#errorContainer ul'),
			wrapper: 'li'
		});
		
		$("#partner-form").validate({
			rules: {
				first_name:{
					required: true,
					alphanumeric: true,
					maxlength:30
				},
				last_name:{
					required: true,
					alphanumeric: true,
					maxlength:30
				},
				email: {
					required: true,
					letternumberdashpointunderscore: true,
					maxlength:100,
					// email: true
				},
				mobile: {
                    required: true,
					onlynumbers: true,
                    minlength:10,
                    maxlength:10
					},
				company:{
					required: true,
					minlength: 2,
					maxlength:30
				},
				user_url:{
					required: true,
					domainurl: true,
					
				},
				domain: {
					required: true,
					domainurl: true,
					equalTo: '#user_url'
				}, 
				user_pass:{
					required: true,
					minlength:8,
					smallcapitalnumber:true,
					// usernamepasswordcheck:true,
				},
				answer:{
					required: true,
					minlength: 4,
					maxlength:100
				},
				security_question:{
					required:true
				}
				
			},
			messages: {
				first_name:{
					required: "Please enter first name",
					minlength: "Please enter a least 4 characters minimum",
					letters: "Please enter alphabets only",
					maxlength: "Please enter maximum 30 characters"
				},
				last_name:{
					required: "Please enter lastname name",
					minlength: "Please enter a least 4 characters minimum",
					letters: "Please enter alphabets only",
					maxlength: "Please enter maximum 30 characters"
				},
				email:{
					required: "Please enter email",
					letternumberdashpointunderscore: "Please enter a valid email"
					// email: "Please enter a valid email"
				},
				mobile: {
					required: "Please enter mobile number",
                    onlynumbers: "Please enter numbers only",  // <-- no such method called "matches"!
                    minlength: "Please enter a ten digit mobile number",
                    maxlength: "Please enter a ten digit number only"
				},
				company:{
					required: "Please enter company name",
					minlength: "Please enter a least 2 characters minimum",
					maxlength: "Please enter maximum 30 characters"
				},
				user_url:{
					required: "Please enter domain",
					domainurl: "Please enter domain like abc.com",
				},
				domain: {
					required: "Please enter domain",
					domainurl: "Please enter domain like abc.com",
					equalTo: "Please enter the same domain"
				}, 
				user_pass:{
					required: "Please enter password",
					minlength: "Please enter at least 8 characters minimum",
					smallcapitalnumber: "Password must have at least 8 characters, a lowercase letter, an uppercase letter, a number, no parts of your firstname and lastname.",
					// usernamepasswordcheck: "hvchxvc";
				},
			},
			submitHandler: function(form) {
				var first_name=$("#first_name").val()
				var last_name=$("#last_name").val()
				var user_pass=$("#user_pass").val()
				//var flag= 0;
				if((checkUserPassValidity(first_name,user_pass)==true) || (checkUserPassValidity(last_name,user_pass)==true))
				{
					$('#error').show();
					//var flag=1;
				}
				else
				{
					$('#error').hide();
					form.submit();
				}		
			},
			errorContainer: $('#errorContainer'),
			errorLabelContainer: $('#errorContainer ul'),
			wrapper: 'li'
		});

		$("#lead-form").validate({
			rules: {
				first_name:{
					required: true,
					minlength: 2,
					alphanumeric: true,
					maxlength:30
				},
				last_name:{
					required: true,
					minlength: 2,
					alphanumeric: true,
					maxlength:30
				},
				email: {
					required: true,
					email: true
				},
				mobile: {
                    required: true,
					onlynumbers: true,
                    minlength:10,
                    maxlength:10
					},
				message:{
					required: true,
					minlength: 2,
					maxlength:30
				},
			},
			messages: {
				first_name:{
					required: "Please enter first name",
					minlength: "Please enter a least 2 characters minimum",
					letters: "Please enter alphabets only",
					maxlength: "Please enter maximum 30 characters"
				},
				last_name:{
					required: "Please enter last name",
					minlength: "Please enter a least 2 characters minimum",
					letters: "Please enter alphabets only",
					maxlength: "Please enter maximum 30 characters"
				},
				email:{
					required: "Please enter email",
					email: "Please enter a valid email"
				},
				mobile: {
					required: "Please enter mobile number",
                    onlynumbers: "Please enter numbers only",  // <-- no such method called "matches"!
                    minlength: "Please enter a ten digit mobile number",
                    maxlength: "Please enter a ten digit number only"
				},
				message:{
					required: "Please enter message",
					minlength: "Please enter a least 2 characters minimum",
					maxlength: "Please enter maximum 30 characters"
				},
			},
			submitHandler: function(form) {
				if (grecaptcha.getResponse()) {
					form.submit();
					$('#captchaerror').hide();
				} else {
					 $('#captchaerror').show();
					//alert('Please confirm captcha to proceed')
				}
			}
		});
		
		$("#subscriber-form").validate({
			rules: {
				email: {
					required: true,
					email: true
				},
				
			},
			messages: {
				email:{
					required: "Please enter email",
					email: "Please enter a valid email"
				},
				
			}
		});
		$("#forgot-form").validate({
			rules: {
				txtEmail: {
					required: true,
					email: true
				},
			},
			messages: {
				txtEmail:{
					required: "Please enter email",
					email: "Please enter a valid email"
				},
			}
		});
		
		$("#reset-form").validate({
			rules: {
				txtPassword: {
					required: true,
					smallcapitalnumber: true,
				},
				c_txtPassword: {
					required: true,
					equalTo: '#txtPassword'
				},
			},
			messages: {
				txtPassword:{
					required: "Please enter password",
					smallcapitalnumber: "Password must have at least 8 characters, a lowercase letter, an uppercase letter, a number",
				},
				c_txtPassword:{
					required: "Please enter confirm password",
					equalTo: "Please enter same password.",
				},
			}
		});
		
		$("#login-form").validate({
			rules: {
				txtEmail: {
					required: true,
					email: true
				},
				txtPassword: {
					required: true
				}
				
			},
			messages: {
				txtEmail:{
					required: "Please enter email",
					email: "Please enter a valid email"
				},
				txtPassword: {
					required: "Please enter password"
				},
				
			}
		});
		

$("#add-wine-form").validate({
			rules: {
				wine_name:{
					required: true,
					minlength: 2,
					maxlength:30
				},
				wine_producer:{
					required: true,
				},
				wine_description: {
					required: true,
				},
				wine_currency: {
                    required: true,
					},
				
			},
			messages: {
				wine_name:{
					required: "Please enter wine name",
					minlength: "Please enter a least 2 characters minimum",
					maxlength: "Please enter maximum 30 characters"
				},
				wine_producer:{
					required: "Please select producer",
					
				},
				wine_description:{
					required: "Please enter description",
					
				},
				wine_currency: {
					required: "Please enter currency",
                   
				},
			}
		});
		
		
		$("#add-price-form").validate({
			rules: {
				price_type:{
					required: true,
				},
				price_wine:{
					required: true,
				},
				price_location: {
					required: true,
				},
				price_start: {
                    required: true,
					},
				price_end: {
                    required: true,
					},
				price_scheduled: {
                    required: true,
					},
				price_value: {
                    required: true,
					},
				price_currency: {
                    required: true,
					},
				
			},
			messages: {
				price_type:{
					required: "Please enter price type",
				},
				price_wine:{
					required: "Please enter wine name",
				},
				price_location: {
					required: "Please enter location",
				},
				price_start: {
                    required: "Please enter start date",
					},
				price_end: {
                    required: "Please enter end date",
					},
				price_scheduled: {
                    required: "Please enter schedule",
					},
				price_value: {
                    required: "Please enter price",
					},
				price_currency: {
                    required: "Please enter currency",
					},
			}
		});
		
		
		$("#add-location-form").validate({
			rules: {
				location_name:{
					required: true,
				},
				location_company:{
					required: true,
				},
				location_street: {
					required: true,
				},
				location_city: {
                    required: true,
					},
				location_state: {
                    required: true,
					},
				location_postal_code: {
                    required: true,
					},
				location_country: {
                    required: true,
					},
				location_phone: {
                    required: true,
					},
				location_currency: {
                    required: true,
					},
				
			},
			messages: {
				location_name:{
					required: "Please enter location",
				},
				location_company:{
					required: "Please enter company",
				},
				location_street: {
					required: "Please enter street",
				},
				location_city: {
                    required: "Please enter city",
					},
				location_state: {
                    required: "Please enter state",
					},
				location_postal_code: {
                    required: "Please enter postal code",
					},
				location_country: {
                    required: "Please enter country",
					},
				location_phone: {
                    required: "Please enter phone",
					},
				location_currency: {
                    required: "Please enter currency",
					},
			}
		});

$("#owl-demo").owlCarousel({
        items : 4,
        lazyLoad : true,
        navigation : true,
		pagination : false
	});

	$("#owl-demo1").owlCarousel({
       items : 9,
        navigation : true,
		pagination : false,
		autoPlay: false,
		itemsDesktop : [1199,4],
		itemsDesktopSmall : [980,6],
		itemsTablet: [768,4],
		 itemsMobile : [479,3],
	});





	});