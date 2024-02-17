let error = document.querySelector("form p.error");

document.querySelector('main form input[type="button"]').addEventListener("click", () =>
{
    let title = document.querySelector('form input[type="text"]').value;
    let content = document.querySelector('form textarea').value;
    if(title.length == 0)
    {
        error.textContent = "Enter title";
        return;
    }
    if(content.length == 0)
    {
        error.textContent = "Enter content";
        return;
    }
    let request = new XMLHttpRequest();
    request.open("post", "../api/forum", true);
    request.onload = function ()
    {
        if(this.status < 300)
            location.href = location.href + this.responseText;
        else
            error.textContent = JSON.parse(this.responseText).message;
    };
    request.setRequestHeader("Session-ID", getCookie("session-id"));
    request.setRequestHeader("Session-Key", getCookie("session-key"));
    request.send(JSON.stringify({ title: title, text: content}));
});