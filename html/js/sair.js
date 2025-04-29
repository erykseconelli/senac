const Sair = document.getElementById("btn_menu_sair");

Sair.addEventListener('click', async () => {
    try {
        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            }
        });

        const json = await response.json();
        if (json.status) {
            // Redireciona para a tela de login após logout
            window.location.href = '/login';
        } else {
            alert(json.msg || 'Erro ao tentar sair.');
        }
    } catch (error) {
        console.error('Erro no logout:', error);
        alert('Erro ao tentar sair. Tente novamente.');
    }
});
