<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Tekim Farma</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Estilo e ícones -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <!-- TOPO -->
  <header class="bg-primary text-white text-center py-3">
    <h1>💊  <img src="logo-instituto-ana.png" alt="Logo Instituto ANA" style="width: 300px;"> Farma</h1>
  </header>

  <!-- CONTEÚDO -->
  <main class="container my-4">
    <div class="row">
      <!-- FORMULÁRIO -->
      <div class="col-md-5 mb-4">
        <div class="card shadow p-3">
          <h4 class="mb-3"><i class="fas fa-plus-circle"></i> Solicitação de Medicamentos</h4>
          <form id="cadastroForm">
            <div class="mb-3">
              <label class="form-label">CPF:</label>
              <input type="text" class="form-control" maxlength="14" placeholder="000.000.000-00" id="cpf" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Nome:</label>
              <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Endereço:</label>
              <input type="text" class="form-control" name="endereco" required>
            </div>
            <div class="row">
              <div class="col">
                <label class="form-label">Título Eleitoral:</label>
                <input type="text" class="form-control" name="titulo">
              </div>
              <div class="col">
                <label class="form-label">Zona Eleitoral:</label>
                <input type="text" class="form-control" name="zona">
              </div>
            </div>
            <div class="row mt-3">
              <div class="col">
                <label class="form-label">Medicamento:</label>
                <input type="text" class="form-control" name="medicamento">
              </div>
              <div class="col">
                <label class="form-label">Data da Solicitação:</label>
                <input type="date" class="form-control" name="data">
              </div>
            </div>
            <div class="mt-4 d-flex justify-content-center gap-2">
              <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Salvar</button>
              <button type="reset" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</button>
            </div>
          </form>
        </div>
      </div>

      <!-- TABELA -->
      <div class="col-md-7">
        <div class="card shadow p-3">
          <h4 class="mb-3"><i class="fas fa-pills"></i> Medicamentos Solicitados</h4>
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>CPF</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Título</th>
                <th>Zona</th>
                <th>Medicamento</th>
                <th>Data</th>
                <th>Ação</th>
              </tr>
            </thead>
            <tbody id="listaMedicamentos"></tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <!-- TOAST DE ALERTA -->
  <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body" id="toast-msg">Mensagem aqui</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- JS FUNCIONALIDADE -->
  <script>
    const form = document.getElementById("cadastroForm");
    const tabela = document.getElementById("listaMedicamentos");
    const cpfInput = document.getElementById("cpf");
    let idEditando = null;

    cpfInput.addEventListener("input", function () {
      let value = cpfInput.value.replace(/\D/g, '');
      if (value.length > 11) value = value.slice(0, 11);
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
      cpfInput.value = value;
    });

    function editarLinha(botao) {
      const linha = botao.closest("tr");
      const dados = linha.querySelectorAll("td");

      document.getElementById("cpf").value = dados[0].textContent.trim();
      document.querySelector("[name='nome']").value = dados[1].textContent.trim();
      document.querySelector("[name='endereco']").value = dados[2].textContent.trim();
      document.querySelector("[name='titulo']").value = dados[3].textContent.trim();
      document.querySelector("[name='zona']").value = dados[4].textContent.trim();
      document.querySelector("[name='medicamento']").value = dados[5].textContent.trim();
      document.querySelector("[name='data']").value = dados[6].textContent.trim();

      idEditando = linha.dataset.id;
      linha.remove();
    }

    form.addEventListener("submit", async function (e) {
      e.preventDefault();

      const dados = {
        id: idEditando,
        cpf: form.querySelector("#cpf").value,
        nome: form.querySelector("[name='nome']").value,
        endereco: form.querySelector("[name='endereco']").value,
        titulo_eleitoral: form.querySelector("[name='titulo']").value,
        zona_eleitoral: form.querySelector("[name='zona']").value,
        nome_medicamento: form.querySelector("[name='medicamento']").value,
        data_solicitacao: form.querySelector("[name='data']").value
      };

      try {
        const response = await fetch("cadastrar.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(dados)
        });

        const resultado = await response.json();

        if (resultado.status === "sucesso") {
          mostrarToastBootstrap(
            resultado.acao === "inserido" ? "Medicamento cadastrado!" : "Registro atualizado com sucesso!",
            "success"
          );
          form.reset();
          idEditando = null;
          carregarTabela();
        } else {
          mostrarToastBootstrap("Erro: " + resultado.mensagem, "error");
        }
      } catch (err) {
        alert("Erro na requisição: " + err.message);
      }
    });

    async function carregarTabela() {
      try {
        const res = await fetch('get_registros.php');
        const data = await res.json();
        const tbody = document.getElementById("listaMedicamentos");
        tbody.innerHTML = '';
        data.forEach(row => {
          const tr = document.createElement("tr");
          tr.dataset.id = row.id;
          tr.innerHTML = `
            <td>${row.cpf}</td>
            <td>${row.nome}</td>
            <td>${row.endereco}</td>
            <td>${row.titulo_eleitoral}</td>
            <td>${row.zona_eleitoral}</td>
            <td>${row.nome_medicamento}</td>
            <td>${row.data_solicitacao}</td>
            <td>
              <button class="btn btn-sm btn-primary me-2" onclick="editarLinha(this)">
                <i class="fas fa-pen"></i>
              </button>
              <button class="btn btn-sm btn-danger" onclick="deletarLinha(this)">
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      } catch (err) {
        console.error("Erro ao buscar dados:", err);
      }
    }

    function deletarLinha(botao) {
      const linha = botao.closest("tr");
      const id = linha.dataset.id;

      fetch("deletar.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
      })
      .then(res => res.json())
      .then(resp => {
        if (resp.status === "sucesso") {
          linha.remove();
          mostrarToastBootstrap("Solicitação excluída com sucesso", "success");
        } else {
          mostrarToastBootstrap("Erro ao excluir", "error");
        }
      })
      .catch(() => alert("Erro ao conectar ao servidor."));
    }

    function mostrarToastBootstrap(msg, tipo) {
      const toast = document.getElementById("liveToast");
      const body = document.getElementById("toast-msg");

      body.textContent = msg;
      toast.classList.remove("bg-success", "bg-danger");
      toast.classList.add(tipo === "error" ? "bg-danger" : "bg-success");

      new bootstrap.Toast(toast).show();
    }

    // traga dados do cpf já cadastrado


    document.getElementById("cpf").addEventListener("blur", async function () {
  const cpf = this.value.replace(/\D/g, ""); // Remove máscara

  if (cpf.length === 11) {
    try {
      const response = await fetch("buscar_dados_por_cpf.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ cpf })
      });

      const dados = await response.json();

      if (dados && dados.nome) {
        document.querySelector("[name='nome']").value = dados.nome;
        document.querySelector("[name='endereco']").value = dados.endereco;
        document.querySelector("[name='titulo']").value = dados.titulo_eleitoral;
        document.querySelector("[name='zona']").value = dados.zona_eleitoral;

       
      }

      // Atualiza a tabela com solicitações do CPF
      atualizarTabelaPorCpf(cpf);
    } catch (err) {
      console.error("Erro ao buscar CPF:", err);
    }
  }
});

// Função para atualizar a tabela com registros do CPF
async function atualizarTabelaPorCpf(cpf) {
  try {
    const response = await fetch("get_registros.php?cpf=" + encodeURIComponent(cpf));
    const registros = await response.json();

    const tbody = document.getElementById("listaMedicamentos");
    tbody.innerHTML = "";

    registros.forEach(row => {
      const tr = document.createElement("tr");
      tr.dataset.id = row.id;
      tr.innerHTML = `
        <td>${row.cpf}</td>
        <td>${row.nome}</td>
        <td>${row.endereco}</td>
        <td>${row.titulo_eleitoral}</td>
        <td>${row.zona_eleitoral}</td>
        <td>${row.nome_medicamento}</td>
        <td>${row.data_solicitacao}</td>
        <td>
          <button class="btn btn-sm btn-primary me-2" onclick="editarLinha(this)">
            <i class="fas fa-pen"></i>
          </button>
          <button class="btn btn-sm btn-danger" onclick="deletarLinha(this)">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  } catch (err) {
    console.error("Erro ao buscar solicitações por CPF:", err);
  }
}



    window.onload = carregarTabela;
  </script>

</body>
</html>
