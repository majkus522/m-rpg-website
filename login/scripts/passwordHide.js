let show = false;
let field = document.querySelector("form label.password input");
document.querySelector("form label.show input").addEventListener("click", () =>
{
    show = !show;
    if(show)
        field.type = "text";
    else
        field.type = "password";
});