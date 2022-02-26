var handler;
//getting the forms

//product page form
var prod_form = document.querySelector ("div.row.wrapper form[name='form-prod']"); 

//brand page form
var brand_form = document.querySelector ("div.container form[name='brand_form']");

//category page form
var cat_form = document.querySelector ("div.container form[name='cat_form']");

//user page form
var user_form = document.querySelector ("div.container form[name='user_form']");

//data getters
function prod_data_getter ()
{
    //getting value of field
    var prod_name = document.forms["form-prod"]["prod_name"].value;
    var price = document.forms["form-prod"]["unit_price"].value;
    var brand_option = document.forms["form-prod"]["brand"].value; 
    var cat_option = document.forms["form-prod"]["category"].value;
    return {
        'name': prod_name,
        'price': price,
        'category': cat_option,
        'brand': brand_option,
        };
}

function brand_data_getter ()
{
    //getting value of field
    var brand_form_field = document.forms["brand_form"]["new_brand"].value;

    return {
        'brand name': brand_form_field
        };
}

function cat_data_getter ()
{
    //getting value of field
    var cat_form_field = document.forms["cat_form"]["new_cat"].value;
    
    return {
        'category name': cat_form_field
        }; 
}

function user_data_getter ()
{
    //getting value of field
    var user_phone = document.forms["user_form"]["phone"].value;
    var user_email = document.forms["user_form"]["email"].value;

    return {
            'phone': user_phone,
            'email': user_email,
            }
}

if (user_form)
{
    //setting handler while passing args through data
    user_form.addEventListener ("submit", handler = function (e)
                                            {
                                                var data = {
                                                            'event': e, 
                                                            'args': user_data_getter (),
                                                            };
                                                form_validation (data);
                                            }
                                )
}

if (prod_form)
{

    //setting handler while passing args through data
    prod_form.addEventListener ("submit", handler = function (e)
                                            {
                                                var data = {
                                                            'event': e, 
                                                            'args': prod_data_getter (),
                                                            };
                                                form_validation (data);
                                            }
                                )
}

if (brand_form)
{   
   
    //setting handler while passing args through data
    /*brand_form.addEventListener ("submit", handler = function (e)
                                            {
                                                var data = {
                                                            'event': e, 
                                                            'args': brand_data_getter (),
                                                            };
                                                form_validation (data);
                                            }
                                );*/

    $(brand_form).on("submit",  function (e)
                                {
                                    var data = {
                                                'event': e, 
                                                'args': brand_data_getter (),
                                                };
                                    form_validation (data);
                                }
                        );

                                     
}
if (cat_form)
{

    //setting handler while passing args through data
    cat_form.addEventListener ("submit", handler = function (e)
                                            {
                                                var data = {
                                                            'event': e, 
                                                            'args': cat_data_getter (),
                                                            };
                                                form_validation (data);
                                            }
                                );
}


function form_validation (data)
{
    //checking if the field value is empty
    //version ECMAScript 2017 (loop through dictionnary)    
    var empty_fields = "";
    for ([field, value] of Object.entries(data['args']))
    {   
        //if field is empty get its name
        if (value == "")
        {
            empty_fields += field + ", ";        
        }
    } 

    if (empty_fields!="")
    {
        //print empty fields
        alert (empty_fields + "must be filled out.");
        //preventing the normal behavior submiting the form
        data['event'].preventDefault();   
    }

    //check if the price is valid in product form
    if ("price" in data['args'])//check if data contains price
    {
        //patterns
        var is_num_float = /\d+(.\d+)?$/g;
        if (!data['args']['price'].match(is_num_float) && data['args']['price']!="")
        {
            alert ("Price should be numrical.");
            //preventing the normal behavior submiting the form
            data['event'].preventDefault();
        }
    }
    
    //check if phone number is morrocan
    if ('phone' in data['args'])//check if data contains price
    {
        if (data['args']['phone'][0] != 0 ||
            data['args']['phone'][1] != 6   
            )
        {
            alert ("Choose a morrocan number. Example: 06########");
            //preventing the normal behavior submiting the form
            data['event'].preventDefault();
        }
        //matching exactly 0 digits 
        //pattern
        var is_phone = /^[0-9]{10}$/;
        if (!data['args']['phone'].match(is_phone))
        {
            alert ("Choose valid number 10 digits.");
            //preventing the normal behavior submiting the form
            data['event'].preventDefault();
        }

        
        var email_regex = /^[a-zA-Z0-9.]+@yahoo.[a-z]{2,3}/;
        if (!data['args']['email'].match(email_regex))
        {
            alert ("please choose a valid 'yahoo' email address.");
            //preventing the normal behavior submiting the form
            data['event'].preventDefault();
        }
    }


}
