let error = document.querySelector("form p");
let username;
let password;
let remember;
document.querySelector("form input[type='button']").addEventListener("click", () =>
{
    let passwordField = document.querySelectorAll("form label.password input");
    if(passwordField[0].value != passwordField[1].value)
        return;
    username = document.querySelector("form label.username input").value;
    let email = document.querySelector("form input[type='email']").value;
    password = btoa(passwordField[0].value);
    remember = document.querySelector("form label.remember input").value;
    let request = new XMLHttpRequest();
    request.open("POST", "../api/endpoints/players", true);
    request.onload = function ()
    {
        if(this.status == 201)
        {
            error.textContent = "";
            login();
        }
        else
        {
            error.textContent = JSON.parse(this.responseText).message;
        }
        console.log(this.responseText)
    }
    request.send(JSON.stringify({
        username: username,
        email: email,
        password: password
    }));
});

async function login()
{
    let request = new XMLHttpRequest();
    request.open("GET", "../api/endpoints/players/" + username + "/logged", true);
    request.onload = function ()
    {
        if(this.status == 200)
        {
            error.textContent = "";
            document.cookie = "session=" + this.responseText + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            document.cookie = "username=" + username + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "");
            location.replace("../players/" + username + "");
        }
        else
        {
            error.textContent = JSON.parse(this.responseText).message;
        }
    }
    request.setRequestHeader("Session-Type", "website");
    request.setRequestHeader("Temp", remember);
    request.setRequestHeader("Password", password);
    request.send();
}