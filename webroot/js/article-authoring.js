$(document).ready(function(){
    $(document).foundation();
    $('.resource-zone').on('toggled', function (event, accordion) {
        console.log(accordion);
    });
    $('a[href="#panel1d"]').trigger('click');
    $('a[href="#panel1d"]').trigger('click');
});