$(function(){
    $('body').on('click', '.remove-block', function(e){
        e.preventDefault();
        $(this).closest('.removable-block').remove();
    });
});

function getRandomString(length = 32) {
    let str = '';
    let chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for (let i = 0; i < length; i++) { 
        let char = Math.floor(Math.random() * chars.length + 1); 
        str += chars.charAt(char) 
    }
    return str; 
}

function initSelects()
{
    $('select.select2').select2({width: '100%'});
}
