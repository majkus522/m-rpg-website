let email = document.querySelector("main div.email");
email.querySelector("input[type='button']").addEventListener("click", () =>
{
    if(email.querySelector("input").value == email.querySelector("input").dataset.old)
        return;
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
    if(password.querySelector("input").value.length < 6)
    {
        password.querySelector("p").textContent = "Password must be at least 6 characters long";
        return;
    }
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

let dialog = document.querySelector("main dialog");
dialog.close();
let del = document.querySelector("main div.delete");
del.querySelector("input[type='button']").addEventListener("click", () =>
{
    dialog.showModal();
    dialog.style.display = "flex";
});
let buttons = dialog.querySelectorAll("button");
buttons[0].addEventListener("click", () =>
{
    del.querySelector("div.loading").style.display = "block";
    del.querySelector("input[type='button']").style.display = "none";
    send({}, function ()
    {
        del.querySelector("div.loading").style.display = "none";
        del.querySelector("input[type='button']").style.display = "block";
        dialog.close();
        dialog.style.display = "none";
        if(this.status < 300)
            location.reload();
        else
            del.querySelector("p").textContent = JSON.parse(this.responseText).message;
    }, "DELETE", btoa(dialog.querySelector("input").value));
});
buttons[1].addEventListener("click", () =>
{
    dialog.close();
    dialog.style.display = "none";
});

function send(body, onload, method = "PATCH", password = "")
{
    let request = new XMLHttpRequest();
    request.open(method, "../api/players/" + getCookie("username"), true);
    request.onload = onload;
    request.setRequestHeader("Session-ID", getCookie("session-id"));
    request.setRequestHeader("Session-Key", getCookie("session-key"));
    if(password.length > 0)
        request.setRequestHeader("Password", password);
    request.send(JSON.stringify(body));
}