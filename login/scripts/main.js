let button = document.querySelector("form input[type='button']");
let error = document.querySelector("form p.error");
button.addEventListener("click", () =>
{
    let username = document.querySelector("form label.username input").value;
    if(username.length == 0)
    {
        error.textContent = "Enter username";
        return;
    }
    let password = btoa(document.querySelector("form label.password input").value);
    if(password.length == 0)
    {
        error.textContent = "Enter password";
        return;
    }

    let remember = document.querySelector("form label.remember input").checked;
    let request = new XMLHttpRequest();
    request.open("GET", "../api/endpoints/players/" + username + "/logged", true);
    request.onload = function ()
    {
        if(this.status >= 400)
        {
            error.textContent = JSON.parse(this.responseText).message;
        }
        else if(this.status == 200)
        {
            document.cookie = "session=" + this.responseText + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            document.cookie = "username=" + username + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "");
            location.reload();
        }
    };
    request.setRequestHeader("Password", password);
    request.setRequestHeader("Session-Type", "website");
    request.setRequestHeader("Temp", !remember);
    request.send();
});