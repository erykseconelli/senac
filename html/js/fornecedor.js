const Save = document.getElementById("salvar");

async function Insert() {
try {
        const form = document.getElementById("form");
        const formData = new FormData (form);
        const options = {
            method: "POST",
            body: formData,
        };
        const response = await fetch('/fornecedor/insert', options);
        return await response.json();
    } catch (error) {
        throw new Error(error.message);
    }
}

Save.addEventListener("click", async () => {
    const response = await Insert ();

    if (response.status) {
        await ControlAlert.SetId('mensagem').Primary("Salvando os Dados...", 2000);
        await ControlAlert.SetId('mensagem').Sucess('Cadastro realizado!', 1000);
        ControlAlert.IsRedirect('/fornecedor/lista', 2000);
    } else {
        ControlAlert.SetId('mensagem').danger(response.msg)
    }
});