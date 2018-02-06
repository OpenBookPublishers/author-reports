require('./bootstrap');

window.setCookie = function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
};

window.getCookie = function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
};

window.hideDashboardFormIfCookie = function hideDashboardFormIfCookie() {
    var dismiss = getCookie("dissmiss-info-form");
    if (!dismiss) {
        $('#info-form-container').removeClass('hidden');
    } else {
        $('#info-form-container').addClass('hidden');
    }
};

window.dismissInfoFormDay = function dismissInfoFormDay() {
    dismissInfoForm(1);
};

window.dismissInfoFormWeek = function dismissInfoFormWeek() {
    dismissInfoForm(7);
};

window.dismissInfoFormMonth = function dismissInfoFormMonth() {
    dismissInfoForm(30);
};

function dismissInfoForm(exdays) {
    setCookie("dissmiss-info-form", true, exdays);
    hideDashboardFormIfCookie();
};