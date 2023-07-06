let inspector = document.querySelector("content inspector");
document.querySelectorAll("content skill").forEach(element => element.addEventListener("click", async (event) =>
{
    let target = event.target;
    if(target.tagName == "IMG")
        target = target.parentElement;
    let data = await (await fetch("../api/data/skills/" + target.dataset.skill + ".json")).json();
    inspector.querySelector("img").src = "../img/skills/" + target.dataset.skill + ".png";
    inspector.querySelector("h2").textContent = data.label;
    inspector.querySelector("p").textContent = data.description;
    inspector.style.display = "flex";
}));