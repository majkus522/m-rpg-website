let email = document.querySelector("main div.email");
email.querySelector("input[type='button']").addEventListener("click", () =>
{
    email.querySelector("div.loading").style.display = "block";
    email.querySelector("input[type='button']").style.display = "none";
    send({ email: email.querySelector("input[type='email']").value}, function ()
    {
        email.querySelector("div.loading").style.display = "none";
        email.querySelector("input[type='button']").style.display = "block";
        if((this.status >= 200 && this.status < 300) || this.status == 401)
            location.reload();
        else
            email.querySelector("p").textContent = JSON.parse(this.responseText).message;
    });
});

let password = document.querySelector("main div.password");
password.querySelector("input[type='button']").addEventListener("click", () =>
{
    password.querySelector("div.loading").style.display = "block";
    password.querySelector("input[type='button']").style.display = "none";
    let newPassword = btoa(password.querySelector("input[type='text'], input[type='password']").value);
    send({ password: newPassword }, function ()
    {
        password.querySelector("div.loading").style.display = "none";
        password.querySelector("input[type='button']").style.display = "block";
        if((this.status >= 200 && this.status < 300) || this.status == 401)
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
        del.querySelector("div.loading").style.display = "block";
        del.querySelector("input[type='button']").style.display = "none";
        send({}, function ()
        {
            del.querySelector("div.loading").style.display = "none";
            del.querySelector("input[type='button']").style.display = "block";
            if(this.status >= 200 && this.status < 300)
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
    request.open(method, "../api/players/" + getCookie("username"), true);
    request.onload = onload;
    request.setRequestHeader("Session-Key", getCookie("session"));
    request.setRequestHeader("Session-Type", "website");
    request.send(JSON.stringify(body));
}