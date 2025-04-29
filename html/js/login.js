const Login = document.getElementById('btn_login');

async function authenticate() {
    try {
        const form = document.getElementById('form');
        if (!form) {
            alert('Formulário não encontrado!');
            return;
        }

        const formData = new FormData(form);
        const options = {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        };
        const response = await fetch('/login/authenticate', options);
        const json = await response.json();
        console.log(json);
        if (!json.status) {
            alert(json.msg);
            return;
        }
        // Redireciona para home após login
        window.location.href = '/';
    } catch (error) {
        console.error('Erro no login:', error);
        alert('Erro ao tentar logar. Tente novamente.');
    }
}

Login.addEventListener('click', async () => {
    await authenticate();
});
