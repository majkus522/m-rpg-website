document.querySelector("input[type='button']").addEventListener("click", () =>
{
    let inputs = document.querySelectorAll("form > label > input");
    let username = inputs[0].value;
    let password = inputs[1].value;
});