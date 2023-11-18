class MrgpBar extends HTMLElement
{
    constructor()
    {
        super();
        this.style = "display: block; width: 100%";
        const shadow = this.attachShadow({mode: "closed"});
        let style = document.createElement("style");
        style.innerHTML = "div { height: 25px; background-color: #343434; padding: 3px; box-sizing: border-box; } div > div { height: 100%; background-color: #898989; }";
        shadow.append(style);
        let background = document.createElement("div");
        let fill = document.createElement("div");
        fill.style.width = this.textContent + "%";
        background.append(fill);
        shadow.append(background);
    }
}

customElements.define("mrpg-bar", MrgpBar);