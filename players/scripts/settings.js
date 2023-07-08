let email = document.querySelector("main div.email");
email.querySelector("input[type='button']").addEventListener("click", () =>
{
    send({ email: email.querySelector("input[type='email']").value}, function ()
    {
        if(this.status == 200 || this.status == 401)
            location.reload();
        else
            email.querySelector("p").textContent = JSON.parse(this.responseText).message;
    });
});

let password = document.querySelector("main div.password");
password.querySelector("input[type='button']").addEventListener("click", () =>
{
    let newPassword = btoa(password.querySelector("input[type='text'], input[type='password']").value);
    send({ password: newPassword }, function ()
    {
        if(this.status == 200 || this.status == 401)
            location.reload();
        else
            password.querySelector("p").textContent = JSON.parse(this.responseText).message;
    });
});

let del = document.querySelector("main div.delete");
del.querySelector("input[type='button']").addEventListener("click", () =>
{
    if(confirm("Are you sure you want to delete your account ??"))
    {
        send({}, function ()
        {
            if(this.status == 200)
                location.replace("../logout");
            else if(this.status == 401)
                location.reload();
            else
                del.querySelector("p").textContent = JSON.parse(this.responseText).message;
        }, "DELETE");
    }
});

function send(body, onload, method = "PATCH")
{
    let request = new XMLHttpRequest();
    request.open(method, "../api/endpoints/players/" + getCookie("username"), true);
    request.onload = onload;
    request.setRequestHeader("Session-Key", getCookie("session"));
    request.setRequestHeader("Session-Type", "website");
    request.send(JSON.stringify(body));
}

function getCookie(name)
{
    let part = document.cookie.split("; ");
    let placeholder = "";
    part.forEach(element => 
    {
        if(element.startsWith(name))
        {
            placeholder = element.replace(name + "=", "");
            return;
        }
    });
    return placeholder;
}