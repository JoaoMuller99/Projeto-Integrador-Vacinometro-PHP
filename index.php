<?php
include_once "conexao.php";
include_once "get_cidades.php";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Vacinômetro" />
  <link rel="icon" href="./imgs/favicon.ico" />
  <link rel="stylesheet" href="globals.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">
  <title>Projeto Integrador - Vacinômetro</title>
</head>

<body>
  <main>
    <header>
      <svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="24" y="1" width="23" height="68" rx="11.5" stroke="#3137CA" strokeWidth="2" />
        <path fillRule="evenodd" clipRule="evenodd"
          d="M0 35.5C0 42.4036 5.59644 48 12.5 48H21V23H12.5C5.59644 23 0 28.5964 0 35.5ZM50 48H57.5C64.4036 48 70 42.4036 70 35.5C70 28.5964 64.4036 23 57.5 23H50V48Z"
          fill="#83CDF1" />
        <circle cx="40" cy="21" r="2" fill="#3137CA" />
        <circle cx="31" cy="28" r="2" fill="#3137CA" />
        <circle cx="39" cy="51" r="2" fill="#3137CA" />
        <circle cx="31.5" cy="15.5" r="2.5" fill="#3137CA" />
        <circle cx="39.5" cy="35.5" r="2.5" fill="#3137CA" />
        <circle cx="31.5" cy="43.5" r="2.5" fill="#3137CA" />
      </svg>
      <h1>Vacinômetro</h1>
    </header>
    <div>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <div class="select-main-container">
        <div>
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M9.33512 4.80232C9.74423 4.73752 10.0234 4.35334 9.95856 3.94423C9.89376 3.53511 9.50958 3.25599 9.10047 3.32079C6.64008 3.71047 4.71044 5.64012 4.32076 8.1005C4.25596 8.50961 4.53508 8.89379 4.9442 8.95859C5.35331 9.02339 5.73749 8.74426 5.80229 8.33515C6.09032 6.51661 7.51658 5.09035 9.33512 4.80232Z"
              fill="#3137CA" />
            <path fillRule="evenodd" clipRule="evenodd"
              d="M11 0.25C5.61522 0.25 1.25 4.61522 1.25 10C1.25 15.3848 5.61522 19.75 11 19.75C16.3848 19.75 20.75 15.3848 20.75 10C20.75 4.61522 16.3848 0.25 11 0.25ZM2.75 10C2.75 5.44365 6.44365 1.75 11 1.75C15.5563 1.75 19.25 5.44365 19.25 10C19.25 14.5563 15.5563 18.25 11 18.25C6.44365 18.25 2.75 14.5563 2.75 10Z"
              fill="#3137CA" />
            <path
              d="M19.5304 17.4698C19.2375 17.1769 18.7626 17.1769 18.4697 17.4698C18.1768 17.7626 18.1768 18.2375 18.4697 18.5304L22.4696 22.5304C22.7625 22.8233 23.2374 22.8233 23.5303 22.5304C23.8232 22.2375 23.8232 21.7626 23.5303 21.4697L19.5304 17.4698Z"
              fill="#3137CA" />
          </svg>
          <select name="cidades" id="cidades" title="Select">
            <option value="-1" disabled selected>
              SELECIONE UMA CIDADE...
            </option>
            <?php
            foreach ($registro["cidades"] as $cidade) {
              echo "<option value='" . $cidade["estabelecimento_municipio_nome"] . "'>" . $cidade["estabelecimento_municipio_nome"] . "</option>";
            }
            ?>
          </select>
        </div>
        <button id="buscar">
          Buscar
        </button>
      </div>
      <div class="infos-vacinas-container">
        <h3><span></span> Resultados</h3>
        <div></div>
      </div>
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

  <script>
    async function chamada(url, valorSelecionado) {
      return new Promise(resultado => {
        $.ajax({
          url: url,
          method: 'POST',
          data: { cidade: valorSelecionado },
          success: function (response) {
            resultado(JSON.parse(response));
          },
          error: function (error) {
            resultado(null);
          }
        })
      })
    }


    $('#buscar').click(async () => {
      const valorSelecionado = $("select").val();

      if (valorSelecionado) {
        const doses = await chamada('post_filtra_vacinas.php', valorSelecionado);
        let vacinas = await chamada('post_nomes_vacinas.php', valorSelecionado);

        if (doses && vacinas) {
          $('.infos-vacinas-container > h3 > span').text(doses.length);
          $('.infos-vacinas-container > div').empty();

          vacinas = vacinas.map((item) => {
            return { ...item, quantidadePrimeiraDose: 0, quantidadeSegundaDose: 0 };
          });

          doses.forEach(item => {
            for (let index = 0; index < vacinas.length; index++) {
              if (vacinas[index].vacina_nome === item.vacina_nome) {
                if (item.vacina_descricao_dose === "1ª Dose") vacinas[index].quantidadePrimeiraDose += 1;
                else if (item.vacina_descricao_dose === "2ª Dose") vacinas[index].quantidadeSegundaDose += 1;
              }
            }
          });

          vacinas.forEach(vacina => {
            const html = `<div>
                            <h6>${vacina.vacina_nome.includes("CORONAVAC") ? "coronavac" : vacina.vacina_nome.toLowerCase()}</h6>
                            <span>
                              1ª Dose: <span>${vacina.quantidadePrimeiraDose}</span>
                            </span>
                            <span>
                              2ª Dose: <span>${vacina.quantidadeSegundaDose}</span>
                            </span>
                          </div>`
            $('.infos-vacinas-container > div').append(html);
          });

          $('.infos-vacinas-container').css("display", "flex");
        }
      }
    })
  </script>
</body>

</html>