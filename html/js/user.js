const Save = document.getElementById("salvar");
async function Insert() {
    try {
        const form = document.getElementById("form");
        const formData = new FormData(form);
        const options = {
            method: "POST",
            body: formData,
        };
        const response = await fetch('/usuario/insert', options);
        return await response.json();
    } catch (error) {
        throw new Error(error.message);
    }
}

async function Deletar(id) {
    const form = document.getElementById("form");
    const formData = new FormData(form);
    const options = {
        method: 'POST',
        body: formData,
    }
    const response = await fetch('/usuario/deletar', options);
    return await response.json();
}



Save.addEventListener("click", async () => {
    const response = await Insert();

    if (response.status) {
        await ControlAlert.SetId('mensagem').Primary("Salvando os Dados...", 2000);
        await ControlAlert.SetId('mensagem').Sucess('Cadastro realizado!', 1000);
        ControlAlert.IsRedirect('/usuario/lista', 2000);
    } else {
        ControlAlert.SetId('mensagem').danger(response.msg)
    }
});
