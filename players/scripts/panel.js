let parent = document.querySelector("info > div");
parent.style.gridTemplateColumns = "1fr " + parent.offsetHeight + "px";

let items = document.querySelectorAll("items img");
items.forEach(element =>
{
    if(element.src == "")
        return;
    element.addEventListener("click", async (event) =>
    {
        let dialog = document.createElement("div");
        document.querySelector("items").appendChild(dialog);
        let rect = event.target.getBoundingClientRect();
        dialog.style.top = (rect.top + 40) + "px";
        dialog.style.left = (rect.left + 80) + "px";
        let part = event.target.src.split("/");
        let equipment = (await (await fetch("../api/data/equipment/" + part[part.length - 1].split(".")[0] + ".json")).json());
        let p = document.createElement("p");
        p.textContent = equipment.label;
        dialog.appendChild(p);
        Object.entries(equipment.bonusStats).forEach((pair) => 
        {
            let p = document.createElement("p");
            p.textContent = statsData.filter(element => element.short == pair[0])[0].label + ": " + pair[1];
            dialog.appendChild(p);
        });
    });
});

document.addEventListener("click", () => document.querySelectorAll("items div").forEach(element => element.remove()), true);