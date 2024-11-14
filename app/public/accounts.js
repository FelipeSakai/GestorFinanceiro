function showBankAccounts() {
    console.log('dasdasda');
    fetch('http://localhost:80/GestorFinanceiro/index.php/bank_accounts')
        .then(response => response.json())
        .then(accounts => {
            const displayArea = document.getElementById('displayArea');
            displayArea.innerHTML = '<h3>Contas Bancárias</h3>';

            accounts.forEach(account => {
                const accountDiv = document.createElement('div');
                accountDiv.classList.add('account');
                accountDiv.innerHTML = `
                    <h4>${account.nome}</h4>
                    <p>Saldo: R$ ${account.saldo}</p>
                `;
                displayArea.appendChild(accountDiv);
            });
        })
        .catch(error => console.error('Erro ao carregar contas:', error));
}
function openRegisterForm() {
    Swal.fire({
        title: 'Cadastrar Conta Bancária',
        html:
            `<input type="text" id="nome" class="swal2-input" placeholder="Nome da Conta">` +
            `<input type="number" id="saldo" class="swal2-input" placeholder="Saldo Inicial">`,
        confirmButtonText: 'Cadastrar',
        showCancelButton: true,
        preConfirm: () => {
            const nome = Swal.getPopup().querySelector('#nome').value;
            const saldo = Swal.getPopup().querySelector('#saldo').value;

            if (!nome || saldo === '') {
                Swal.showValidationMessage('Por favor, preencha todos os campos');
            }
            return { nome: nome, saldo: parseFloat(saldo) };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('http://localhost:80/GestorFinanceiro/index.php/bank_accounts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(result.value)
            })
                .then(response => response.json())
                .then(data => {
                    Swal.fire('Conta cadastrada!', '', 'success');
                    showBankAccounts();
                })
                .catch(error => {
                    console.error('Erro ao cadastrar conta:', error);
                    Swal.fire('Erro ao cadastrar conta', '', 'error');
                });
        }
    });
}

