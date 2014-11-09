function Validate()
{
    if (document.registration_form.first_name.value == '') 
    {
        alert('Please fill in the first name!');
        return false;
    }
	    if (document.registration_form.last_name.value == '') 
    {
        alert('Please fill in the last name!');
        return false;
    }
	    if (document.registration_form.volunteer_number.value == '') 
    {
        alert('Please fill in the volunteer number!');
        return false;
    }
    if (document.registration_form.email.value == '') 
    {
       alert('Please fill in the email address!');
       return false;
    }
	    if (document.registration_form.phone_number.value == '') 
    {
       alert('Please fill in the phone number!');
       return false;
    }
    if (document.registration_form.username.value == '') 
    {
        alert('Please fill in the username!');
        return false;
    }
    if (document.registration_form.password.value == '') 
    {
       alert('Please fill in the password!');
      return false;
    }
    if (document.registration_form.password_confirmation.value == '') 
    {
       alert('Please fill in the password again for confirmation!');
      return false;
    }
    if (document.registration_form.password.value != 
    document.registration_form.password_confirmation.value) 
    {
        alert("The passwords do not match! Please try again.");
        return false;
    }
    return true;
}