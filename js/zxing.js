var gettingZXing = false;
if(window.location.hash.substr(1,2) == "zx"){
    var bc = window.location.hash.substr(3);
    bc = unescape(bc);
    localStorage["barcode"] = bc;
    window.close();
    self.close();
    window.location.href="about:blank";//In case self.close isn't allowed
}
function getZxing(){
    gettingZXing = true;
    var href = window.location.href;
    var ptr = href.lastIndexOf("#");
    if (ptr > 0) {
        href = href.substr(0, ptr);
    }
    //setTimeout(function(){gettingZXing = false}, 20000);// in case you cancel, or an error occurs.
    window.location.href = ("zxing://scan/?ret=" + escape(href + "#zx{CODE}"));

    //window won't actually be replaced. This just prevents both the browser AND zxing from opening a new window.'
}
window.addEventListener("hashchanged", function(ev){
    var hash = window.location.hash.substr(1);
    if (hash != "") {
        window.location.hash = "";
        processBarcode(unescape(hash));
    }   
}, false);

window.addEventListener("storage", function(event){
    if(gettingZXing == true){
        gettingZXing = false;
        localStorage["barcode"] = "";
        if(event.url.split("\#")[0] == window.location.href){
            window.focus();
            processBarcode(unescape(event.newValue));
        }
    }
}, false);