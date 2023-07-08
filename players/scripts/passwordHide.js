let show = false;
let field = document.querySelector("main div.password input:not([type='button'])");
document.querySelector("main div.password label.show input").addEventListener("click", () =>
{
    show = !show;
    if(show)
        field.type = "text";
    else
        field.type = "password";
});