$(document).ready(function() {
    if (getParameterByName("error") != "" ||
        getParameterByName("warning") != "" ||
        getParameterByName("success") != "" ||
        getParameterByName("info") != "") {
        
        history.replaceState(null, document.title, getRawUrl());
    }

    $(".alert").delay(5000).fadeOut();

    $("form.form-vertical").submit(function() {
        var formData = [];
        $("form.form-vertical input").each(function() { formData.push(this); });

        var hasError = false;
        var hasEmailError = false;
        for (var i=0; i < formData.length; i++) { 
            if (hasClass(formData[i], "exclude")) { 
                continue;
            }

            $(formData[i]).parent().parent().removeClass("error");
            
            if (!formData[i].value) { 
                hasError = true;
                $(formData[i]).parent().parent().addClass("error");
            }

            if (hasClass(formData[i], "email")) { 
                if (!validateEmail(formData[i].value)) {
                    hasEmailError = true;
                    $(formData[i]).parent().parent().addClass("error");
                }
            }
        }

        if (hasError == true) {
            alert("Please complete all fields, check your input, and try again.")                
            return false;
        }

        if (hasEmailError == true) {
            alert("Please enter a valid email address and try again.")                
            return false;
        }
    });
});

function validateEmail(email)
{
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if(!emailReg.test(email)) {
        return false;
    }
    else {
        return true;
    }
}

function hasClass(el, class_to_match)
{
    var c;
    if (el && el.className && typeof class_to_match === "string") {
        c = el.getAttribute("class");
        c = " "+ c + " ";
        return c.indexOf(" " + class_to_match + " ") > -1;
    } 
    else {
        return false;
    }
}

function getRawUrl()
{
    var url_parts = window.location.href.split('?');
    var url = url_parts[0];

    var regexS = "^(.*)&(?:.*)$";
    var regex = new RegExp(regexS);
    var results = regex.exec(url);
    if(results == null)
        return url;
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
