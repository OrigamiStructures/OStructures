$(document).ready(function(){
    $(document).foundation();
    $('.resource-zone').on('toggled', function (event, accordion) {
        console.log(accordion);
    });
})