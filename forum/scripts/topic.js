let commenter = document.createElement("div");
commenter.classList.add("commenter");
commenter.appendChild(document.createElement("textarea"));
let button = document.createElement("button");
button.textContent = "Post";
button.addEventListener("click", (event) =>
{
    let request = new XMLHttpRequest();
    request.open("POST", "../api/forum", true);
    request.onload = function ()
    {
        location.reload();
    }
    request.setRequestHeader("Session-ID", getCookie("session-id"));
    request.setRequestHeader("Session-Key", getCookie("session-key"));
    request.send(JSON.stringify({ text: event.target.parentElement.querySelector("textarea").value, master: getId(event.target)}));
});
commenter.appendChild(button);

let buttons = document.querySelectorAll("div.main button");
buttons[0].addEventListener("click", like);
buttons[1].addEventListener("click", showCommenter);

let request = new XMLHttpRequest();
request.open("GET", "../api/forum/" + slug, true);
request.onload = async function ()
{
    if(this.status < 300)
    {
        (JSON.parse(this.responseText)).forEach(element =>
        {
            document.querySelector('div.main[data-id="' + element.master + '"]').appendChild(createComment(element));
        });
    }
}
request.setRequestHeader("Session-ID", getCookie("session-id"));
request.setRequestHeader("Session-Key", getCookie("session-key"));
request.setRequestHeader("Result-Offset", 1);
request.send();

function getId(element)
{
    return element.parentElement.parentElement.dataset.id;
}

function createComment(data)
{
    let main = document.createElement("div");
    main.classList.add("main");
    main.dataset.id = data.id;
    let author = document.createElement("div");
    author.classList.add("author");
    author.textContent = data.player;
    main.appendChild(author);
    let content = document.createElement("div");
    content.classList.add("content");
    let p = document.createElement("p");
    p.textContent = data.text;
    content.appendChild(p);
    let time = document.createElement("div");
    time.textContent = data.time;
    content.appendChild(time);
    let button = document.createElement("button");
    button.addEventListener("click", like);
    let icon = document.createElement("ion-icon");
    icon.name = data.liked ? "thumbs-up" : "thumbs-up-outline";
    button.appendChild(icon);
    p = document.createElement("p");
    p.textContent = data.likes;
    button.appendChild(p);
    content.appendChild(button);
    button = document.createElement("button");
    button.addEventListener("click", showCommenter);
    button.textContent = "Comment";
    icon = document.createElement("ion-icon");
    icon.name = "chatbox-ellipses-outline";
    button.appendChild(icon);
    content.appendChild(button);
    if(data.player == getCookie("username"))
    {
        button = document.createElement("button");
        button.textContent = "Delete";
        icon = document.createElement("ion-icon");
        icon.name = "trash-outline";
        button.appendChild(icon);
        button.addEventListener("click", (event) =>
        {
            let request = new XMLHttpRequest();
            request.open("delete", "../api/forum/" + data.id, true);
            request.onload = function ()
            {
                if(this.status < 300)
                    location.reload();
            }
            request.setRequestHeader("Session-ID", getCookie("session-id"));
            request.setRequestHeader("Session-Key", getCookie("session-key"));
            request.send();
        });
        content.appendChild(button);
    }
    main.appendChild(content);
    return main;
}

function like(event)
{
    let icon = event.target.querySelector("ion-icon");
    let liked = icon.name == "thumbs-up";
    let request = new XMLHttpRequest();
    request.open("PATCH", "../api/forum/" + getId(event.target), true);
    request.onload = function ()
    {
        if(this.status < 300)
        {
            icon.name = (!liked) ? "thumbs-up" : "thumbs-up-outline";
            let count = event.target.querySelector("p");
            count.textContent = (Number(count.textContent) + ((!liked) ? 1 : -1));
        }
    }
    request.setRequestHeader("Session-ID", getCookie("session-id"));
    request.setRequestHeader("Session-Key", getCookie("session-key"));
    request.send(JSON.stringify({ like: !liked}));
}

function showCommenter(event)
{
    event.target.parentElement.parentElement.insertBefore(commenter, event.target.parentElement.parentElement.children[2]);
}