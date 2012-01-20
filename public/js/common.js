$(document).ready(function() {
    if (getParameterByName("error") != "" ||
        getParameterByName("warning") != "" ||
        getParameterByName("success") != "" ||
        getParameterByName("info") != "") {
        
        history.replaceState(null, document.title, getRawUrl());
    }
    
    $(".alert-message a.close").click(function() {
        $(".alert-message").fadeOut();
    });
});

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
