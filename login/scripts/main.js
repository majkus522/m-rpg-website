let button = document.querySelector("form input[type='button']");
let loading = document.querySelector("div.loading");
let error = document.querySelector("form p");
button.addEventListener("click", () =>
{
    loading.style.display = "block";
    button.style.display = "none";
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

    let remember = document.querySelector("mrpg-checkbox.remember").value;
    let request = new XMLHttpRequest();
    request.open("GET", "../api/players/" + username + "/login", true);
    request.onload = function ()
    {
        loading.style.display = "none";
        button.style.display = "block";
        if(this.status >= 200 && this.status < 300)
        {
            let response = JSON.parse(this.responseText);
            document.cookie = "session-key=" + response.key + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            document.cookie = "session-id=" + response.id + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            document.cookie = "username=" + username + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            location.reload();
        }
        else
            error.textContent = JSON.parse(this.responseText).message;
    };
    request.setRequestHeader("Password", password);
    request.setRequestHeader("Session-Type", "website");
    request.setRequestHeader("Temp", !remember);
    request.send();
})