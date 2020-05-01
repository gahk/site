function fetchForm(selected) {
    debugger;
    //scale
    $('.form-wrapper').transition('fly left');
    $(".form-wrapper").load("/optagelse/" + selected, function (response) {
        //drop
        $('.form-wrapper').transition('fly right');
    });
    setTimeout(function () {
        console.log("Hej");
        //400
    }, 300);
}

$(document).ready(function () {
    $('.ui.checkbox').checkbox();
    $('.ui.search.dropdown').dropdown({
            allowAdditions: true
    });
    
    $('.ui.dropdown').dropdown();
    $('#kort-motivasjon').popup({
        inline: true,
        on: 'focus',
        position: 'top right',
        delay: {
            show: 2200,
            hide: 200
        }
    });
    
});

//Endrer popup dynamisk når bruker skriver inn shit
$("#kort-motivasjon").on("change paste keyup", function () {
    var textBox = $("#kort-motivasjon");
    var currentText = textBox.val();
    //Hardcoded, but whatcha gotta do
    var remaining = 500 - currentText.length;
    $(".ui.popup.top.right").find('.content').text(remaining + " tegn tilbage");
});

//Send inn form
function sendInn() {
    //Spiller en liten animasjon dersom formen ikke er ok
    $('.form-wrapper').transition('shake');

}