class MrpgCheckbox extends HTMLElement
{
    value = false;

    constructor()
    {
        super();
        const shadow = this.attachShadow({mode: "closed"});
        let main = document.createElement("div");
        main.style = "display: flex; align-items: center; gap: " + this.getStyle("--gap");
        main.textContent = this.textContent;
        let border = document.createElement("div");
        border.style = "border: " + this.getStyle("--border") + "; background-color: transparent; width: " + this.getStyle("--size") + "; height: " + this.getStyle("--size") + "; display: flex; justify-content: center; align-items: center";
        main.insertBefore(border, main.firstChild);
        let fill = document.createElement("div");
        fill.style = "background-color: " + this.getStyle("--fill-color") + "; width: " + this.getStyle("--fill-size") + "; height: " + this.getStyle("--fill-size") + "; display: none";
        border.append(fill);
        shadow.append(main);
        this.addEventListener("click", () =>
        {
            this.value = !this.value;
            fill.style.display = this.value ? "block" : "none";
        }, true);
    }

    getStyle(property)
    {
        let value = getComputedStyle(this).getPropertyValue(property);
        if(value.trim().length != 0)
            return value;
        switch(property)
        {
            case "--border":
                return "2px solid white";

            case "--size":
                return "25px";

            case "--fill-color":
                return "white";

            case "--fill-size":
                return "65%";

            case "--gap":
                return "10px";
        }
    }
}

customElements.define("mrpg-checkbox", MrpgCheckbox);