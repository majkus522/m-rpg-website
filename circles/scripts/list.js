let main = document.querySelector("body > main");
main.textContent = "";
let request = new XMLHttpRequest();
request.open("GET", "../api/controllers/circles-panel", true);
request.onload = async function ()
{
    JSON.parse(this.responseText).forEach(element =>
    {
        createPanel(element);
    });
}
request.send();

async function createPanel(data)
{
    let panel = document.createElement("a");
    panel.href = data.slug;

    let svg = document.createElement("svg");
    panel.appendChild(svg);

    let desc = document.createElement("desc");

    let icon = document.createElement("ion-icon");
    icon.name = "thumbs-up-outline";
    icon.addEventListener("click", async function (event)
    {
        console.log("fdgdfgdf");
        event.stopPropagation();
        event.preventDefault();
    });
    desc.appendChild(icon);

    let likes = document.createElement("likes");
    likes.textContent = data.likes;
    desc.appendChild(likes);

    let div = document.createElement("div");
    let name = document.createElement("p");
    if(data.name.length > 19)
    {
        name.classList.add("scrolling");
        name.style.setProperty("--length", data.name.length);
    }
    name.textContent = data.name;
    div.appendChild(name);
    desc.appendChild(div);

    panel.appendChild(desc);
    main.appendChild(panel);
}