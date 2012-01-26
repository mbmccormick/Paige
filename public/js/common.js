$(document).ready(function() {
    if (getParameterByName("error") != "" ||
        getParameterByName("warning") != "" ||
        getParameterByName("success") != "" ||
        getParameterByName("info") != "") {
        
        history.replaceState(null, document.title, getRawUrl());
    }

    $(".phone").mask("999-999-9999");
    
    $(".alert-message").delay(5000).fadeOut();
	
    $(".alert-message a.close").click(function() {
        $(".alert-message").fadeOut();
    });

    $("form.form-stacked").submit(function() {
        var formData = $("form.form-stacked").serializeArray();
        for (var i=0; i < formData.length; i++) { 
            if (formData[i].className.contains("exclude")) continue;

            if (!formData[i].value) { 
                alert("Please complete all fields, check your input, and try again.")                
                return false;
            }

            if (formData[i].className.contains("email")) { 
                if (!validateEmail(formData[i].value)) {}
                    alert("Please enter a valid email address and try again.")                
                    return false;
                }
            }
        }
    });
});

function validateEmail($email)
{
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if(!emailReg.test($email)) {
        return false;
    }
    else {
        return true;
    }
}


function getRawUrl()
{
    var regexS = "^(.*)&(?:.*)$";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.href);
    if(results == null)
        return window.location.href;
    else
        return results[1];
}

function getParameterByName(name)
{
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.href);
    if(results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}
