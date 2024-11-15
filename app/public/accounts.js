function showBankAccounts() {
    fetch('http://localhost:8000/bank_accounts')
        .then(response => response.json())
        .then(accounts => {
            const accountList = document.getElementById('accountList');
            accountList.innerHTML = '';

            accounts.forEach(account => {
                const accountDiv = document.createElement('div');
                accountDiv.classList.add('account-item');
                accountDiv.innerText = account.nome;
                accountDiv.onclick = () => {
                    showAccountDetails(account.id);
                    showTransactionDetails(accountId);
                }
                accountList.appendChild(accountDiv);
            });
        })
        .catch(error => console.error('Erro ao carregar contas:', error));
}

function showAccountDetails(accountId) {
    fetch(`http://localhost:8000/bank_accounts/${accountId}`)
        .then(response => response.json())
        .then(data => {
            console.log(data)
            const displayArea = document.getElementById('displayArea');
            displayArea.innerHTML = `
                <h3>Conta: ${data.nome}</h3>
                <p>Saldo Atual: R$ ${data.saldo}</p>
                <button onclick="addTransaction(${accountId}, true)">Adicionar Saldo</button>
                <button onclick="addTransaction(${accountId}, false)">Remover Saldo</button>
                <div class="transaction-history" id="transactionHistory">
                    <h4>Histórico de Transações</h4>
                </div>
            `;
        })
        .catch(error => console.error('Erro ao carregar detalhes da conta:', error));
}

function showTransactionDetails(accountId) {
    fetch(`http://localhost:8000/bank_accounts/transaction${accountId}`)
        .then(response => response.json())
        .then(data => {
            const transactionHistory = document.getElementById('transactionHistory');
            data.forEach(transaction => {
                const transactionDiv = document.createElement('div');
                transactionDiv.classList.add('transaction-item');
                transactionDiv.classList.add(transaction.value > 0 ? 'positive' : 'negative');
                transactionDiv.innerHTML = `
                <span>${transaction.date}</span>
                <span>${transaction.value > 0 ? '+' : ''}${transaction.value}</span>
            `;
                transactionHistory.appendChild(transactionDiv);
            });
        })
        .catch(error => console.error('Erro ao carregar detalhes da conta:', error));

}

function addTransaction(accountId, isAddition) {
    Swal.fire({
        title: isAddition ? 'Adicionar Saldo' : 'Remover Saldo',
        input: 'number',
        inputPlaceholder: 'Digite o valor',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        showLoaderOnConfirm: true,
        preConfirm: (value) => {
            const transactionValue = isAddition ? parseFloat(value) : -parseFloat(value);

            return fetch(`http://localhost:8000/bank_accounts/transaction/${accountId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ value: transactionValue })
            })
                .then(response => {
                    if (!response.ok) throw new Error('Erro ao processar transação');
                    return response.json();
                });
        },
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Transação realizada com sucesso!', '', 'success');
            showAccountDetails(accountId);
        }
    }).catch(error => Swal.fire('Erro ao processar transação', '', 'error'));
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
            fetch('http://localhost:8000/bank_accounts', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(result.value)
            })
                .then(response => response.json())
                .then(data => {
                    Swal.fire('Conta cadastrada!', '', 'success');
                    showBankAccounts();
                })
                .catch(error => Swal.fire('Erro ao cadastrar conta', '', 'error'));
        }
    });
}

function showBankAccounts() {
    fetch('http://localhost:8000/bank_accounts')
        .then(response => response.json())
        .then(accounts => {
            const accountList = document.getElementById('accountList');
            accountList.innerHTML = '';

            accounts.forEach(account => {
                const accountDiv = document.createElement('div');
                accountDiv.classList.add('account-item');
                accountDiv.innerHTML = `
                    <span onclick="showAccountDetails(${account.id})">${account.nome}</span>
                    <button class="dots" onclick="toggleOptions(${account.id})">⋮</button>
                    <div class="account-options" id="options-${account.id}">
                        <button onclick="editAccount(${account.id}, '${account.nome}')">Editar</button>
                        <button onclick="removeAccount(${account.id})">Remover</button>
                    </div>
                `;
                accountList.appendChild(accountDiv);
            });
        })
        .catch(error => console.error('Erro ao carregar contas:', error));
}

function toggleOptions(accountId) {
    const options = document.getElementById(`options-${accountId}`);
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
}

function editAccount(accountId, currentName) {
    Swal.fire({
        title: 'Editar Nome da Conta',
        input: 'text',
        inputValue: currentName,
        showCancelButton: true,
        confirmButtonText: 'Salvar',
        preConfirm: (newName) => {
            if (!newName) {
                Swal.showValidationMessage('O nome não pode estar vazio');
            } else {
                return fetch(`http://localhost:8000/bank_accounts/${accountId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nome: newName })
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Erro ao editar conta');
                        return response.json();
                    });
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Nome alterado com sucesso!', '', 'success');
            showBankAccounts();
        }
    }).catch(error => Swal.fire('Erro ao editar conta', '', 'error'));
}

function removeAccount(accountId) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Essa ação não poderá ser desfeita!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, remover',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`http://localhost:8000/bank_accounts/${accountId}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Erro ao remover conta');
                    return response.json();
                })
                .then(() => {
                    Swal.fire('Conta removida!', '', 'success');
                    showBankAccounts();
                })
                .catch(error => Swal.fire('Erro ao remover conta', '', 'error'));
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    showBankAccounts();
});

function showBankAccounts() {
    fetch('http://localhost:8000/bank_accounts')
        .then(response => response.json())
        .then(accounts => {
            const accountList = document.getElementById('accountList');
            accountList.innerHTML = '';

            accounts.forEach(account => {
                const accountDiv = document.createElement('div');
                accountDiv.classList.add('account-item');
                accountDiv.innerHTML = `
                    <span onclick="showAccountDetails(${account.id})">${account.nome}</span>
                    <button class="dots" onclick="toggleOptions(${account.id})">⋮</button>
                    <div class="account-options" id="options-${account.id}">
                        <button onclick="editAccount(${account.id}, '${account.nome}')">Editar</button>
                        <button onclick="removeAccount(${account.id})">Remover</button>
                    </div>
                `;
                accountList.appendChild(accountDiv);
            });
        })
        .catch(error => console.error('Erro ao carregar contas:', error));
}

