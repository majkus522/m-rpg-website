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
    request.open("POST", "../api/players.php", true);
    request.onload = function ()
    {
        if(this.status >= 200 && this.status < 300)
        {
            login();
        }
        else
        {
            error.textContent = this.responseText;
        }
        console.log(this.responseText)
    }
    request.send(JSON.stringify({
        username: username,
        email: email,
        password: password,
        remember: remember
    }));
});

async function login()
{
    let request = new XMLHttpRequest();
    let url = "../api/login.php?player=" + username;
    if(!remember)
        url += "&temp";
    request.open("GET", url, true);
    request.onload = function ()
    {
        if(this.status >= 200 && this.status < 300)
        {
            document.cookie = "session=" + this.responseText + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            document.cookie = "username=" + username + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "");
            location.replace("../");
        }
        else
        {
            error.textContent = this.responseText;
        }
    };
    request.setRequestHeader("Password", password);
    request.send();
}