class ControlAlert {
    static id = ''; 
    static element;

    static SetId(id = '') {
        this.id = id;
        this.element = document.getElementById(this.id);
        if (!this.element) {
            throw new Error(`Elemento alert não foi encontrado.`);
        }
        return this;
    }

    // Exibe uma mensagem primária e aguarda o tempo especificado
    static Primary(message = "Salvando os Dados", time = 5000) {
        this.element.className = 'alert alert-primary mt-3';
        this.element.innerHTML = message;
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve();
            }, time);
        });
    }

    // Exibe uma mensagem de sucesso e aguarda o tempo especificado
    static Sucess(message = "Cadastro realizado", time = 5000) {
        this.element.className = 'alert alert-success mt-3';
        this.element.innerHTML = message;
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve();
            }, time);
        });
    }

    // Exibe uma mensagem de erro
    static danger(message = '') {
        this.element.className = 'alert alert-danger mt-3';
        this.element.innerHTML = message;
        return this;
    }

    // Redireciona para a URL após o tempo especificado
    static IsRedirect(url = '', time = 3000) {
        if (url === '') {
            throw new Error("Por favor, informe o local para redirecionar");
        }
        return new Promise((resolve) => {
            setTimeout(() => {
                window.location.href = url;
                resolve();  // Resolve a Promise após o tempo de redirecionamento
            }, time);
        });
    }
}