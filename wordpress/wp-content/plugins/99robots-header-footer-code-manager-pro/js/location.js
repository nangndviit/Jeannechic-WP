// simple redirect
if ('undefined' == typeof hfcm_pro_location) {
    var hfcm_pro_location = {url: ''};
}
window.location.replace(hfcm_pro_location.url);