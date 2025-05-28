<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Tekim Farma</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

  <header>
  <h1>💊 Tekim Farma</h1>
  </header>

  <main class="content">

    <section class="form-container">
      <h2><i class="fas fa-plus-circle"></i> Solicitação de Medicamentos</h2>
      <form id="cadastroForm">
        <label>CPF:</label>
        <input type="text" maxlength="14" placeholder="000.000.000-00" id="cpf" required>

        <label>Nome:</label>
        <input type="text" name="nome" required>

        <label>Endereço:</label>
        <input type="text" name ="endereco" required>

       
        <div class="linha">
            <div class="coluna">
              <label>Título Eleitoral:</label>
              <input type="text" name="titulo">
            </div>
            <div class="coluna">
              <label>Zona Eleitoral:</label>
              <input type="text" name="zona">
            </div>
          </div>
        
          <div class="linha">
            <div class="coluna">
              <label>Nome do Medicamento:</label>
              <input type="text" name="medicamento">
            </div>
            <div class="coluna">
              <label>Data da Solicitação:</label>
              <input type="date" name="data">
            </div>
          </div>

        <div class="buttons">
          <button type="submit" class="btn-green"><i class="fas fa-check"></i> Salvar</button>
          <button type="reset" class="btn-gray"><i class="fas fa-times"></i> Cancelar</button>
        </div>
      </form>
    </section>

    <section class="tabela">
      <h2><i class="fas fa-pills"></i> Medicamentos Solicitados</h2>
      <table>
        <thead>
          <tr>
            <th>CPF</th>
            <th>Nome</th>
            <th>Endereço</th>
            <th>Titulo Eleitoral</th>
            <th>Zona Eleitoral</th>
            <th>Nome do Medicamento</th>
            <th>Data da Solicitação</th>
          </tr>
        </thead>
        <tbody id="listaMedicamentos">
          <!-- Registros aparecerão aqui -->
        </tbody>
      </table>
    </section>
  </main>
  <script>
    window.onload = function () {
  fetch('get_registros.php')
    .then(response => response.json())
    .then(data => {
      const tbody = document.querySelector("table tbody");
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
          <button class="btn btn-sm btn-danger" onclick="deletarLinha(this)" title="Excluir">
        <i class="fas fa-trash-alt"></i>
        </button>
        `;
        tbody.appendChild(tr);
      });
    })
    .catch(error => console.error("Erro ao buscar dados:", error));
};
    const cpfInput = document.getElementById("cpf");

cpfInput.addEventListener("input", function () {
  let value = cpfInput.value.replace(/\D/g, ''); // remove tudo que não for número

  if (value.length > 11) value = value.slice(0, 11); // limita 11 dígitos

  value = value.replace(/(\d{3})(\d)/, '$1.$2');
  value = value.replace(/(\d{3})(\d)/, '$1.$2');
  value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

  cpfInput.value = value;
});
    const form = document.getElementById("cadastroForm");
    const tabela = document.getElementById("listaMedicamentos");
  
    form.addEventListener("submit", async function (e) {
  e.preventDefault();

  const cpf = form.querySelectorAll("input")[0].value;
  const nome = form.querySelectorAll("input")[1].value;
  const endereco = form.querySelectorAll("input")[2].value;
  const titulo = form.querySelectorAll("input")[3].value;
  const zona = form.querySelectorAll("input")[4].value;
  const medicamento = form.querySelectorAll("input")[5].value;
  const data = form.querySelectorAll("input")[6].value;

  const dados = {
    cpf: cpf,
    nome: nome,
    endereco: endereco,
    titulo_eleitoral: titulo,
    zona_eleitoral: zona,
    nome_medicamento: medicamento,
    data_solicitacao: data
  };

  try {
    const response = await fetch("cadastrar.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(dados)
    });

    const resultado = await response.json();

    if (resultado.status === "sucesso") {
      // Adiciona na tabela
      const novaLinha = document.createElement("tr");
      novaLinha.innerHTML = `
        <td>${cpf}</td>
        <td>${nome}</td>
        <td>${endereco}</td>
        <td>${titulo}</td>
        <td>${zona}</td>
        <td>${medicamento}</td>
        <td>${data}</td>
        <button class="btn btn-sm btn-danger" onclick="deletarLinha(this)" title="Excluir">
        <i class="fas fa-trash-alt"></i>
        </button>

      `;
      tabela.appendChild(novaLinha);
      mostrarToastBootstrap("Medicamento cadastrado com sucesso!", "success");



    } else {
      mostrarToastBootstrap("Erro ao cadastrar!", "error");



    }

    form.reset();
  } catch (error) {
    alert("Erro na requisição: " + error.message);
  }
});
// Deletar linha
function deletarLinha(botao) {
  const linha = botao.closest("tr");
  const id = linha.dataset.id;

  fetch("deletar.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: id })
  })
  .then(res => res.json())
  .then(response => {
    if (response.status === "sucesso") {
      linha.remove();
    } else {
      alert("Erro: " + response.mensagem);
    }
  })
  .catch(() => {
    alert("Erro na conexão com o servidor.");
  });
}

// Mostrar Pop Cadastro

function mostrarToastBootstrap(mensagem, tipo = "success") {
  const toastEl = document.getElementById("liveToast");
  const toastBody = document.getElementById("toast-msg");

  toastBody.textContent = mensagem;

  // Define cor do toast (success ou danger)
  toastEl.classList.remove("bg-success", "bg-danger");
  toastEl.classList.add(tipo === "error" ? "bg-danger" : "bg-success");

  const toast = new bootstrap.Toast(toastEl);
  toast.show();
}





  </script>
 
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
  <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="toast-msg">
        Mensagem aqui
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>


</body>
</html>
