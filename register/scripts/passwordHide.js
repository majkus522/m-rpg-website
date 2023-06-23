let show = false;
let fields = document.querySelectorAll("form label.password input");
document.querySelector("form label.show input").addEventListener("click", () =>
{
    show = !show;
    let value;
    if(show)
        value = "text";
    else
        value = "password";
    fields.forEach((element) =>
    {
        element.type = value;
    });
});